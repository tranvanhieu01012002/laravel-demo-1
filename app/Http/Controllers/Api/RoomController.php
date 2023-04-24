<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\Room\IRoomService;
use Illuminate\Http\Request;

class RoomController extends Controller
{
    private IRoomService $roomService;

    public function __construct(IRoomService $roomService)
    {
        $this->roomService = $roomService;
    }

    public function open(int $id)
    {
        $response = $this->roomService->open($id);
        if ($response["status"]) {
            return response()->json($response, 200);
        } else return response()->json($response, 404);
    }

    public function create(Request $request)
    {
        return response()->json($this->roomService->create($request));
    }
}
