<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\SetQuestion;
use App\Services\Question\ISetQuestionService;
use Illuminate\Http\Request;

class SetQuestionController extends Controller
{
    private ISetQuestionService $setQuestion;

    public function __construct(ISetQuestionService $setQuestion)
    {
        $this->setQuestion = $setQuestion;
    }

    public function getAll()
    {
        $response = $this->setQuestion->getAll();
        return response()->json($response, 200);
    }

    public function create(Request $request)
    {
        $response = $this->setQuestion->create($request);
        return response()->json($response, 201);
    }

    public function delete(int $id)
    {
        $response = $this->setQuestion->delete($id);
        return response()->json($response, 200);
    }

    public function getQuestions(int $id)
    {
        $response = $this->setQuestion->getQuestions($id);
        return response()->json($response, 200);
    }

    public function update(int $id, Request $request)
    {
        $user = $request->user();
        if ($user->cannot("update", SetQuestion::find($id))) {
            return response()->json(["data"=> "You can not update"], 401);
        }
        $response = $this->setQuestion->update($id, $request);
        return response()->json($response, 200);
    }
}
