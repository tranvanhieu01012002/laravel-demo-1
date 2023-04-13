<?php

namespace App\Services\Question;

use App\Constants\Room;
use App\Events\RoomEvent;
use App\Events\ShowResult;
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
        $userDB = Auth::user();

        $key = self::handleKey($roomId, $userDB->id);
        $user = $redis->get($key);

        if ($user) {
            $user = json_decode($user);
            $user->score += $score;
        } else {
            $user = [
                "user_id" => $userDB->id,
                "user_name" => $userDB->name,
                "score" => $score
            ];
        }

        return $redis->set($key, json_encode($user), "EX", Room::EXPIRED_TIME * 60);
    }

    public static function handleKey(int $roomId, string $userId)
    {
        return Room::PREFIX. $roomId . "_" . $userId;
    }

    public function viewResult(int $roomId): array
    {
        $redis = Redis::connection();
        $currentKeys = $redis->keys(Room::PREFIX . $roomId . "*");
        $users = [];
        foreach ($currentKeys as $key) {
            array_push($users, json_decode($redis->get($key)));
        }

        usort($users, function ($first, $second) {
            return $first->score < $second->score;
        });

        broadcast(new ShowResult($users, $roomId))->toOthers();
        
        return $users;
    }
}
