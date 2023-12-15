<?php

namespace App\Http\Controllers;

use Inertia\Inertia;


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
