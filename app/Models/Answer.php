<?php

namespace App\Models;

use App\Models\VoteTrait;
use Mews\Purifier\Facades\Purifier;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property string $body
 * @property bool $is_favorited
 * @property int $user_id
 * @property int $answer_count
 * @property bool $is_best_answer
 * @property-read Question $question
 */
class Answer extends Model
{   
    use VoteTrait;
    use HasFactory;

    protected $fillable = ['body', 'user_id'];

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

    public function question(): BelongsTo
    {
        return $this->belongsTo(Question::class);
    }

    public function user(): BelongsTo 
    {
        return $this->belongsTo(User::class);
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