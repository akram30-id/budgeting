<?php

namespace App\Models\api;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Project extends Model
{
    use HasFactory;

    protected $table = 'projects';

    public static function getAllProjects($userEmail)
    {

        $data = DB::table('projects')
            ->join('project_types', 'projects.project_type_code', '=', 'project_types.project_type_code')
            ->select([
                'projects.project_no',
                'projects.project_name',
                'projects.project_owner',
                'projects.description',
                'projects.created_at',
                'project_types.project_type_name',
                'project_types.project_type_code',
            ])
            ->where(function ($query) use ($userEmail) {
                $query->where('projects.project_owner', $userEmail)
                    ->orWhereExists(function ($subQuery) use ($userEmail) {
                        $subQuery->select(DB::raw(1))
                            ->from('project_members')
                            ->whereColumn('project_members.project_no', 'projects.project_no')
                            ->where('project_members.email', $userEmail);
                    });
            })
            ->where('projects.state', 1)
            ->orderBy('projects.id', 'asc')
            ->get();

        return $data;
    }
}
