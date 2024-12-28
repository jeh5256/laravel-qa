<?php

namespace Tests\Feature\Api;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Auth;
use Tests\TestCase;
use App\Models\Question;
use App\Models\User;

class QuestionsTest extends TestCase
{
    use RefreshDatabase;

    protected User $user;


    public function setUp(): void
    {
        parent::setUp();

        $this->user = User::factory()->create();
        $this->actingAs($this->user);
    }

    /** @test */
    public function can_create_a_question()
    {
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
            'user_id' => $this->user->id
        ]);
    }

    /** @test */
    public function must_be_authenicated_to_create_a_question()
    {
        Auth::logout();

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
        $question = Question::factory(['user_id' => $this->user->id])->create();
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
            'user_id' => $this->user->id
        ]);

        $this->assertDatabaseMissing('questions', [
            'title' => $question->title,
            'slug' => $question->slug,
            'body' => $question->body,
            'user_id' => $this->user->id
        ]);
    }

    /** @test */
    public function a_user_cant_edit_another_users_question()
    {
        $anotherUser = User::factory()->create();

        $question = Question::factory([
            'user_id' => $anotherUser->id
        ])->create();

        $this->patch("/questions/{$question->slug}", [
            'title' => 'updated title',
            'slug' => 'updated slug',
            'body' => 'uppdated body'
        ])
        ->assertForbidden();

        $this->assertDatabaseHas('questions', [
            'title' => $question->title,
            'slug' => $question->slug,
            'body' => $question->body,
            'user_id' => $anotherUser->id
        ]);
    }

    /** @test */
    public function a_user_can_upvote_a_question()
    {
        $question = Question::factory(['vote_count' => 0])->create();

        $this->json("POST", "/questions/{$question->id}/vote", [
            'vote' => 1
        ])
        ->assertStatus(200)
        ->assertJson([
            'message' => 'Question upvoted',
            'votesCount' => $question->vote_count + 1
        ]);
    }

    /** @test */
    public function a_user_cant_upvote_a_question_more_than_once()
    {
        $question = Question::factory(['vote_count' => 0])->create();

        $this->json("POST", "/questions/{$question->id}/vote", [
            'vote' => 1
        ]);
       
        $this->json("POST", "/questions/{$question->id}/vote", [
            'vote' => 1
        ])
        ->assertJson([
            'votesCount' => 0
        ]);
    }

    /** @test */
    public function a_user_can_downvote_a_question()
    {
        $question = Question::factory(['vote_count' => 0])->create();

        $this->json("POST", "/questions/{$question->id}/vote", [
            'vote' => -1
        ])
        ->assertStatus(200)
        ->assertJson([
            'message' => 'Question downvoted',
            'votesCount' => $question->vote_count - 1
        ]);
    }

    /** @test */
    public function a_user_cant_downvote_a_question_more_than_once()
    {
        $question = Question::factory(['vote_count' => 0])->create();

        $this->json("POST", "/questions/{$question->id}/vote", [
            'vote' => -1
        ]);
       
        $this->json("POST", "/questions/{$question->id}/vote", [
            'vote' => -1
        ])
        ->assertJson([
            'votesCount' => 0
        ]);
    }

    /** @test */
    public function a_user_can_favorite_a_question()
    {
        $question = Question::factory()->create();

        $res = $this->json("POST", "/questions/{$question->id}/favorites")
            ->assertStatus(204);

        $this->assertEmpty($res->getContent());

        $this->assertDatabaseHas('question_favorites', [
            'user_id' => $this->user->id,
            'question_id' => $question->id
        ]);

    }

     /** @test */
     public function a_user_can_unfavorite_a_question()
     {
         $question = Question::factory()->create();
 
         $question->questionFavorites()->toggle(auth()->user()->id);

         $res = $this->json("POST", "/questions/{$question->id}/favorites");

         $this->assertDatabaseMissing('question_favorites', [
             'user_id' => $this->user->id,
             'question_id' => $question->id
         ]);
 
     }
}
