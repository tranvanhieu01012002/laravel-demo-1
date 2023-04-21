<?php

namespace App\Services\Question;

use App\Constants\Room;
use App\Events\RoomEvent;
use App\Events\ShowResult;
use App\Models\Answer;
use App\Models\Question;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
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
        // $questionIds = array_column($questions, 'id');
        $questionDBs = Question::where("set_question_id", 1)->with("answers")->get();

        $index = $this->makeForLoopToUpdateAndReturnRemainQuestion($questions, $questionDBs);

        $remainQuestions = array_slice($questions, $index);

        foreach ($remainQuestions as $remainQuestion) {
            Log::debug("chay vo day nao");
            $this->addNewQuestion($remainQuestion, 1);
        }

        return [
            "status" => true,
            "data" => "success"
        ];
    }

    protected function makeForLoopToUpdateAndReturnRemainQuestion(array $questions, Collection $questionDBs)
    {
        $index = 0;
        Log::debug(sizeof($questions));
        Log::debug(sizeof($questionDBs));
        foreach ($questionDBs as $questionDB) {
            if ($index < sizeof($questions)) {
                $questionDB->content = $questions[$index]["content"];
                $indexAnswer = 0;
                foreach ($questionDB->answers as $answer) {
                    $answerRequest = $questions[$index]["answers"][$indexAnswer];
                    $answer->content = $answerRequest["content"];
                    $answer->is_correct = $answerRequest["is_correct"];
                    $indexAnswer++;
                }
                $questionDB->push();
            } else {
                Log::debug("allow delete");
                $questionDB->delete();
            }
            $index++;
        };
        return $index;
    }

    protected function addNewQuestion(array $question, int $setQuestionId)
    {
        $questionDB = new Question([
            "content" => $question["content"],
            "image" => "https://hinhanhdephd.com/wp-content/uploads/2015/12/hinh-anh-dep-girl-xinh-hinh-nen-dep-gai-xinh.jpg",
            "set_question_id" => $setQuestionId
        ]);
        $questionDB->save();
        $questionDB->answers()
            ->createMany($this->createListAnswer($question["answers"]));
    }

    protected function createListAnswer(array $answers)
    {
        $answersDB = [];
        foreach ($answers as $answer) {
            array_push($answersDB,  [
                "content" => $answer["content"],
                "is_correct" => $answer["is_correct"]
            ]);
        }
        return $answersDB;
    }
}
