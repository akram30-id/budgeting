<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class TreasuryController extends Controller
{
    public function cash()
    {

        $currentYear = date('Y');

        $years = [];

        for ($i=$currentYear; $i >= ($currentYear-10); $i--) {
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
