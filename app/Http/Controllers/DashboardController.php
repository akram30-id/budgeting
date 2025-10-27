<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DashboardController extends Controller
{
    //
    public function index()
    {
        $data = [
            'title' => 'F-Finance - Dashboard',
            'pageTitle' => 'Dashboard',
            'api_logout' => config('services.app_url') . '/api/logout',
            'api_token' => session('access_token'),
            'url_logout' => config('services.app_url') . '/logout',
        ];

        return view('dashboard', $data);
    }
}
