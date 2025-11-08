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
                $detail->year = $valDetail->year;
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
                'success'   => true,
                'page'      => $page,
                'length'    => $length,
                'month'     => $listDetail[0]->month,
                'year'      => $listDetail[0]->year,
                'data'      => $data
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

    public function createTreasuryDetail(Request $request)
    {

        $validate = Validator::make($request->all(), [
            'detail' => 'required|max:100',
            'notes' => 'max:200'
        ], [
            'detail.required' => 'Detail cash is required',
            'notes.max' => 'Notes can not be more than 200 characters',
            'detail.max' => 'Detail can not be more than 100 characters'
        ]);

        if ($validate->fails()) {
            return response()->json([
                'success' => false,
                'message' => $validate->errors()->first()
            ], 422);
        }

        try {
            $user = Auth::user();

            $data = [
                'detail'                => $request->detail,
                'notes'                 => $request->notes ?? null,
                'income'                => $request->income ?? 0,
                'expense'               => $request->expense ?? 0,
                'is_debt'               => $request->is_debt ?? 0,
                'user_id'               => $user->id,
                'created_at'            => date('Y-m-d H:i:s'),
                'updated_at'            => date('Y-m-d H:i:s'),
            ];

            $dataObject = json_decode(json_encode($data));

            $createNewCash = Treasury::createCash($request->treasury_no, $dataObject, $user->id);

            if ($createNewCash['error'] && $createNewCash['code'] == '500F0') {
                throw new \Exception($createNewCash['message']);
            }

            if ($createNewCash['error']) {
                return response()->json([
                    'success' => false,
                    'message' => $createNewCash['message']
                ], $createNewCash['code'] ?? 500);
            }

            return response()->json([
                'success' => true,
                'message' => 'Successfully created new cash'
            ], 200);
        } catch (\Throwable $th) {

            Log::error(json_encode([
                'file'      => $th->getFile(),
                'line'      => $th->getLine(),
                'message'   => $th->getMessage()
            ]));

            return response()->json([
                'success' => false,
                'message' => 'Failed to save created cash (GENERAL_ERROR).'
            ], 500);
        }
    }

    public function deleteCash(Request $request)
    {

        try {

            $treasuryDetailNo = $request->treasury_detail_no ?? null;

            if ($treasuryDetailNo === null) {
                return response()->json([
                    'success' => false,
                    'message' => 'Treasury detail is required'
                ], 422);
            }

            $user = Auth::user();
            $userId = $user->id;

            $treasuryDetail = DB::table('treasury_detail AS a')
            ->join('treasuries AS b', 'a.treasury_no', '=', 'b.treasury_no')
                ->select([
                    'b.treasury_no',
                    'b.owner_id'
                ])
                ->where('a.treasury_detail_no', $treasuryDetailNo)->first();

            if (!$treasuryDetail) {
                return response()->json([
                    'success' => false,
                    'message' => 'Cash not found.'
                ], 404);
            }

            if ($treasuryDetail->owner_id !== $userId) {
                $treasuryMember = DB::table('treasury_members')
                    ->select([
                        'member_id'
                    ])
                    ->where('treasury_no', $treasuryDetail->treasury_no)
                    ->where('state', 1)
                    ->where('can_edit', 1)
                    ->where('member_id', $userId)
                    ->first();

                if (!$treasuryMember) {
                    return response()->json([
                        'success' => false,
                        'message' => 'You are not allowed to do this action.'
                    ], 401);
                }
            }

            $delete = Treasury::deleteCash($treasuryDetailNo);

            if (isset($delete['error']) && $delete['error'] == true) {
                return response()->json([
                    'success' => false,
                    'message' => $delete['message']
                ], $delete['code'] ?? 500);
            }

            return response()->json([
                'success' => true,
                'message' => 'Successfully deleted'
            ], 200);

        } catch (\Throwable $th) {

            Log::error(json_encode([
                'file' => $th->getFile(),
                'line' => $th->getLine(),
                'message' => $th->getMessage()
            ]));

            return response()->json([
                'success' => false,
                'error' => 'Something went wrong'
            ], 500);
        }
    }
}
