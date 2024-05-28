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

    /** @test */
    public function must_be_authenicated_to_create_a_question()
    {
        $question = Question::factory()->make();

        $this->json("POST", "/questions", [
            'title' => $question->title,
            'slug' => $question->slug,
            'body' => $question->body
        ])
        ->assertStatus(401);
    }

    /** @test */
    public function a_user_can_edit_their_question()
    {
        $this->actingAs($user = User::factory()->create());

        $question = Question::factory(['user_id' => $user->id])->create();
        $editedQuestion = Question::factory()->make();

        $this->patch("/questions/{$question->slug}", [
            'title' => $editedQuestion->title,
            'slug' => $editedQuestion->slug,
            'body' => $editedQuestion->body
        ])
        ->assertRedirect('questions')
        ->assertSessionHasNoErrors();

        $this->assertDatabaseHas('questions', [
            'title' => $editedQuestion->title,
            'slug' => $editedQuestion->slug,
            'body' => $editedQuestion->body,
            'user_id' => $user->id
        ]);

        $this->assertDatabaseMissing('questions', [
            'title' => $question->title,
            'slug' => $question->slug,
            'body' => $question->body,
            'user_id' => $user->id
        ]);
    }
}
