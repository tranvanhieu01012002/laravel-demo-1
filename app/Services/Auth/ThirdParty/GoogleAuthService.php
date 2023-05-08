<?php

namespace App\Services\Auth\ThirdParty;

use App\Models\User;
use Exception;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;

class GoogleAuthService implements IAuthService
{
    public function login(string $token)
    {
        try {
            $user = Socialite::driver('google')->stateless()->userFromToken($token);
            if (!!$user) {
                $userDB = User::where('third_party', $user->user['id'])->first();
                if (!!$userDB) {
                    return [
                        "status" => true,
                        "token" => Auth::login($userDB)
                    ];
                } else {

                    $newUser = new User();
                    $newUser->image = $user->user['picture'];
                    $newUser->email = $user->user['email'];
                    $newUser->third_party = $user->user['id'];
                    $newUser->name = $user->user['name'];
                    $newUser->save();

                    return [
                        "status" => true,
                        "token" => Auth::login($newUser)
                    ];
                }
            }
            return [
                "status" => false,
                "token" => "oh some thing wrong"
            ];
        } catch (Exception $e) {
            return [
                "status" => false,
                "token" => $e->getMessage()
            ];
        }
    }
}
