<?php

namespace App\Services\Question;

use App\Models\Question;

class QuestionService implements IQuestionService
{
    public function getQuestionWithAnswers(int $roomId)
    {
        return Question::take(2)->with("answers")->get();
    }
}
