<?php

namespace App\Services\Question;

use Illuminate\Http\Request;

interface IQuestionService {
    public function getQuestionWithAnswers(int $roomId);

    public function nextQuestion(int $roomId);

    public function pushAnswer(int $roomId, int $score);

    public function viewResult(int $roomId): array;

    public function update(Request $request);
}
