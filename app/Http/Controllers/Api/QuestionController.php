<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\Question\IQuestionService;
use Illuminate\Http\Request;

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
}
