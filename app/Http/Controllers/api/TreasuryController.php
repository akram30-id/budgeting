<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\api\Treasury;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;

class TreasuryController extends Controller
{
    public function getListCash(Request $request)
    {
        try {
            $user = Auth::user();

            $page = intval($request->query('page', 1));
            $length = intval($request->query('length', 15));

            $keywords = $request->query('keywords', '');

            if ($length > 100) {
                $length = 100;
            }

            $listTreasury = Treasury::getListTreasuries($user->id, $page, $length, $keywords);

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

    public function deleteCash(Request $request)
    {

        $validate = Validator::make($request->all(), [
            'treasury_no' => 'required'
        ], [
            'treasury_no.required' => 'Can not find empty treasury no.'
        ]);

        if ($validate->fails()) {
            return response()->json([
                'success' => false,
                'message' => $validate->errors()->first()
            ], 400);
        }

        $user = Auth::user();

        $getTreasury = Treasury::getTreasuryByNo($request->treasury_no, $user->id);

        if (!$getTreasury) {
            return response()->json([
                'success' => false,
                'message' => 'No treasury found (' . $request->treasury_no . ')'
            ], 404);
        }

        try {

            DB::beginTransaction();

            Treasury::deleteTreasury($request->treasury_no);

            DB::commit();

            return response()->json([
                'success' => true,
                'data' => 'Successfully deleted.'
            ], 200);
        } catch (\Throwable $th) {

            DB::rollBack();

            Log::error(json_encode([
                'request' => $request->all(),
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

    public function addCash(Request $request)
    {

        $validate = Validator::make($request->all(), [
            'month' => 'required',
            'year'  => 'required'
        ], [
            'month.required'    => 'Month is required',
            'year.required'     => 'Year is required'
        ]);

        if ($validate->fails()) {
            return response()->json([
                'success' => false,
                'message' => $validate->errors()->first()
            ], 400);
        }

        $user = Auth::user();

        try {
            DB::beginTransaction();

            $createWithLastId = DB::table('treasuries')
                ->insertGetId([
                    'month'     => $request->month,
                    'year'      => $request->year,
                    'owner_id'  => $user->id,
                    'created_at' => now(),
                    'updated_at' => now()
                ]);

            if ($createWithLastId) {
                $treasuryNo = "TRE" . str_pad($createWithLastId, 10, "0", STR_PAD_LEFT);

                $updateTreasury = DB::table('treasuries')
                    ->where('id', $createWithLastId)
                    ->update([
                        'treasury_no' => $treasuryNo
                    ]);

                if ($updateTreasury) {
                    DB::commit();

                    return response()->json([
                        'success' => true,
                        'message' => 'Successfully created ' . $treasuryNo
                    ], 200);
                } else {
                    DB::rollBack();

                    return response()->json([
                        'success' => false,
                        'message' => 'Failed to create new treasury'
                    ], 422);
                }
            }
        } catch (\Throwable $th) {
            $this->logError($th);

            DB::rollBack();

            return response()->json([
                'success' => false,
                'message' => 'Something went wrong'
            ], 500);
        }
    }
}
