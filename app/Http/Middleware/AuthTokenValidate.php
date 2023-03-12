<?php

namespace App\Http\Middleware;

use Closure;
use Auth;
use Cookie;
use App\Models\Admin\AdminAuthToken;
use App\Models\UserAuthToken;
use App\Http\Controllers\YokartController;

class AuthTokenValidate
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $yokartObj = new YokartController();
        $cookieToken = $yokartObj->retrieveCookie('RememberAdmin');
        if (empty(Auth::guard('admin')->check()) && !empty($cookieToken)) {
            $authToken = AdminAuthToken::getRecordByToken($cookieToken);
            if (!empty($authToken)) {
                Auth::guard('admin')->loginUsingId($authToken->admauth_admin_id);
            }
        }

        $userCookieToken = $yokartObj->retrieveCookie('RememberUser');
        if (empty(Auth::check()) && !empty($userCookieToken)) {
            $authToken = UserAuthToken::getRecordByToken($userCookieToken);
            if (!empty($authToken)) {
                Auth::loginUsingId($authToken->usrauth_user_id);
            }
        }
        
        return $next($request);
    }
}
