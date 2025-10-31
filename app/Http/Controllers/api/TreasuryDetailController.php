<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\api\Treasury;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use stdClass;
use GrahamCampbell\ResultType\Error;

class TreasuryDetailController extends Controller
{

    public function getListDetail(Request $request)
    {
        try {
            $user = Auth::user();

            $page = intval($request->query('page', 1));
            $length = intval($request->query('length', 15));

            $keywords = $request->query('keywords', '');

            $treasuryNo = $request->query('treasury_no', null);

            if (empty($treasuryNo)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Can not find empty treasury no.'
                ], 400);
            }

            $listDetail = Treasury::getTreasuryDetailByNo($treasuryNo, $user->id, $page, $length, $keywords);

            if (!$listDetail) {
                return response()->json([
                    'success' => false,
                    'message' => 'No data found.'
                ], 404);
            }

            $data = [];

            $actualBalanceFinal = 0;
            $estimateBalanceFinal = 0;

            foreach ($listDetail as $valDetail) {

                $detail = new stdClass;
                $detail->treasury_detail_no = $valDetail->treasury_detail_no;
                $detail->treasury_detail_name = $valDetail->treasury_detail_name;
                $detail->month = $valDetail->month;
                $detail->income_value = $valDetail->income_value;
                $detail->expense_value = $valDetail->expense_value;
                $detail->is_checked = $valDetail->is_checked;
                $detail->is_debt = $valDetail->is_debt;
                $detail->created_at = $valDetail->created_at;
                $detail->updated_at = $valDetail->updated_at;

                if ($valDetail->is_checked == 1) {
                    $actualBalanceFinal += $valDetail->income_value - $valDetail->expense_value;
                } else {
                    $actualBalanceFinal += $valDetail->income_value;
                }

                $estimateBalanceFinal += $valDetail->income_value - $valDetail->expense_value;

                $detail->estimate_value = $estimateBalanceFinal;

                $detail->actual_value = $actualBalanceFinal;

                $data[] = $detail;
            }

            return response()->json([
                'success' => true,
                'page' => $page,
                'length' => $length,
                'data' => $data
            ], 200);
        } catch (\Throwable $th) {

            Log::error(json_encode([
                'request'   => $request->all(),
                'file'      => $th->getFile(),
                'line'      => $th->getLine(),
                'message'   => $th->getMessage()
            ]));

            return response()->json([
                'success' => false,
                'message' => 'Something went wrong.'
            ], 500);
        }
    }

    public function updateTreasuryDetail(Request $request)
    {
        try {
            $user = Auth::user();

            $validate = Validator::make($request->all(), [
                'treasury_detail_no' => 'required',
            ], [
                'treasury_detail_no.required' => 'Can not find empty treasury detail no.',
            ]);

            if ($validate->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => $validate->errors()->first()
                ], 400);
            }

            $treasuryDetail = Treasury::getTreasuryDetail($request->treasury_detail_no);

            if (!$treasuryDetail) {
                return response()->json([
                    'success' => false,
                    'message' => 'No data found.'
                ], 404);
            }

            // Check permission
            if ($treasuryDetail->owner_id != $user->id) {
                $treasuryMember = Treasury::getTreasuryMemberDetail($request->treasury_detail_no, $user->id);

                if (!$treasuryMember) {
                    return response()->json([
                        'success' => false,
                        'message' => 'You are not a member of this treasury.'
                    ], 403);
                }

                if ($treasuryMember->can_edit == 0) {
                    return response()->json([
                        'success' => false,
                        'message' => 'You are not allowed to edit this treasury detail.'
                    ], 403);
                }
            }

            // 🔹 Kumpulkan semua update data (gunakan array merge)
            $updateData = [];

            if ($request->has('is_checked'))
                $updateData['is_checked'] = $request->is_checked;

            if ($request->has('is_debt'))
                $updateData['is_debt'] = $request->is_debt;

            if ($request->has('income_value'))
                $updateData['income_value'] = $request->income_value;

            if ($request->has('expense_value'))
                $updateData['expense_value'] = $request->expense_value;

            if ($request->has('notes'))
                $updateData['notes'] = $request->notes;

            if (empty($updateData)) {
                return response()->json([
                    'success' => false,
                    'message' => 'No fields to update.'
                ], 400);
            }

            // 🔹 Lakukan update
            $updateChecked = Treasury::updateTreasuryDetail($request->treasury_detail_no, $updateData);

            // 🔹 Cek hasil update sebelum return
            if ($updateChecked['error'] === true) {
                throw new \Exception($updateChecked['message']);
            }

            return response()->json([
                'success' => true,
                'message' => 'Successfully updated.'
            ], 200);
        } catch (\Throwable $th) {
            Log::error(json_encode([
                'request' => $request->all(),
                'file' => $th->getFile(),
                'line' => $th->getLine(),
                'message' => $th->getMessage()
            ]));

            return response()->json([
                'success' => false,
                'message' => 'Something went wrong.'
            ], 500);
        }
    }
}
