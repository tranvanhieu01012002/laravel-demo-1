<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Controller;
use App\Jobs\SendMailRegister;
use App\Models\User;
use Exception;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use PHPOpenSourceSaver\JWTAuth\Exceptions\TokenExpiredException;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|string|email',
            'password' => 'required|string',
        ]);

        $credentials = $request->only('email', 'password');

        // if want to login
        $token = Auth::attempt($credentials);
        // $token = Auth::setTTL(1)->attempt($credentials);
        if (!$token) {
            return response()->json([
                'status' => 'error',
                'message' => 'Unauthorized',
            ], 401);
        }
        $user = Auth::user();
        return response()->json([
            'status' => 'success',
            'token' => $token,
            'type' => 'bearer',
            'expires_in' =>  auth()->factory()->getTTL() * 60,
            'user' => $user
        ]);
    }

    public function register(Request $request)
    {

        $validated = Validator::make($request->all(), [
            'email' => 'required|string|email|max:255|unique:users',
        ]);

        if ($validated->fails()) {
            return response()->json([
                'status' => 'fail',
                'message' => 'oh this email is exist',
            ], 422);
        }

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        SendMailRegister::dispatch($user);

        return response()->json([
            'status' => 'success',
            'message' => 'User created successfully please confirm',
            'id' => $user->id,
        ]);
    }

    public function logout()
    {
        Auth::logout();
        return response()->json([
            'status' => "success",
            'message' => 'Successfully logged out',
        ]);
    }

    public function refresh()
    {
        return response()->json([
            'status' => 'success',
            'user' => Auth::user(),
            'authentication' => [
                'token' => Auth::refresh(),
                'type' => 'bearer',
            ]
        ]);
    }

    public function me()
    {
        return response()->json(Auth::user());
        try {
            return response()->json(Auth::user());
        } catch (TokenExpiredException $e) {
            return response()->json(["message" =>  $e->getMessage()]);
        } catch (Exception $eFull) {
            return response()->json(["message" =>  $eFull->getMessage()]);
        }

        // return auth()->payload();
    }
}
