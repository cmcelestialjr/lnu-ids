<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class CheckUpdatedPassword
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
        $user = Auth::user();

        if ($user->update_password==NULL || $user->update_password == 0 || $user->forgot_password==1) {
            return redirect()->route('change.password');
        }

        return $next($request);
    }
}
