<?php

namespace App\Http\Controllers;

use App\Models\Answer;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;

class AcceptAnswerController extends Controller
{
    public function __invoke(Answer $answer): JsonResponse|RedirectResponse
    {
        $this->authorize('accept', $answer);
        
        $answer->question->acceptBestAnswer($answer);

        if (request()->expectsJson()) {
            return response()->json([
                'message' => 'Marked as best answer'
            ]);
        }

        return back();
    }
}
