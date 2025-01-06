<?php

namespace App\Http\Controllers;

use App\Models\Question;

class FavoritesController extends Controller
{
    public function __invoke(Question $question)
    {
        $question->questionFavorites()->toggle(auth()->user()->id);

        if (request()->expectsJson()) {
            return response()->json(null, 204);
        }

        return back();
    }
}
