<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ErrorController extends Controller
{
    public function redirectTokenExpired()
    {
        return response()->json(["message" => "token expired"]);
    }
}
