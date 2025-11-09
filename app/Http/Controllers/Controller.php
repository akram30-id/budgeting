<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    function __construct()
    {
        $request = new Request();

        Log::debug(json_encode([
            'request' => $request->all()
        ]));
    }

    public function logError(\Throwable $th)
    {

        Log::error(json_encode([
            'file' => $th->getFile() ?? null,
            'line' => $th->getLine() ?? null,
            'message' => $th->getMessage() ?? ''
        ]));

    }
}
