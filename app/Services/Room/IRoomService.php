<?php

namespace App\Services\Room;

use Illuminate\Http\Request;

interface IRoomService
{
    public function open(int $id): array;
    public function create(Request $request);
}
