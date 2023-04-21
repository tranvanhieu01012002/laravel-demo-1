<?php

namespace App\Services\Question;

use App\Constants\Room;
use App\Events\RoomEvent;
use App\Events\ShowResult;
use App\Models\Question;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Redis;

class QuestionService implements IQuestionService
{

    public function getQuestionWithAnswers(int $roomId)
    {
        return Question::take(6)->with("answers")->get();
    }

    public function nextQuestion(int $roomId)
    {
        return broadcast(new RoomEvent($roomId));
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
        return Room::PREFIX . $roomId . "_" . $userId;
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

        broadcast(new ShowResult($users, $roomId));

        return $users;
    }

    public function update(Request $request)
    {
        $questions = $request->input("questions");
        $questionIds = array_column($questions,'id');
        $index = 0;
        $questionDBs = Question::whereIn("id",array_values($questionIds))->with("answers")->get();

        foreach ( $questionDBs as $questionDB) {
            $questionDB->content = $questions[$index]["content"];
            $indexAnswer = 0;
            foreach ($questionDB->answers as $answer) {
                $answerRequest =$questions[$index]["answers"][$indexAnswer];
                $answer->content = $answerRequest["content"];
                $answer->is_correct = $answerRequest["is_correct"];
                $indexAnswer++;
            }
            $index++;
            $questionDB->push();
        }
        return [
            "status" => true,
            "data" =>  $questionDBs
        ];
    }
}
