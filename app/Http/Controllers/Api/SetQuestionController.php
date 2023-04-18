<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\Question\ISetQuestionService;
use Illuminate\Http\Request;

class SetQuestionController extends Controller
{
    private ISetQuestionService $setQuestion;

    public function __construct(ISetQuestionService $setQuestion)
    {
        $this->setQuestion = $setQuestion;
    }

    public function getAll(){
        $response = $this->setQuestion->getAll();
        return response()->json($response, 200);
    }

    public function create(Request $request){
        $response = $this->setQuestion->create($request);
        return response()->json($response, 201);
    }
}
