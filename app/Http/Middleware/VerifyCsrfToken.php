<?php

namespace App\Http\Middleware;

use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken as Middleware;

class VerifyCsrfToken extends Middleware
{
    /**
     * Indicates whether the XSRF-TOKEN cookie should be set on the response.
     *
     * @var bool
     */
    protected $addHttpCookie = true;

    /**
     * The URIs that should be excluded from CSRF verification.
     *
     * @var array
     */
    protected $except = [
        // '/newsletter'
        '/dropzone',
        'admin/blogs/images/*',
        'admin/editor/images',
        'admin/pages/skill-level/*',
        'orderPayment/sucess/cash-free',
        'installer/migration-seeding-data',
        'installer/update-config',
    ];
}
