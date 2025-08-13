<?php

namespace App\Models;

use App\Models\VoteTrait;
use Mews\Purifier\Casts\CleanHtml;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

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
    
    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'body' => CleanHtml::class
        ];
    }

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

    public function getIsBestAnswerAttribute() {
        return $this->id == $this->question->best_answer_id;
    }

    protected function bestAnswer(): Attribute
    {
        return Attribute::make(
            get: fn (string $value) => $this->isBestAnswer() ? 'vote-accepted' : ''
        );
    }
    
    protected function status(): Attribute
    {
        return Attribute::make(
            get: fn (string $value) => $this->isBestAnswer() ? 'vote-accepted' : ''
        );
    }

    protected function hasUserVoted(): Attribute
    {
        return Attribute::make(
            get: fn (string $value) => $this->getUserVote()
        );
    }

    private function isBestAnswer(): bool
    {
        return $this->id == $this->question->best_answer_id;
    }

    public function getUserVote(): ?string
    {
        $user_voted = $this->votes()
            ->where('id', Auth::user()->id)
            ->withPivot('vote')
            ->wherePivotNotNull('vote')
            ->first();

        if (!$user_voted) {
            return null;
        }

        return $user_voted->pivot->vote === 1  ? 'upvoted' : 'downvoted';
    }
}