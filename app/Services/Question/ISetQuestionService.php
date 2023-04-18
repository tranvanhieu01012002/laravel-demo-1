<?php
namespace App\Services\Question;

use Illuminate\Http\Request;

interface ISetQuestionService {
    public function getAll();
    public function create(Request $request);
}