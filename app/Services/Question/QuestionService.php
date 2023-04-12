<?php

namespace App\Services\Question;

use App\Events\RoomEvent;
use App\Models\Question;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redis;

class QuestionService implements IQuestionService
{
    public function getQuestionWithAnswers(int $roomId)
    {
        return Question::take(2)->with("answers")->get();
    }

    public function nextQuestion(int $roomId)
    {
        return broadcast(new RoomEvent($roomId))->toOthers();
    }

    public function pushAnswer(int $roomId, int $score)
    {
        $redis = Redis::connection();
        $userId = Auth::id();

        $key = $this->handleKey($roomId, $userId);
        $user = $redis->get($key);

        if ($user) {
            $user = json_decode($user);
            $user->score += $score;
        } else {
            $user = [
                "user_id" => $userId,
                "score" => $score
            ];
        }

        return $redis->set($key, json_encode($user));
    }

    public function handleKey(int $roomId, string $userId)
    {
        $prefixKey = "room_";
        return $prefixKey . $roomId . "_" . $userId;
    }
}
