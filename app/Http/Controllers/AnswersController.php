<?php

namespace App\Http\Controllers;

use App\Http\Requests\AnswerRequest;
use App\Http\Requests\UpdateAnswerRequest;
use App\Models\Answer;
use App\Models\Question;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;

class AnswersController extends Controller
{    
    /**
     * Store a newly created resource in storage.
     *
     * @param \App\Models\Question
     * @param  \Illuminate\Http\Request  $request
     * @return \App\Http\Requests\RedirectResponse||\Illuminate\Http\JsonResponse;
     */
    public function store(Question $question, AnswerRequest $request): RedirectResponse|JsonResponse
    {   
        $answer = $question->answers()->create([
            'body' => $request->body,
            'user_id' => auth()->id()
        ]);

        if ($request->expectsJson()) {
            return response()->json([
                'message' => 'Your answer has been submitted',
                'answer' => $answer->load('user')
            ], 201);
        }
           
        return back()->with('success', 'Your answer has been submitted');
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateAnswerRequest  $request
     * @param  \App\Models\Answer  $answer
     * @param  \App\Models\Question $question
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Http\JsonResponse
     */
    public function update(
        UpdateAnswerRequest $request, 
        Question $question, 
        Answer $answer
    ): RedirectResponse|JsonResponse
    {
        $this->authorize('update', $answer);

        $answer->update(
            $request->validated('body')
        );
        
        if ($request->expectsJson()) {
            return response()->json([
                'message' => 'Your answer has been updated',
                'body_html' => $answer->body_html
            ]);
        }
        
        return redirect()
            ->route('questions.show', $question->slug)
            ->with('success', 'Your answer has been updated');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Answer  $answer
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Http\JsonResponse
     */
    public function destroy(Answer $answer): RedirectResponse|JsonResponse
    {
        $this->authorize('delete', $answer);
        $answer->delete();

        if (request()->expectsJson()) {
            return response()->json([
                'message' => 'Your answer was deleted'
            ]);
        }

        return back()->with('success', 'Your answer was deleted');
    }
}
