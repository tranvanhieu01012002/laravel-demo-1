<?php

namespace App\Services\User;

use Illuminate\Support\Facades\Auth;
use App\Http\Requests\UserRequest;
use Illuminate\Support\Facades\Log;

class UserService implements IUserService
{
    public function updateInfo(UserRequest $request)
    {
        $user = Auth::user();
        $user->name = $request->get("name");
        $user->save();
        return [
            "data" => $user,
            "code" => 200
        ];
    }
}
