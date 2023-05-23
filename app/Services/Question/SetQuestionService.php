<?php

namespace App\Services\Question;

use App\Constants\SetQuestionStatus;
use App\Http\Resources\PublishSetQuestionResource;
use App\Http\Resources\SetQuestionResource;
use App\Models\Favorite;
use App\Models\SetQuestion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SetQuestionService implements ISetQuestionService
{
    public function getAll()
    {
        $user = Auth::user();
        $setQuestions = SetQuestion::where("user_id", $user->id)
            ->withCount("questions")
            ->with(["favorite" =>
            function ($query) use ($user) {
                $query->where("user_id", $user->id);
            }]);
        return SetQuestionResource::collection($setQuestions->get());
    }

    public function create(Request $request)
    {
        $user = Auth::user();
        $setQuestion = new SetQuestion(
            [
                "user_id" => $user->id,
                "name" => $request->input("name")
            ]
        );
        $setQuestion->save();
        return $setQuestion;
    }

    public function delete(int $id)
    {
        $setQuestion = SetQuestion::find($id);
        return $setQuestion->delete();
    }

    public function getQuestions(int $id): array
    {
        $id = intval($id);
        try {
            $setQuestion = SetQuestion::where("id", $id)
                ->with('questions.answers')
                ->first();
            return [
                'status' => true,
                'data' => $setQuestion->questions,
            ];
        } catch (\Throwable $th) {
            return [
                'status' => false,
                'data' =>  $th->getMessage()
            ];
        }
    }

    public function update(int $id, Request $request)
    {
        $setQuestion = SetQuestion::find($id);
        $userId = Auth::id();

        if ($request->has("favorite")) {
            $this->updateWithFavorite($id, $request, $userId);
        }

        if ($request->has("name")) {
            $setQuestion->name = $request->input("name");
            $setQuestion->save();
        }

        if ($request->has("status")) {
            $setQuestion->status = $request->get("status") === SetQuestionStatus::PRIVATE ? SetQuestionStatus::PUBLISH : SetQuestionStatus::PRIVATE;
            $setQuestion->save();
        }

        return $this->getAll();
    }

    public function updateWithFavorite(int $id, Request $request, string $userId)
    {
        if ($request->get("favorite")) {
            $favoriteDB = Favorite::where("set_question_id", $id)->where("user_id", $userId);
            $favoriteDB->delete();
        } else {
            $favorite = new Favorite(["user_id" => $userId, "set_question_id" => $id]);
            $favorite->save();
        }
    }

    public function getPublishQuestion()
    {
        $setPublishQuestion = SetQuestion::whereNot("user_id", Auth::id())
            ->where('status', SetQuestionStatus::PUBLISH)
            ->with(["username"])
            ->withCount("questions")
            ->get();
        return PublishSetQuestionResource::collection($setPublishQuestion);
    }

    public function fork(Request $request): string
    {
        $setQuestion = SetQuestion::find($request->input("id"));
        $setQuestion->replicateRow();
        return "success";
    }
}
