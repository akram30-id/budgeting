<?php

namespace App\Http\Controllers;


class SettingsController extends Controller
{
    public function index()
    {
        $data = [
            'title'                 => 'Finance Hub - User Settings',
            'pageTitle'             => 'User Settings',
            'sub_module_name'       => 'vendors',
            'api_update_password'   => config('services.app_url_go') . '/api/auth/change-password',
        ];

        return view('settings.index', $data);
    }
}
