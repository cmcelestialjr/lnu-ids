<?php

namespace App\Http\Middleware;

use Closure;

class HttpsProtocol
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
        if (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off')
        {
            return $next($request);
        }else{
            return redirect('/error/http');
        }
    }
}
