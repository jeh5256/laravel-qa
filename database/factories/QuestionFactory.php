<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Question>
 */
class QuestionFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'title' => $this->faker->words(3, true),
            'slug' => $this->faker->unique()->slug(),
            'body' => $this->faker->paragraph(),
            'views' => $this->faker->randomDigit(),
            'answers_count' => $this->faker->randomDigit(),
            'vote_count' => $this->faker->randomDigit(),
        ];
    }
}
