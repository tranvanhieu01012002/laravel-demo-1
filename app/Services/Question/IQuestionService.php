<?php

namespace App\Services\Question;

interface IQuestionService {
    public function getQuestionWithAnswers(int $roomId);
}
