<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\SetQuestion;
use App\Services\Question\IQuestionService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class QuestionController extends Controller
{
    private IQuestionService $questionService;

    public function __construct(IQuestionService $questionService)
    {
        $this->questionService = $questionService;
    }

    public function getQuestions(int $roomId)
    {
        $response = $this->questionService
            ->getQuestionWithAnswers($roomId);

        return response()
            ->json([
                "message" => "success",
                "data" => $response
            ]);
    }

    public function nextQuestion(int $roomId)
    {
        $response = $this->questionService->nextQuestion($roomId);
        return response()
            ->json([
                "message" => "success",
                "data" => $response
            ]);
    }

    public function pushAnswer(int $roomId, Request $request)
    {
        if ($request->input('is_correct')) {
            $response = $this->questionService
                ->pushAnswer(
                    $roomId,
                    $request->input('score'),
                );
        } else {
            $response = "oh! Somethings was wrong!";
        }
        return response()
            ->json([
                "data" => $response
            ]);
    }

    public function viewResult(int $roomId)
    {
        $response = $this->questionService->viewResult($roomId);
        return response()->json(["data" => $response]);
    }

    public function update(Request $request)
    {
        $user = $request->user();

        if ($user->cannot("update", SetQuestion::find($request->input("set_question_id")))) {
            return response()->json(["data" => "You can not update"], 403);
        }
        $response = $this->questionService->update($request);
        return response()->json($response);
    }
}
