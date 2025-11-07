<?php

namespace App\Models\api;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use stdClass;
use function GuzzleHttp\json_encode;

class Treasury extends Model
{
    use HasFactory;

    static function getListTreasuries($userId, $page = 1, $limit = 15, $search = "")
    {
        $offset = ($page - 1) * $limit;

        $data = DB::table('treasuries')
            ->select([
                'treasuries.treasury_no',
                'treasuries.month',
                'treasuries.year',
                'treasuries.created_at',
                DB::raw('(
                SELECT COUNT(id)
                FROM treasury_detail
                WHERE treasury_detail.treasury_no = treasuries.treasury_no
            ) AS total_records'),
                DB::raw('(
                SELECT COUNT(id)
                FROM treasury_members
                WHERE treasury_members.treasury_no = treasuries.treasury_no
            ) AS total_members'),
                DB::raw('(
                SELECT name
                FROM users
                WHERE users.id = treasuries.owner_id
            ) AS owner_name'),
                'treasuries.owner_id',
            ])
            ->where('treasuries.state', 1)
            ->where(function ($query) use ($userId) {
                $query->where('treasuries.owner_id', $userId)
                    ->orWhereExists(function ($subQuery) use ($userId) {
                        $subQuery->select(DB::raw(1))
                            ->from('treasury_members')
                            ->whereColumn('treasury_members.treasury_no', 'treasuries.treasury_no')
                            ->where(function ($nested) use ($userId) {
                                $nested->where('treasury_members.member_id', $userId)
                                    ->where('treasury_members.is_accepted', 1)
                                    ->where('treasury_members.state', 1);
                            });
                    });
            });

        // ✅ Search filter
        if (!empty($search)) {
            $data->where('treasuries.treasury_no', 'like', '%' . $search . '%');
        }

        // ✅ Pagination and order
        $data = $data->orderBy('treasuries.id', 'desc')
            ->limit($limit)
            ->offset($offset)
            ->get();

        return $data;
    }

    static function deleteTreasury($treasuryNo)
    {
        DB::table('treasuries')
            ->where('treasuries.treasury_no', $treasuryNo)
            ->update(['state' => 0, 'updated_at' => date('Y-m-d H:i:s')]);
    }

    static function getTreasuryByNo($treasuryNo, $userId)
    {
        $data = DB::table('treasuries')
            ->select([
                'treasuries.treasury_no',
                'treasuries.month',
                'treasuries.year',
                'treasuries.created_at',
                'treasuries.owner_id',
            ])
            ->where(function ($query) use ($userId) {
                $query->where('treasuries.owner_id', $userId)
                    ->orWhereExists(function ($subQuery) use ($userId) {
                        $subQuery->select(DB::raw(1))
                            ->from('treasury_members')
                            ->whereColumn('treasury_members.treasury_no', 'treasuries.treasury_no')
                            ->where('treasury_members.member_id', $userId);
                    });
            })
            ->where('treasuries.state', 1)
            ->where('treasuries.treasury_no', $treasuryNo)
            ->orderBy('treasuries.id', 'desc')
            ->first();

        return $data;
    }

    static function getTreasuryDetailByNo($treasuryNo, $ownerId, $page = 1, $limit = 15, $search = "")
    {

        $masterTreasury = self::getTreasuryByNo($treasuryNo, $ownerId);

        if (!$masterTreasury) {
            return [];
        }

        $offset = ($page - 1) * $limit;

        $data = DB::table('treasury_detail')
            ->join('treasuries', 'treasuries.treasury_no', '=', 'treasury_detail.treasury_no')
            ->select([
                'treasury_detail.*',
                'treasuries.treasury_no',
                'treasuries.month',
                'treasuries.year',
            ])
            ->where('treasury_detail.state', 1)
            ->where('treasury_detail.treasury_no', $treasuryNo);

        if (!empty($search)) {
            $data->where('treasury_detail.treasury_detail_name', 'like', '%' . $search . '%');
            $data->orWhere('treasury_detail.notes', 'like', '%' . $search . '%');
            $data->orWhere('treasury_detail.income_value', 'like', '%' . $search . '%');
        }

        return $data->orderBy('treasury_detail.id', 'asc')
            ->limit($limit)
            ->offset($offset)
            ->get();
    }

