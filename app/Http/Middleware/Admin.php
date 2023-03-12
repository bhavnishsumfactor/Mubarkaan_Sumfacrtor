<?php

namespace App\Http\Middleware;

use Closure;
use Auth;

class Admin
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
        if (Auth::guard('admin')->check()) {
            if (Auth::guard('admin')->user()->admin_publish != 1) {
                if ($request->ajax()) {
                    return jsonresponse(401, null);
                }
                return redirect('/admin/logout');
            }
            return $next($request);
        }
        if ($request->ajax()) {

            return jsonresponse(401, null);
        }
        return redirect('/admin/login');
    }
}
