<?php

use Illuminate\Database\Seeder;
use App\User;
use App\Question;

class VotesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \DB::table('votes')->where('vote_type', 'App\Question')->delete();
        $users = User::all();
        $numberOfUsers = $users->count();
        $votes = [-1, 1];

        foreach (Question::all() as $question) {
            for ($i = 0; $i < rand(1, $numberOfUsers); $i++) {
                $user = $users[$i];
                $user->voteForQuestion($question, $votes[rand(0, 1)]);
            }
        }
    }
}
