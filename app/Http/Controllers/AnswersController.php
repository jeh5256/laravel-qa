<?php

namespace App\Http\Controllers;

use App\Http\Requests\AnswerRequest;
use App\Models\Answer;
use App\Models\Question;
use Illuminate\Http\Request;

class AnswersController extends Controller
{

    public function __construct() 
    {
        $this->middleware('auth')->except('index');       
    }

    public function index(Question $question)
    {
        return $question->answers()->with('user')->simplePaginate(5);
    }
    
    /**
     * Store a newly created resource in storage.
     *
     * @param \App\Models\Question
     * @param  \Illuminate\Http\Request  $request
     * @return \App\Http\Requests\AnswerRequest;
     */
    public function store(Question $question, AnswerRequest $request)
    {   
        $answer = $question->answers()->create([
            'body' => $request->body,
            'user_id' => auth()->id()
        ]);

        if ($request->expectsJson()) {
            return response()->json([
                'message' => 'Your answer has been submitted',
                'answer' => $answer->load('user')
            ]);
        }
           
        return back()->with('success', 'Your answer has been submitted');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Answer  $answer
     * @return \Illuminate\Http\Response
     */
    public function edit(Question $question, Answer $answer)
    {
        $this->authorize('update', $answer);

        return view('answers.edit', compact('question', 'answer'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Answer  $answer
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Question $question, Answer $answer)
    {
        $this->authorize('update', $answer);

        $answer->update($request->validate([
            'body' => 'required'
        ]));
        
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
     * @return \Illuminate\Http\Response
     */
    public function destroy(Answer $answer)
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
