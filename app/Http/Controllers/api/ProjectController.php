<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use App\Models\api\Project;
use Illuminate\Support\Facades\Log;

class ProjectController extends Controller
{
    public function getListProject()
    {
        try {

            $userAccount = Auth::user();

            $getProjects = Project::getAllProjects($userAccount->email);

            if (!$getProjects) {
                return response()->json([
                    'success' => false,
                    'message' => 'No project found'
                ], 404);
            }

            return response()->json([
                'success' => true,
                'data' => $getProjects
            ], 200);
        } catch (\Throwable $th) {

            Log::error(json_encode([
                'message'   => $th->getMessage(),
                'line'      => $th->getLine(),
                'file'      => $th->getFile()
            ]));

            return response()->json([
                'success' => false,
                'message' => 'Something went wrong.'
            ], 500);
        }
    }
}
