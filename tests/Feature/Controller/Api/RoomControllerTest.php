<?php

namespace Tests\Feature\Controller\Api;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Mockery;
use Tests\TestCase;

class RoomControllerTest extends TestCase
{
    
    // protected IRoomService $roomService;
    // protected IQuestionService $questionService;
    // protected ISetQuestionService $setQuestionService;

    // protected function setUp(): void
    // {
    //     $this->afterApplicationCreated(function(){
    //         $user = Mockery::mock(User::class)->makePartial();
    //         $user->shouldReceive('getAttribute')->with('id')->andReturn(1);
    //         $user->shouldReceive('getAttribute')->with('name')->andReturn('hieu');
    //         $this->actingAs($user);
    //     });
    //     parent::setUp();
    // }

    public function test_that_true_is_true(): void
    {
        $this->assertTrue(true);
    }
}
