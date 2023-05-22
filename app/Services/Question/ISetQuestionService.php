<?php

namespace App\Services\Question;

use Illuminate\Http\Request;

interface ISetQuestionService
{
    public function getAll();
    public function create(Request $request);
    public function delete(int $id);
    public function getQuestions(int $id): array;
    public function update(int $id, Request $request);
    public function getPublishQuestion();
}
