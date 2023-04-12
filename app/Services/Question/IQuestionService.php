<?php

namespace App\Services\Question;

interface IQuestionService {
    public function getQuestionWithAnswers(int $roomId);

    public function nextQuestion(int $roomId);

    public function pushAnswer(int $roomId, int $score);
}
