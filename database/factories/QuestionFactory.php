<?php

use Faker\Generator as Faker;

$factory->define(App\Question::class, function (Faker $faker) {
    return [
        'title' => rtrim($faker->sentence(rand(0, 5)), "."),
        'body' => $faker->paragraphs(rand(3, 7), true),
        'views' => rand(0, 10),
        //'votes_count' => rand(-3, 10),
    ];
});
