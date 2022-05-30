<?php

namespace App\Http\Controllers;

use App\Models\Answer;
use Inertia\Inertia;
use App\Models\Question;


class DashboarController extends Controller
{

    public function __invoke()
    {
        return Inertia::render('Dashboard', [
            'questions' => Question::where('user_id', auth()->user()->id)
                ->paginate(5),
            'answers' => Answer::where('user_id', auth()->user()->id)
                ->paginate(5)
        ]);
    }
}
