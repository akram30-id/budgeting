<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class UserController extends Controller
{
    public function login()
    {

        $data = [
            'title' => 'F-Finance - Sign In',
            'pageTitle' => 'Sign In',
            'api_login' => config('services.app_url') . '/api/login',
            'save_token_url' => config('services.app_url') . '/save-token',
        ];

        return view('auth.login', $data);
    }

    public function register()
    {
        return view('auth.register');
    }

    public function saveTokenToSession(Request $request)
    {

        try {

            $accessToken = $request->input('access_token');
            $remember = $request->boolean('remember');

            // Save the access token to the session
            $request->session()->put('remember_me', $remember);

            // set cookie duration
            $minutes = $remember ? 60 * 24 * 30 : 60 * 24;
            // // untuk testing
            // $minutes = $remember ? 1 : 60 * 24;

            $secure = config('session.secure', false);

            return response()->json([
                'success' => true,
                'message' => 'Access token saved to session',
                'ttl' => $minutes,
                'secure' => $secure
            ], 200)->withCookie(cookie(
                'access_token',
                $accessToken,
                $minutes,
                '/',
                null,
                $secure,
                true
            ));
        } catch (\Throwable $th) {

            return response()->json([
                'success' => false,
                'message' => 'Failed to save access token to session'
            ], 500);

        }
    }

    public function logout(Request $request)
    {
        // Clear the access token from the session
        $request->session()->forget('access_token');

        return redirect('/login')->withCookie(
            cookie()->forget('access_token')
        );
    }
}
