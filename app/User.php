<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password',
    ];

    protected $appends = [
        'url', 'avatar'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    public function questions() 
    {
        return $this->hasMany(Question::class);
    }

    public function getUrlAttribute() 
    {
        //return route('question.show', $this->id);
        return '#';
    }

    public function answers()
    {
        return $this->hasMany(Answer::class);
    }

    public function getAvatarAttribute() {
        $email = $this->email;
        $size = 32;
        return "https://www.gravatar.com/avatar/" . md5(strtolower(trim($email))) . "?s=" . $size;
    }

    public function questionFavorites()
    {
        return $this->belongsToMany(Question::class, 'question_favorites')->withTimestamps();
    }

    public function voteQuestions()
    {
        return $this->morphedByMany(Question::class, 'vote');
    }

    public function voteAnswers()
    {
        return $this->morphedByMany(Answer::class, 'vote');
    }

    public function voteForQuestion(Question $question, $vote)
    {
        $voteQuestions = $this->voteQuestions();
        return $this->_vote($voteQuestions, $question, $vote);
    }

    public function voteForAnswer(Answer $answer, $vote)
    {
        $voteAnswers = $this->voteAnswers();
        return $this->_vote($voteAnswers, $answer, $vote);   
    }  

    private function _vote($relationship, $model, $vote)
    {
        if ($relationship->where('vote_id', $model->id)->exists()) {
            $relationship->updateExistingPivot($model, ['vote' => $vote]);
        } else {
            $relationship->attach($model, ['vote' => $vote]);
        }

        $model->load('votes');
        $downVotes = (int) $model->downVotes()->sum('vote');
        $upVotes = (int) $model->upVotes()->sum('vote');
        
        $model->vote_count = $upVotes + $downVotes;
        $model->save();

        return $model->vote_count;
    }
}
