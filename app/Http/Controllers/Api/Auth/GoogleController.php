<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Controller;
use App\Services\Auth\ThirdParty\IAuthService;
use Illuminate\Http\Request;

class GoogleController extends Controller
{
    private IAuthService $authService;

    public function __construct(IAuthService $authService)
    {
        $this->authService = $authService;
    }

    public function __invoke(Request $request)
    {
        $token = $request->get("token");
        $responseToken = $this->authService->login($token);

        if ($responseToken["status"]) {
            return response()->json([
                'status' => 'success',
                'token' => $responseToken['token'],
                'type' => 'bearer',
                'expires_in' =>  auth()->factory()->getTTL() * 60
            ]);
        } else {
            return response()->json([
                'status' => "error",
                'message' => $responseToken['token']
            ]);
        }
    }
}
