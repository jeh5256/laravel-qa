<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Question;

class VoteQuestionController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function __invoke(Question $question, Request $request)
    {
        $vote = (int) $request->input('vote');
        $votesCount = auth()->user()->voteForQuestion($question, $vote);

        if (request()->expectsJson()) {
            return response()->json([
                'message' => ($vote > -1) ? 'Question upvoted' : 'Question downvoted',
                'votesCount' => $votesCount
            ]);
        }

        return back();
    }
}
