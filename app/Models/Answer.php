<?php

namespace App\Models;

use App\Models\VoteTrait;
use Mews\Purifier\Facades\Purifier;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Answer extends Model
{   
    use VoteTrait;
    use HasFactory;

    protected $fillable = ['body', 'user_id'];

    protected $appends = ['created_date', 'body_html', 'is_best_answer', 'user_voted'];

    public static function boot() {
        parent::boot();

        static::created(function($answer) {
            $answer->question->increment('answers_count');
        });

        static::deleted(function($answer) {
            $question = $answer->question;
            $question->decrement('answers_count');

            if ($question->best_answer_id = $answer->id) {
                $question->best_answer_id = null;
                $question->save();
            }
        });
    }

    public function question() {
        return $this->belongsTo(Question::class);
    }

    public function user() {
        return $this->belongsTo(User::class);
    }

    public function getBodyHtmlAttribute() 
    {
        return Purifier::clean($this->body);
    }

    public function getCreatedDateAttribute() 
    {
        return $this->created_at->diffForHumans();
    }

    public function isBestAnswer()
    {
        return $this->id == $this->question->best_answer_id;
    }

    public function getIsBestAnswerAttribute() {
        return $this->isBestAnswer();
    }

    public function getStatusAttribute()
    {
        return $this->isBestAnswer() ? 'vote-accepted' : '';
    }

    public function getUserVote()
    {
        $user_voted = $this->votes()
            ->where('id', auth()->id())
            ->withPivot('vote')
            ->wherePivotNotNull('vote')
            ->first();

        if (!$user_voted) {
            return false;
        }

        return $user_voted->pivot->vote === 1  ? 'upvoted' : 'downvoted';
    }

    public function getUserVotedAttribute()
    {
        return $this->getUserVote();
    }

}