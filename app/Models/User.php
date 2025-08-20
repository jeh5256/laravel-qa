<?php

namespace App\Models;

use App\Models\Answer;
use App\Models\Question;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\MorphToMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class User extends Authenticatable
{
    use Notifiable;

    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name', 'email', 'password',
    ];

     /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $appends = [
        'url', 'avatar'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /** @return \Illuminate\Database\Eloquent\Relations\HasMany<
    *    \App\Models\Question,
    *    $this
    * > 
    */
    public function questions(): HasMany 
    {
        return $this->hasMany(Question::class);
    }

    /** @return \Illuminate\Database\Eloquent\Relations\HasMany<
    *    \App\Models\Answer,
    *    $this
    * > 
    */
    public function answers(): HasMany
    {
        return $this->hasMany(Answer::class);
    }

    /** @return \Illuminate\Database\Eloquent\Relations\BelongsToMany<
    *    \App\Models\Question,
    *    $this
    * > 
    */
    public function questionFavorites(): BelongsToMany
    {
        return $this->belongsToMany(Question::class, 'question_favorites')->withTimestamps();
    }

    /** @return \Illuminate\Database\Eloquent\Relations\MorphToMany<
    *    \App\Models\Question,
    *    $this,
    *    \Illuminate\Database\Eloquent\Relations\MorphPivot, 
    *   'pivot'
    * > 
    */
    public function voteQuestions(): MorphToMany
    {
        return $this->morphedByMany(Question::class, 'vote');
    }

    /** @return \Illuminate\Database\Eloquent\Relations\MorphToMany<
    *    \App\Models\Answer,
    *    $this,
    *    \Illuminate\Database\Eloquent\Relations\MorphPivot, 
    *   'pivot'
    * > 
    */
    public function voteAnswers(): MorphToMany
    {
        return $this->morphedByMany(Answer::class, 'vote');
    }

    public function getUrlAttribute(): string 
    {
        //return route('question.show', $this->id);
        return '#';
    }

    public function getAvatarAttribute(): string 
    {
        $email = $this->email;
        $size = 32;

        return "https://www.gravatar.com/avatar/" . md5(strtolower(trim($email))) . "?s=" . $size;
    }

    public function voteForQuestion(Question $question, int $vote): int
    {
        $voteQuestions = $this->voteQuestions();

        return $this->_vote($voteQuestions, $question, $vote);
    }

    public function voteForAnswer(Answer $answer, int $vote): int
    {
        $voteAnswers = $this->voteAnswers();

        return $this->_vote($voteAnswers, $answer, $vote);   
    }  

    /**
    * @template TModel of \App\Models\Answer|\App\Models\Question
    *
    * @param \Illuminate\Database\Eloquent\Relations\MorphToMany<TModel, $this> $relationship
    * @param TModel $model
    * @param int $vote
    * @return int
    */
    private function _vote(BelongsToMany $relationship, Answer|Question $model, int $vote): int
    {
        /** @var \App\Models\Answer|\App\Models\Question|null $existing */
        $existing = $relationship->where('vote_id', $model->id)->withPivot('vote')->first();

        if (!$existing) {
            $relationship->attach($model, ['vote' => $vote]);
        }
        
        if ($existing && $existing->pivot->vote === intval($vote)) {
            $relationship->toggle($model);
        }

        if ($existing && $existing->pivot->vote !== intval($vote)) {
            $relationship->updateExistingPivot($model, ['vote' => $vote]);
        }
       

        $model->load('votes');
        $downVotes = (int) $model->downVotes()->sum('vote');
        $upVotes = (int) $model->upVotes()->sum('vote');
        
        $model->vote_count = $upVotes + $downVotes;
        $model->save();

        return $model->vote_count;
    }
}