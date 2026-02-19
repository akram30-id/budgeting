<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Laravel\Sanctum\PersonalAccessToken;

class GlobalAuthorizationMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {

        if (!$request->session('access_token') && $request->cookie('remember_login') && $request->cookie('token_in_cookie')) {
            // VALIDASI TOKEN DI DB
            $hashed = hash('sha256', $request->cookie('token_in_code'));

            $token = PersonalAccessToken::where('token', $hashed)
                ->where('expires_at', '>', now())
                ->first();

            if ($token) {
                $request->session()->put('access_token', $request->cookie('token_in_cookie'));
            }
        }

        if (!$request->session('access_token')) {
            return redirect('/login');
        }

        return $next($request);
    }
}
