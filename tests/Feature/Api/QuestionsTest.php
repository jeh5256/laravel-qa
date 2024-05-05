<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\Question;
use App\Models\User;

class QuestionsTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function can_create_a_question()
    {
        $this->actingAs($user = User::factory()->create());

        $question = Question::factory()->make();

        $this->json("POST", "/questions", [
            'title' => $question->title,
            'slug' => $question->slug,
            'body' => $question->body
        ])
        ->assertRedirect('questions')
        ->assertSessionHasNoErrors();

        $this->assertDatabaseHas('questions', [
            'title' => $question->title,
            'slug' => $question->slug,
            'body' => $question->body,
            'views' => 0,
            'answers_count' => 0,
            'best_answer_id' => null,
            'user_id' => $user->id
        ]);
    }
}
