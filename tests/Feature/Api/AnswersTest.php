<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Question;
use Illuminate\Foundation\Testing\RefreshDatabase;

class AnswersTest extends TestCase
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
      public function can_answer_a_question()
      {
          $question = Question::factory()->create();
  
          $res=$this->json("POST", "/questions/{$question->id}/answers", [
              'body' => 'this is an answer'
          ])
          ->assertStatus(201)
          ->assertJson([
            'message' => 'Your answer has been submitted',
          ]);
  
          $this->assertDatabaseHas('answers', [
            'body' => 'this is an answer',
            'question_id' => $question->id,
            'user_id' => $this->user->id
          ]);
      }
}
