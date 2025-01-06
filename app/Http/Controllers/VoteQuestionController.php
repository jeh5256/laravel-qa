<?php

namespace App\Http\Controllers;

use App\Http\Requests\VoteQuestionRequest;
use App\Models\Question;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;

class VoteQuestionController extends Controller
{
    public function __invoke(Question $question, VoteQuestionRequest $request): RedirectResponse|JsonResponse
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
