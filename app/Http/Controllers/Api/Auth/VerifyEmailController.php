<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;

class VerifyEmailController extends Controller
{
    public function __invoke(string $userId)
    {
        $user = User::where("id",$userId)->whereNull('email_verified_at')->first();
        if (!$user == null) {
            $user->markEmailAsVerified();
        }
        return "ok reload your page";
    }
}
