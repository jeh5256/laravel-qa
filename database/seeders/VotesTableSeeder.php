<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Answer;
use App\Models\Question;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class VotesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('votes')->delete();
        $users = User::all();
        $numberOfUsers = $users->count();
        $votes = [-1, 1];

        foreach (Question::all() as $question) {
            for ($i = 0; $i < rand(1, $numberOfUsers); $i++) {
                $user = $users[$i];
                $user->voteForQuestion($question, $votes[rand(0, 1)]);
            }
        }

        foreach (Answer::all() as $answer) {
            for ($i = 0; $i < rand(1, $numberOfUsers); $i++) {
                $user = $users[$i];
                $user->voteForAnswer($answer, $votes[rand(0, 1)]);
            }
        }
    }
}
