<?php

namespace App\Elibs;

use App\Http\Middleware\EncryptCookies;
use App\Http\Models\Member;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Redis;
use Illuminate\Support\Facades\Session;

class UserHelper
{
    static private $instance = false;


    public function __construct()
    {
        self::$instance = &$this;
    }

    public static function &getInstance()
    {

        if (!self::$instance) {
            new self();
        }

        return self::$instance;
    }

    function getCurrentMemberAuth()
    {
        $member = Helper::getSession(Member::SESSION_KEY_FOR_CUR_MEMBER, '', false);

        if (!(isset($member['id']) && $member['id'])) {
            return false;

        }

        Member::$currentMember = Member::findMemberById($member);
        if (isset($member['name'])) {
            Member::$currentMember['name'] = $member['name'];
            Member::$currentMember['fullname'] = $member['fullname'];

        }
        //set lại các dữ liệu lấy được
        return Member::$currentMember;
    }
}
