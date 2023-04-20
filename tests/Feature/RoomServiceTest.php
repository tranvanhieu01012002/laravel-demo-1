<?php

namespace Tests\Feature;

use App\Models\User;
use App\Services\Question\QuestionService;
use App\Services\Room\IRoomService;
use App\Services\Room\RoomService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Auth;
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

        Redis::del($result["room"]);
    }

    public function test_open_room_fail(): void
    {
        $roomServiceMock = Mockery::mock(RoomService::class)->makePartial();
        $result = $roomServiceMock->open($this->roomId);
        $this->assertArrayHasKey("status", $result);
        $this->assertArrayHasKey("data", $result);
        $this->assertEquals(true, $result["status"]);
        $this->assertEquals($this->roomId, $result["data"]);

        Redis::del($this->roomId);
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

        Redis::del($this->roomId);
    }

    public function test_add_to_redis(): void
    {
        $roomServiceMock = Mockery::mock(RoomService::class)->makePartial();
        $redisMock = Mockery::mock(Redis::connection());
        $user = Auth::user();
        $roomServiceMock->addToRedis($redisMock, $user, $this->roomId);
        $key = QuestionService::handleKey($this->roomId, $user->id);
        $instance = Redis::get($key);
        $this->assertNotNull($instance);
        Redis::del($key);
    }
}
