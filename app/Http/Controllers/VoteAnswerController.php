<?php

namespace App\Http\Controllers;

use App\Http\Requests\Answers\VoteAnswerRequest;
use App\Models\Answer;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;

class VoteAnswerController extends Controller
{
    public function __invoke(Answer $answer, VoteAnswerRequest $request): RedirectResponse|JsonResponse
    {
        $vote = (int) $request->input('vote');
        $votesCount = auth()->user()->voteForAnswer($answer, $vote);

        if (request()->expectsJson()) {
            return response()->json([
                'message' => ($vote > -1) ? 'Answer upvoted' : 'Answer downvoted',
                'votesCount' => $votesCount
            ]);
        }
        return back();
    }
}
