<?php

namespace App\Models\api;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

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

    static function getTreasuryByNo($treasuryNo, $ownerId)
    {
        $data = DB::table('treasuries')
            ->select([
                'treasuries.treasury_no',
                'treasuries.month',
                'treasuries.year',
                'treasuries.created_at',
            ])
            ->where('treasuries.state', 1)
            ->where('treasuries.owner_id', $ownerId)
            ->where('treasuries.treasury_no', $treasuryNo)
            ->orderBy('treasuries.id', 'desc')
            ->first();

        return $data;
    }
}
