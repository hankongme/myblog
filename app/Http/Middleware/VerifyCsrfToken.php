<?php

namespace App\Http\Middleware;

use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken as Middleware;

class VerifyCsrfToken extends Middleware
{
    /**
     * The URIs that should be excluded from CSRF verification.
     *
     * @var array
     */
    protected $except = [
        'wxapi',
        __ADMIN_PATH__.'/tool/upload_image',
        __ADMIN_PATH__.'/ueditor/index',
        '/wxpay/user/notify',
        '/wxpay/company/notify',
        '/wxpay/user/store_money_notify',
    ];
}
