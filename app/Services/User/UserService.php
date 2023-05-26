<?php

namespace App\Services\User;

use Illuminate\Support\Facades\Auth;
use App\Http\Requests\UserRequest;
use Intervention\Image\ImageManagerStatic as Image;
use Illuminate\Support\Facades\Storage;

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

    public function updateImage(UserRequest $request)
    {
        $img = Image::make($request->file("image"))->resize(300, 200);
        $img->save(public_path("example1.png"));
        return [
            "data" => "success",
            "code" => 201
        ];
    }
}