    static function getTreasuryDetail($treasuryDetailNo)
    {

        $treasuryDetail = DB::table('treasury_detail')
            ->join('treasuries', 'treasuries.treasury_no', '=', 'treasury_detail.treasury_no')
            ->select([
                'treasury_detail.*',
                'treasuries.treasury_no',
                'treasuries.owner_id',
            ])
            ->where('treasury_detail.treasury_detail_no', $treasuryDetailNo)
            ->where('treasury_detail.state', 1)
            ->first();

        return $treasuryDetail;
    }

    static function getTreasuryMemberDetail($treasuryNo, $userId)
    {
        $treasuryMember = DB::table('treasury_members')
            ->where('treasury_members.treasury_no', $treasuryNo)
            ->where('treasury_members.member_id', $userId)
            ->where('treasury_members.state', 1)
            ->first();

        return $treasuryMember;
    }


    static function updateTreasuryDetail($treasuryDetailNo, $data)
    {
        DB::beginTransaction();

        try {
            DB::table('treasury_detail')
                ->where('treasury_detail_no', '=', $treasuryDetailNo)
                ->update($data);

            DB::commit();
            return ['error' => false, 'message' => 'Update successful'];
        } catch (\Throwable $th) {
            DB::rollBack();
            return [
                'error' => true,
                'message' => json_encode([
                    'line' => $th->getLine(),
                    'file' => $th->getFile(),
                    'message' => $th->getMessage()
                ])
            ];
        }
    }

    static function createCash(String $treasuryNo, stdClass $data, $userId)
    {

        try {

            $treasury = self::getTreasuryByNo($treasuryNo, $userId);

            // NOT TREASURY OWNER
            if ($treasury && $treasury->owner_id !== $userId) {

                // CHECK IS MEMBER HAVE EDIT ACCESS
                $member = DB::table('treasury_members')
                    ->select('can_edit')
                    ->where('member_id', $userId)
                    ->first();

                if ($member && $member->can_edit != 1) {
                    return [
                        'error'     => true,
                        'message'   => 'Member have no access to edit this treasury',
                        'code'      => 401
                    ];
                }
            }

            DB::beginTransaction();

            $saveWithLastId = DB::table('treasury_detail')
                ->insertGetId([
                    'treasury_no'           => $treasuryNo,
                    'treasury_detail_name'  => $data->detail,
                    'notes'                 => $data->notes,
                    'income_value'          => $data->income,
                    'expense_value'         => $data->expense,
                    'is_debt'               => $data->is_debt,
                    'user_id'               => $userId,
                    'created_at'            => date('Y-m-d H:i:s'),
                    'updated_at'            => date('Y-m-d H:i:s'),
                ]);

            if ($saveWithLastId) {

                $treasuryDetailNo = "TRD" .  str_pad($saveWithLastId, 10, "0", STR_PAD_LEFT);

                $updateCashDetail = DB::table('treasury_detail')
                    ->where(['id' => $saveWithLastId])
                    ->update(['treasury_detail_no' => $treasuryDetailNo]);

                if ($updateCashDetail) {
                    DB::commit();

                    return ['error' => false];
                } else {
                    DB::rollBack();

                    return ['error' => true, 'message' => 'Failed to save created cash (TREASURY_DETAIL_NO_FAILED)', 'code' => 500];
                }
            } else {
                DB::rollBack();

                return ['error' => true, 'message' => 'Failed to save created cash (TREASURY_DETAIL_SAVE_FAILURE)', 'code' => 500];
            }
        } catch (\Throwable $th) {

            DB::rollBack();

            return ['error' => true, 'message' => json_encode(['message' => $th->getMessage(), 'file' => $th->getFile(), 'line' => $th->getLine()]), 'code' => '500F0'];
        }
    }
}
