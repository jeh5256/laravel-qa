<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Question;

class FavoritesController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function __invoke(Question $question)
    {
        $question->questionFavorites()->toggle(auth()->user()->id);

        if (request()->expectsJson()) {
            return response()->json(null, 204);
        }

        return back();
    }
}
