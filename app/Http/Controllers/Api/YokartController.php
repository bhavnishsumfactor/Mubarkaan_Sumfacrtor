<?php
namespace App\Http\Controllers\Api;

use Illuminate\Routing\Controller as BaseController;

class YokartController extends BaseController
{
    protected $cartSession;
    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            $this->cartSession = $request->header('sess-token');
            return $next($request);
        });
    }
}
