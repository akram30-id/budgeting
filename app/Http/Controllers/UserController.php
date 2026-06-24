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
            $userId = $request->input('user_id');

            // Save the access token to the session
            $request->session()->put('access_token', $accessToken);
            $request->session()->put('user_id', $userId);

            if ($remember) {
                cookie()->queue(cookie('remember_login', true, 60 * 24 * 30));
                cookie()->queue(cookie('token_in_cookie', $accessToken, 60 * 24 * 30));
                cookie()->queue(cookie('user_id', $userId, 60 * 24 * 30));
            }

            return response()->json([
                'success' => true,
                'message' => 'Access token saved to session'
            ], 200);
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

        return redirect('/login');
    }
}
