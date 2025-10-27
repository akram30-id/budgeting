<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class TreasuryController extends Controller
{

    public function index()
    {

        $data = [
            'title'                 => 'Finance Hub - Treasuries',
            'pageTitle'             => 'Treasuries',
            'list_treasuries_api'   => config('services.app_url') . '/api/list-treasury',
            'api_token'             => session('access_token')
        ];

        return view('treasury.index', $data);
    }

    public function cash()
    {

        $currentYear = date('Y');

        $years = [];

        for ($i = $currentYear; $i >= ($currentYear - 10); $i--) {
            $years[] = $i;
        }

        $data = [
            'title' => 'Finance Hub - Treasury Cash',
            'pageTitle' => 'Treasury',
            'years' => $years,
            'module' => 'treasuries'
        ];

        return view('treasury.cash', $data);
    }
}
