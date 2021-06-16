<?php

namespace App\Http\Models;

use App\Elibs\Debug;
use App\Elibs\eBug;
use App\Elibs\eView;
use App\Elibs\Helper;
use Illuminate\Http\Request;
use Illuminate\Routing\Route;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Support\Facades\Redirect;
use Symfony\Component\Mime\Encoder\Base64Encoder;

class Member extends BaseModel
{
    //
    const table_name = 'y_member';

    static $permision = [];

    protected $table = self::table_name;

    protected $fillable = [];
    static $unguarded = TRUE;
    static $currentMember = [];// id, name, mô tả......

    const USER_KEY = 'user_token';
    const SESSION_KEY_FOR_CUR_MEMBER = 'local_user';
    const COOKIE_KEY_FOR_CUR_MEMBER = 'thany';

    const STATUS_ACTIVE = 'active';
    const STATUS_DISABLE = 'disabled';

    const ACTIVE_MEMBER = 'active';
    const DISABLE_MEMBER = 'disabled';

    static function getCurrentMember()
    {
        if (self::$currentMember) {
            return self::$currentMember;
        }

        $uid = Request::capture()->input('uid', '');

        if ($uid) {
            $member = self::getMemberByUid($uid);

            if (!$member) {
                return FALSE;
            } else {
                self::setCurrent($member->toArray());
            }
        }

        return self::$currentMember;
    }

    static function getCurrentMemberId($object = FALSE)
    {

        if ($object) {
            return isset(self::$currentMember['_id']) ? Helper::convertToObjectId(self::$currentMember['_id']) : FALSE;
        }
        return isset(self::$currentMember['_id']) ? self::$currentMember['_id'] : FALSE;
    }

    static function getMemberByEmail($email, $fields = '*')
    {
        if (!$email) {
            return [];
        }
        $where = [
            'email' => Helper::convertEmail($email),
        ];
        $member = static::select($fields)->where($where)->first();

        return $member;
    }

    static function getMemberByPhone($phone)
    {
        if (!$phone) {
            return [];
        }
        $where = [
            'phone' => $phone,
        ];
        $member = static::where($where)->first();

        return $member;
    }

    static function getMemberByUid($uid)
    {
        if (!$uid) {
            return [];
        }
        $where = [
            '_id' => $uid,
        ];
        $member = static::where($where)->first();

        return $member;
    }

    static function getMemberByAccount($username)
    {
        if (!$username) {
            return [];
        }
        $where = [
            'account' => $username,
        ];
        $member = static::where($where)->first();

        return $member;
    }

    static function loginSystem()
    {
        if (in_array(\Illuminate\Support\Facades\Route::current()->getName(),['CannotAccess'])) {
            return false;
        }

        $p = @Member::$currentMember[Member::USER_KEY];
        if(!$p){
            $p = \request('user_token');
        }

        if(!$p){
            $uid = \request('uid');
            $p = Member::getUserById($uid);
        }
        \App\Exceptions\Handler::$__countEx = true;

        $LoginMember = [
            'client_info' => [
                'agent' => \Request::server('HTTP_USER_AGENT'),
                'referrer' => \Request::server('HTTP_REFERER'),
                'ip' => \Request::ip(),
            ],
            'type' => 'login',
            'time' => Helper::getMongoDate(),
        ];
        LogLogin::insert($LoginMember);

        $l =  base64_encode(Helper::encrypt(json_encode(['l' => request()->fullUrl()])));

        throw new HttpResponseException(redirect('/auth/login?p='.$p . '&l=' . $l ));

    }

    static function encodePassword($password)
    {
        return md5('thany' . $password . 'ynhan');
    }

    static function setCurrent($current = [])
    {
        if ($current) {
            self::$currentMember = $current;
            Helper::setSession(self::SESSION_KEY_FOR_CUR_MEMBER, self::$currentMember);
        } else {
            self::$currentMember = Helper::getSession(self::SESSION_KEY_FOR_CUR_MEMBER);
        }
    }

