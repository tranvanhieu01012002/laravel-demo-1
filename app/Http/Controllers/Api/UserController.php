<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function getAll(){
        return User::paginate(20);
    }

    public function showUser(string $id){
        return User::find($id);
    }
}
