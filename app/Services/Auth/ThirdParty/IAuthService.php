<?php

namespace App\Services\Auth\ThirdParty;

interface IAuthService
{
    public function login(string $token);
}
