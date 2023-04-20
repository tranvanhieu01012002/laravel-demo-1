<?php

namespace App\Services\Question;

use App\Models\SetQuestion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SetQuestionService implements ISetQuestionService
{
    public function getAll()
    {
        $user = Auth::user();
        return SetQuestion::where("user_id", $user->id)->withCount("questions")->get();
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
        $user = Auth::user();
        $id = intval($id);
        try {
            $setQuestion = SetQuestion::where("id",$id)
                ->where('user_id', $user->id)
                ->with('questions.answers')
                ->first();
            return [
                'status'=> true,
                'data' => $setQuestion->questions,  
            ];
        } catch (\Throwable $th) {
            return [
                'status' => false,
                'data' =>  $th->getMessage()
            ];
        }
    }
}
