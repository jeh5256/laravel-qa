<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Answer;
use App\Models\Question;
use Illuminate\Support\Facades\Auth;
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

		$this->json("POST", "/questions/{$question->id}/answers", [
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

	/** @test */
	public function must_be_authenicated_to_answer_a_question()
	{
		Auth::logout();

		$question = Question::factory()->create();

		$this->json("POST", "/questions/{$question->id}/answers", [
			'title' => $question->title,
			'slug' => $question->slug,
			'body' => $question->body
		])
			->assertStatus(401);
	}

	/** @test */
	public function a_user_can_edit_their_answer()
	{
		$question = Question::factory(['user_id' => $this->user->id])->create();

		$answer = Answer::factory([
			'user_id' => $this->user->id,
			'question_id' => $question->id
		])->create();

		$editedAnswer = Answer::factory([
			'question_id' => $question->id,
			'user_id' => $this->user->id
		])->make();

		$this->patch("/questions/{$question->id}/answers/{$answer->id}", [
			'body' => $editedAnswer->body
		])
			->assertRedirect(route('questions.show', ['question' => $question->slug]))
			->assertSessionHasNoErrors();

		$this->assertDatabaseHas('answers', [
			'question_id' => $question->id,
			'body' => $editedAnswer->body,
			'user_id' => $this->user->id
		]);

		$this->assertDatabaseMissing('questions', [
			'question_id' => $question->id,
			'body' => $question->body,
			'user_id' => $this->user->id
		]);
	}

	/** @test */
	public function a_user_cant_edit_another_users_question()
	{
		$anotherUser = User::factory()->create();

		$question = Question::factory()->create();

		$answer = Answer::factory([
			'question_id' => $question->id,
			'user_id' => $anotherUser->id
		])->create();

		$this->patch("/questions/{$question->id}/answers/{$answer->id}", [
			'body' => 'edited answer body'
		])
			->assertForbidden();

		$this->assertDatabaseMissing('answers', [
			'user_id' => $anotherUser->id,
			'body' => 'edited answer body'
		]);
	}

	/** @test */
	public function a_user_can_upvote_an_answer()
	{
		$question = Question::factory()->create();

		$answer = Answer::factory([
			'question_id' => $question->id,
			'vote_count' => 0
		])->create();

		$this->json("POST", "/answers/{$answer->id}/vote", [
			'vote' => 1
		])
			->assertStatus(200)
			->assertJson([
				'message' => 'Answer upvoted',
				'votesCount' => $answer->vote_count + 1
			]);
	}

	/** @test */
	public function a_user_cant_upvote_an_answer_more_than_once()
	{
		$question = Question::factory()->create();

		$answer = Answer::factory([
			'question_id' => $question->id,
			'vote_count' => 0
		])->create();

		$this->json("POST", "/answers/{$answer->id}/vote", [
			'vote' => 1
		]);

		$this->json("POST", "/answers/{$answer->id}/vote", [
			'vote' => 1
		])
			->assertJson([
				'votesCount' => 0
			]);
	}

	/** @test */
	public function a_user_can_downvote_an_answer()
	{
		$question = Question::factory()->create();

		$answer = Answer::factory([
			'question_id' => $question->id,
			'vote_count' => 0
		])->create();

		$this->json("POST", "/answers/{$answer->id}/vote", [
			'vote' => -1
		])
			->assertStatus(200)
			->assertJson([
				'message' => 'Answer downvoted',
				'votesCount' => $answer->vote_count - 1
			]);
	}

	/** @test */
	public function a_user_cant_downvote_a_question_more_than_once()
	{
		$question = Question::factory(['vote_count' => 0])->create();

		$answer = Answer::factory([
			'question_id' => $question->id,
			'vote_count' => 0
		])->create();

		$this->json("POST", "/answers/{$answer->id}/vote", [
			'vote' => -1
		]);

		$this->json("POST", "/answers/{$answer->id}/vote", [
			'vote' => -1
		])
			->assertJson([
				'votesCount' => 0
			]);
	}

	/** @test */
	public function a_user_can_mark_an_answer_as_best_answer()
	{
		$question = Question::factory([
			'user_id' => $this->user->id,
			'vote_count' => 0,
			'best_answer_id' => null
		])->create();

		$answer = Answer::factory([
			'question_id' => $question->id,
			'vote_count' => 0
		])->create();

		$this->json("POST", "/answers/{$answer->id}/accept")
			->assertStatus(200)
			->assertJson([
				'message' => 'Marked as best answer'
			]);
	}

	/** @test */
	public function a_user_cant_mark_an_answer_as_best_answer_if_they_didnt_ask_the_question()
	{
		$question = Question::factory([
			'user_id' => 9999999,
			'vote_count' => 0,
			'best_answer_id' => null
		])->create();

		$answer = Answer::factory([
			'question_id' => $question->id,
			'vote_count' => 0
		])->create();

		$this->json("POST", "/answers/{$answer->id}/accept")
			->assertStatus(403);
	}
}
