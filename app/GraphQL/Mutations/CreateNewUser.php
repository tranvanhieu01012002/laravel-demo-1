<?php

namespace App\GraphQL\Mutations;

use App\Models\User;
use Illuminate\Support\Facades\Hash;

final class CreateNewUser
{
    /**
     * @param  null  $_
     * @param  array{}  $args
     */
    public function __invoke($_, array $args)
    {
        $user = new User();
        $user->name = $args['name'];
        $user->email= $args['email'];
        $user->password = Hash::make($args['password']);
        $user->save();
        return $user;
    }

}
