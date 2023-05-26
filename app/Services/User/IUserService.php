<?php

namespace App\Services\User;

use App\Http\Requests\UserRequest;

interface IUserService
{
    public function updateInfo(UserRequest $request);
    public function updateImage(UserRequest $request);
}
