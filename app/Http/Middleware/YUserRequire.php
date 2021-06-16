<?php

namespace App\Http\Middleware;

use App\Elibs\eView;
use App\Elibs\Helper;
use App\Elibs\UserHelper;
use App\Http\Models\Member;
use Closure;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

class YUserRequire
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Closure $next
     * @param  string|null $guard
     *
     * @return mixed
     */
    public function handle($request, Closure $next, $guard = NULL)
    {
        if (!Helper::getSession(Member::SESSION_KEY_FOR_CUR_MEMBER)) {
            $cookie = Helper::getCookie(Member::COOKIE_KEY_FOR_CUR_MEMBER);
            if ($cookie) {
                $salt = explode(':', $cookie);
                if (isset($salt[1]) && sha1($salt[0] . 'ynhan') == $salt[1]) {
                    $member = Member::getMemberByEmail($salt[0]);
                    if($member) {
                        Member::setCurrent(Helper::getSession(Member::SESSION_KEY_FOR_CUR_MEMBER));
                        return $next($request);
                    }
                }
            }

            if ($request->ajax() || $request->wantsJson()) {
                return response('Phiên đăng nhập của bạn đã hết. Bạn cần đăng nhập lại hệ thống để thực hiện chức năng.(hãy mở tab khác để đăng nhập nhé)', 401);
            } else {
                Member::loginSystem();
            }
        }else{

            $member = Helper::getSession(Member::SESSION_KEY_FOR_CUR_MEMBER);
            $newMember = Member::getMemberByUid($member['_id']);
            if(!$newMember || $newMember['status'] === Member::DISABLE_MEMBER){
                Helper::setCookie(Member::COOKIE_KEY_FOR_CUR_MEMBER, '');
                return abort(401);
            }
        }

        Member::setCurrent(Helper::getSession(Member::SESSION_KEY_FOR_CUR_MEMBER));
        return $next($request);
    }

    public function __handle($request, Closure $next, $guard = NULL)
    {
        if (Auth::guard($guard)->guest()) {
            if ($request->ajax() || $request->wantsJson()) {
                return response('Unauthorized.', 401);
            } else {
                return redirect()->guest('auth/login');
            }
        }

        return $next($request);
    }
}
