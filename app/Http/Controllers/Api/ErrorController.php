<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ErrorController extends Controller
{
    public function InvalidateId()
    {
        return response()->json([
            "message" => "Id is invalidate type"
        ], 422);
    }

    public function redirectTokenExpired()
    {
        return response()->json(["message" => "token expired"]);
    }
}
