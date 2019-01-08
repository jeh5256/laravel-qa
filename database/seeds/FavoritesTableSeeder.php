<?php

use Illuminate\Database\Seeder;
use App\User;
use App\Question;

class FavoritesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('question_favorites')->delete();

        $users = User::pluck('id')->all();
        $numUsers = count($users);

        foreach (Question::all() as $question) {
            for ($i = 0; $i < rand(1, $numUsers); $i++) {
                $user = $users[$i];
                $question->questionFavorites()->attach($user);
            }
        }
    }
}
