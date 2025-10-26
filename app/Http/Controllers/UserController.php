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

            // Save the access token to the session
            $request->session()->put('access_token', $accessToken);

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
}
