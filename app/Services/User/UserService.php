<?php

namespace App\Services\User;

use Illuminate\Support\Facades\Auth;
use App\Http\Requests\UserRequest;
use Illuminate\Http\File;
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

        $file = $request->file("image");
        $fileName = time() . "-" . $file->getClientOriginalName();
        $imageFile = Image::make($file)->resize(130, 130)->stream()->__toString();

        $path = 'avatars/' . $fileName;
        Storage::disk('s3')->put($path, $imageFile);
        $fullPath = getS3Url($path);

        $user = Auth::user();
        Storage::disk("s3")->delete(getPathS3FromDB($user->image));
        $user->image = $fullPath;
        $user->save();

        return [
            "data" => [
                "path" => $fullPath
            ],
            "code" => 201,
        ];
    }
}
