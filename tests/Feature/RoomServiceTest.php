<?php

namespace Tests\Feature;

use App\Models\User;
use App\Services\Room\IRoomService;
use App\Services\Room\RoomService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Mockery;
use Tests\TestCase;

class RoomServiceTest extends TestCase
{
    // protected IRoomService $roomService;
    // protected IQuestionService $questionService;
    // protected ISetQuestionService $setQuestionService;

    protected function setUp(): void
    {
        $this->afterApplicationCreated(function(){
            $user = Mockery::mock(User::class)->makePartial();
            $user->shouldReceive('getAttribute')->with('id')->andReturn(1);
            $user->shouldReceive('getAttribute')->with('name')->andReturn('hieu');
            $this->actingAs($user);
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
        $this->assertArrayHasKey("room",$result);
        $this->assertArrayHasKey("user_id",$result);
        $this->assertEquals("1",$result["user_id"]);
    }
}
