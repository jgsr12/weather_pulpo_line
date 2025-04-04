<?php

namespace App\Http\Middleware;

use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken as Middleware;

class VerifyCsrfToken extends Middleware
{
    /**
     * The URIs that should be excluded from CSRF verification.
     *
     * @var array<int, string>
     */
    protected $except = [
        //
          'api/reservas',
          'api/clientes/login',
          'api/clientes/forgot-password',
          'api/clientes/reset-password',
          'api/clientes/register'
    ];
}