    static function getCurrent()
    {
        if (self::$currentMember) {
            return self::$currentMember;
        } else {
            self::$currentMember = Helper::getSession(self::SESSION_KEY_FOR_CUR_MEMBER);
            return self::$currentMember;
        }
    }

    static function getCurrentId()
    {
        return isset(self::$currentMember['_id']) ? self::$currentMember['_id'] : 0;
    }

    static function getCurrentAccount()
    {
        return @self::$currentMember['email'];
    }

    static function getCurrentEmail()
    {
        return @self::$currentMember['email'];
    }

    static function getCurrentName()
    {
        return @self::$currentMember['name'];
    }

    static function getCurrentPhone()
    {
        return @self::$currentMember['phone'];
    }

    static function getTokenForgotPassword($member)
    {
        $encodedEmail = base64_encode($member['email']);
        $hash = md5($member['email'] . 'thany-ynhan' . $member['email']);

        return $hash . '::' . $encodedEmail;
    }

    static function getEmailFromTokenForgotPassword($token = FALSE)
    {
        if (!$token) {
            return FALSE;
        }
        $tokenArr = explode('::', $token);
        if (count($tokenArr) < 2) {
            return FALSE;
        }
        $hash = $tokenArr[0];
        $encodedEmail = $tokenArr[1];
        $email = base64_decode($encodedEmail);
        if (!Helper::isEmail($email)) {
            return FALSE;
        }
        if ($hash === md5($email . 'ynhan-thany' . $email)) {
            return $email;
        }
    }

    static function getTokenActiveEmail($member)
    {
        $encodedEmail = base64_encode($member['email']);
        $hash = md5($member['email'] . 'choaidocacuocdoi.thany.ynhan.com' . $member['email']);

        return $hash . '::' . $encodedEmail;
    }

    static function getEmailFromTokenActiveEmail($token = FALSE)
    {
        if (!$token) {
            return FALSE;
        }
        $tokenArr = explode('::', $token);
        if (count($tokenArr) < 2) {
            return FALSE;
        }
        $hash = $tokenArr[0];
        $encodedEmail = $tokenArr[1];

        $email = base64_decode($encodedEmail);
        if (!Helper::isEmail($email)) {
            return FALSE;
        }
        if ($hash === md5($email . 'choaidocacuocdoi.thany.ynhan.com' . $email)) {
            return $email;
        }
    }

    static function getUserById($user_token)
    {
        return Member::where(['_id'=>$user_token])->first();
    }

    static function findMemberById($user)
    {
        $member = [];
        if(isset($user['id']) && $user['id'])
        {
            $member = static::where(['_id' => $user['id']])->first();
        }
        return $member;
    }

    static function isLogin()
    {
        $user = Helper::getSession(self::SESSION_KEY_FOR_CUR_MEMBER, false, 'crm_');
        if (isset($user['status']) && $user['status'] === Member::STATUS_ACTIVE) {
            return true;
        }
        return false;
    }

    static function setLogin($member, $update = false)
    {
        if (!$member) {
            return FALSE;
        }

        if (!is_array($member)) {
            $member = $member->toArray();
        }

        //
        Helper::setSession(Member::SESSION_KEY_FOR_CUR_MEMBER, $member);
        Helper::setCookie(Member::COOKIE_KEY_FOR_CUR_MEMBER, $member['email'] . ':' . md5($member['email'] . 'ynhan'));

        if (!$update) {
            /*Ghi log login*/
            $LoginMember = [
                'email' => $member['email'],
                'id' => $member['_id'],
                'client_info' => [
                    'agent' => \Request::server('HTTP_USER_AGENT'),
                    'referrer' => \Request::server('HTTP_REFERER'),
                    'ip' => \Request::ip(),
                ],
                'type' => 'login',
                'time' => Helper::getMongoDate(),
            ];
            LogLogin::insert($LoginMember);
            /*End Ghi log login*/
        }
    }

    static function setLogOut()
    {
        Helper::delSession(Member::SESSION_KEY_FOR_CUR_MEMBER);
        Helper::delCookie(Member::COOKIE_KEY_FOR_CUR_MEMBER);
    }

    static function isProduction()
    {
        return config('app.env') == 'production';
    }
}
