<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\UserRequest;
use App\Models\User;
use App\Services\User\IUserService;

class UserController extends Controller
{
    protected IUserService $userService;

    public function __construct(IUserService $userService)
    {
        $this->userService = $userService;
    }
    public function getAll()
    {
        return User::paginate(20);
    }

    public function showUser(string $id)
    {
        return User::find($id);
    }

    public function update(UserRequest $request)
    {
        if ($request->hasFile("image")) {
            $response = $this->userService->updateImage($request);
        } else {
            $response =  $this->userService->updateInfo($request);
        }
        return response()->json($response["data"], $response["code"]);
    }
}
