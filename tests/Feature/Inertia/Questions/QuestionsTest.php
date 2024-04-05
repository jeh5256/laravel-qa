<?php

namespace Tests\Feature\Inertia;

use App\Models\Answer;
use Tests\TestCase;
use App\Models\User;
use App\Models\Question;
use Inertia\Testing\AssertableInertia as Assert;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Database\Eloquent\Factories\Sequence;

class QuestionsTest extends TestCase
{
    use RefreshDatabase;

    public User $user;

    
    protected function setUp(): void
    {
        parent::setUp();

        $this->user = User::factory()->create();
    }

    /** @test */
    public function can_render_questions_page(): void
    {
        [$question1, $question2, $question3] = Question::factory([
            'user_id' => $this->user->id
        ])
        ->state(new Sequence(
            ['created_at' => now()],
            ['created_at' => now()->subDay()],
            ['created_at' => now()->subDays(2)]
        ))
        ->count(3)->create();

        $this->get('/questions')
            ->assertInertia(
                fn (Assert $page) => $page
                    ->component('Questions/QuestionsIndex')
                    ->has(
                        'questions',
                        function(Assert $page) use($question1, $question2, $question3) {
                            return $page->where('data.0.id', $question1->id)
                                ->where('data.0.title', $question1->title)
                                ->where('data.0.slug', $question1->slug)
                                ->where('data.1.id', $question2->id)
                                ->where('data.1.title', $question2->title)
                                ->where('data.1.slug', $question2->slug)
                                ->where('data.2.id', $question3->id)
                                ->where('data.2.title', $question3->title)
                                ->where('data.2.slug', $question3->slug)
                                ->has('current_page')
                                ->etc();
                        }
                    )
            );
    }

    /** @test */
    public function can_render_questions_create_page()
    {
        $this->actingAs($this->user);

        $this->get('/questions/create')
            ->assertInertia(
                fn (Assert $page) => $page
                    ->component('Questions/QuestionsCreate')
            );
    }

    /** @test */
    public function user_is_redirected_to_login_when_accessing_questions_create_while_unathenticated()
    {
        $this->get('/questions/create')
           ->assertRedirect('/login');
    }

    /** @test */
    public function can_render_edit_question_page()
    {
        $question = Question::factory([
            'user_id' => $this->user->id
        ])
        ->create();

        $answer = Answer::factory([
            'question_id' => $question->id
        ])->create();
        
        $this->get("/questions/{$question->slug}")
            ->assertInertia(
                fn (Assert $page) => $page
                    ->component('Questions/QuestionsShow')
                    ->has(
                        'question',
                        function(Assert $page) use($question) {
                            return $page->where('id', $question->id)
                                ->where('title', $question->title)
                                ->where('slug', $question->slug)
                                ->where('user_id', $this->user->id)
                                ->where('body_html', $question->body_html)
                                ->etc();
                        }
                    )
                    ->has(
                        'answers',
                        function(Assert $page) use($answer) {
                            return $page->where('0.id', $answer->id)
                                ->where('0.question_id', $answer->question_id)
                                ->where('0.body', $answer->body);
                        
                        }
                    )
            );
    }
}
