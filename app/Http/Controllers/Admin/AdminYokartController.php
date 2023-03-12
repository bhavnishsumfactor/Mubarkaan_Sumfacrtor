<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use Auth;
use View;
use App\Models\Theme;
use App\Models\Configuration;

class AdminYokartController extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    protected $admin;
    protected $signed_in;

    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            $this->admin = Auth::guard('admin')->user();
            $this->signed_in = Auth::guard('admin')->check();
            \Cache::rememberForever('configuration', function () {
                return Configuration::whereNotIn('conf_name', ['EMAIL_FOOTER_DEFAULT_HTML', 'EMAIL_FOOTER_HTML', 'PACKING_SLIP_HTML', '	
                PACKING_SLIP_HTML_ORIGINAL', 'PACKING_SLIP_HTML_REPLACEMENT'])->get()->pluck('conf_val', 'conf_name');
            });
            return $next($request);
        });
    }
}
