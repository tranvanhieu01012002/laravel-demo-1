<?php

namespace Tests\Feature;

use App\Models\User;
use App\Services\Room\IRoomService;
use App\Services\Room\RoomService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Redis;
use Mockery;
use Tests\TestCase;

class RoomServiceTest extends TestCase
{
    // protected IRoomService $roomService;
    // protected IQuestionService $questionService;
    // protected ISetQuestionService $setQuestionService;
    protected int $roomId;

    protected function setUp(): void
    {
        $this->afterApplicationCreated(function () {
            $user = Mockery::mock(User::class)->makePartial();
            $user->shouldReceive('getAttribute')->with('id')->andReturn(1);
            $user->shouldReceive('getAttribute')->with('name')->andReturn('hieu');
            $this->actingAs($user);
            $roomServiceMock = Mockery::mock(RoomService::class)->makePartial();
            $result = $roomServiceMock->create();
            $this->roomId = $result["room"];
        });
        parent::setUp();
    }

    /**
     * A basic feature test example.
     */
    public function test_create_room(): void
    {
        $roomServiceMock = Mockery::mock(RoomService::class)->makePartial();
        $result = $roomServiceMock->create();
        $this->assertArrayHasKey("room", $result);
        $this->assertArrayHasKey("user_id", $result);
        $this->assertEquals("1", $result["user_id"]);

        $this->beforeApplicationDestroyed(function () use ($result) {
            Redis::del($result["room"]);
        });
    }

    public function test_open_room_fail(): void
    {
        $roomServiceMock = Mockery::mock(RoomService::class)->makePartial();
        $result = $roomServiceMock->open($this->roomId);
        $this->assertArrayHasKey("status", $result);
        $this->assertArrayHasKey("data", $result);
        $this->assertEquals(true, $result["status"]);
        $this->assertEquals($this->roomId, $result["data"]);

        $this->beforeApplicationDestroyed(function () {
            Redis::del($this->roomId);
        });
    }


    public function test_open_room_success(): void
    {
        $roomServiceMock = Mockery::mock(RoomService::class)->makePartial();
        $result = $roomServiceMock->open($this->roomId);
        $this->assertArrayHasKey("status", $result);
        $this->assertArrayHasKey("data", $result);
        $this->assertArrayHasKey("user_id", $result);
        $this->assertArrayHasKey("is_owner", $result);
        $this->assertEquals(true, $result["status"]);
        $this->assertEquals($this->roomId, $result["data"]);
        $this->assertEquals(1, $result["user_id"]);
        $this->assertEquals(false, $result["is_owner"]);

        $this->beforeApplicationDestroyed(function () {
            Redis::del($this->roomId);
        });
    }
}
