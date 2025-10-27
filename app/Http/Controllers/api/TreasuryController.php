<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TreasuryController extends Controller
{
    public function getListCash()
    {
        $user = Auth::user();

        $userAccount = $user->email;


    }
}
