<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Database\Seeders\VotesTableSeeder;
use Database\Seeders\FavoritesTableSeeder;
use Database\Seeders\UsersQuestionsAnswersTableSeeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call([
            FavoritesTableSeeder::class,
            UsersQuestionsAnswersTableSeeder::class,
            VotesTableSeeder::class
        ]);
    }
}
