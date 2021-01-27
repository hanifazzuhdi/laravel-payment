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
        'http://cf97da0dcb50.ngrok.io/midtrans/notification',
        'http://cf97da0dcb50.ngrok.io',
        'https://api.sandbox.midtrans.com/v2',
        'https://app.sandbox.midtrans.com/snap/v1'
    ];
}
