<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\api\Treasury;
use Illuminate\Support\Facades\Log;

class TreasuryController extends Controller
{
    public function getListCash(Request $request)
    {
        try {
            $user = Auth::user();

            $page = intval($request->query('page', 1));
            $length = intval($request->query('length', 15));

            if ($length > 100) {
                $length = 100;
            }

            $listTreasury = Treasury::getListTreasuries($user->id, $page, $length);

            if (!$listTreasury) {
                return response()->json([
                    'success' => false,
                    'message' => 'No data found.'
                ], 404);
            }

            return response()->json([
                'success' => true,
                'page' => $page,
                'length' => $length,
                'data' => $listTreasury
            ], 200);
        } catch (\Throwable $th) {

            Log::error(json_encode([
                'file' => $th->getFile(),
                'line' => $th->getLine(),
                'message' => $th->getMessage()
            ]));

            return response()->json([
                'success' => false,
                'message' => 'Something went wrong'
            ], 500);
        }
    }
}
