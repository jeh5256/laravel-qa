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
            'questions' => auth()
                ->user()
                ->questions()
                ->take(5)
                ->latest()
                ->get(),
            'answers' => auth()
                ->user()
                ->answers()
                ->take(5)
                ->latest()
                ->get(),
           
        ]);
    }
}
