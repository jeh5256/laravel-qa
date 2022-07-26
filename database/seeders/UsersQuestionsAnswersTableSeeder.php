<?php

namespace Database\Seeders;


use App\Models\User;
use App\Models\Answer;
use App\Models\Question;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UsersQuestionsAnswersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('answers')->delete();
        DB::table('questions')->delete();
        DB::table('users')->delete();
        
        User::factory()
            ->has(Question::factory()
            ->count(rand(1, 5)))
            ->create()
            ->each(function($user) {
                $user->questions()->each(function($question) use($user) {
                    Answer::factory([
                        'user_id' => $user->id,
                        'question_id' => $question->id
                    ])
                    ->count(rand(1,5))
                    ->create();
                });
            });
    }
}
