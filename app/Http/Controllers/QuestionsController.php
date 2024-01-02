<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use Inertia\Response;
use App\Models\Question;
use Illuminate\Routing\Redirector;
use Illuminate\Http\RedirectResponse;
use App\Http\Requests\Questions\CreateQuestionRequest;
use App\Http\Requests\Questions\UpdateQuestionRequest;

class QuestionsController extends Controller
{

    public function __construct() 
    {
        $this->middleware('auth', ['except' => ['index', 'show']]);       
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Inertia\Response
     */
    public function index(): Response
    {
        return Inertia::render('Questions/QuestionsIndex', [
            'questions' => Question::with('user')
                ->latest()
                ->paginate(5)
              
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Inertia Response
     */
    public function create(): Response
    {   
        return Inertia::render('Questions/QuestionsCreate', [
            'can' => [
                'addQuestion' => auth()->id() ?? false
            ]
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\Questions\CreateQuestionRequest  $request
     * @return \Illuminate\Routing\Redirector|\Illuminate\Http\RedirectResponse
     */
    public function store(CreateQuestionRequest $request): Redirector|RedirectResponse
    {
        $request->user()->questions()->create($request->only('title', 'body'));

        return redirect('/questions')->with('success', 'Your question has created');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Moodels\Question  $question
     * @return \Inertia\Response
     */
    public function show(Question $question): Response
    {
        $question->increment('views');

        $question->load(['answers' => function($query) {
            return $query->latest();
        }, 'answers.user', 'user']);

        return Inertia::render('Questions/QuestionsShow', [
            'question' => $question,
            'answers' => $question->answers,
            'can' => [
                'markAsBestAnswer' => $question->user_id === auth()?->id(),
                'addAnswer' => auth()->id() ?? false
            ]
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Question  $question
     * @return \Inertia\Response
     */
    public function edit(Question $question): Response
    {
        return Inertia::render('Questions/QuestionsEdit', [
            'question' => $question,
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\Questions\UpdateQuestionRequest $request
     * @param  \App\Models\Question  $question
     * @return \Illuminate\Http\Response|\Illuminate\Routing\Redirector|\Illuminate\Http\RedirectResponse
     */
    public function update(UpdateQuestionRequest $request, Question $question): Redirector|Response|RedirectResponse
    {
        $this->authorize('update', $question);

        $question->update($request->only('body', 'title'));

        if ($request->expectsJson()) {
            return response()->json([
                'message' => 'Your question has been updated',
                'body_html' => $question->body_html
            ]);
        }

        return redirect('/questions')->with('success', 'Your question has been updated');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Question  $question
     * @return \Illuminate\Http\Response|\Illuminate\Routing\Redirector|\Illuminate\Http\RedirectResponse
     */
    public function destroy(Question $question): Redirector|Response|RedirectResponse
    {
        $this->authorize('delete', $question);

        $question->delete();

        if (request()->expectsJson()) {
            return response()->json([
                'message' => 'Your question has been deleted'
            ]);
        }
        return redirect('/questions')->with('success', 'Your question has been deleted');
    }
}
