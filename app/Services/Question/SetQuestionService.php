<?php

namespace App\Services\Question;

use App\Models\SetQuestion;
use Illuminate\Support\Facades\Auth;

class SetQuestionService implements ISetQuestionService
{
    public function getAll()
    {
        $user = Auth::user();
        return SetQuestion::where("user_id",$user->id)->withCount("questions")->get();
    }
}
