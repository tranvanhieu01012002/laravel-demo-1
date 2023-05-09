<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;

class VerifyEmailController extends Controller
{
    public function __invoke(string $userId)
    {
        $unverifiedUser = User::where("id", $userId)->whereNull('email_verified_at')->first();
        if (!$unverifiedUser == null) {
            $unverifiedUser->markEmailAsVerified();
        }
        return "ok reload your page";
    }

    public function hasVerifyEmail(string $userId)
    {
        $verifiedUser = User::where("id", $userId)->whereNotNull('email_verified_at')->first();
        if ($verifiedUser == null) {
            return response()->json(["status" => false]);
        } else {
            return response()->json(["status" => true]);
        }
    }
}
