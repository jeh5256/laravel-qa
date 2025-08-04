<?php

namespace App\Models;

use App\Models\User;
use App\Models\Answer;
use App\Models\VoteTrait;
use Illuminate\Support\Str;
use Mews\Purifier\Casts\CleanHtml;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

/**
 * @property bool $is_favorited
 * @property int $answer_count
 * @proerty int $user_id
 * @property-read User $questionFavorites
 * @property-read mixed $body_html
 * @property-read mixed $is_favorited
 */
class Question extends Model
{
    use VoteTrait;
    use HasFactory;

    protected $fillable = [
        'title', 'slug', 'body', 'user_id'
    ];

    protected $casts = [
        'body' => CleanHtml::class
    ];

    public function user(): BelongsTo 
    {
        return $this->BelongsTo(User::class);
    }

    public function setSlugAttribute($value): void 
    {
        $slug = !empty($value) ? $value : Str::slug($this->title);

        $this->attributes['slug'] = $slug;
    }

    public function getStatusAttribute(): string 
    {
        if ($this->answers_count > 0) {
            if ($this->best_answer_id) {
                return "answered-accepted";
            }

            return "answered";
        } else {
            return "unanswered";
        }
    }

    public function getUrlAttribute(): string 
    {
        return route('questions.show', $this->slug);
    }

    public function getCreatedDateAttribute() 
    {
        return $this->created_at->diffForHumans();
    }

    public function answers()
    {
        return $this->hasMany(Answer::class);
    }

    public function acceptBestAnswer(Answer $answer)
    {
        $answer->id === $this->best_answer_id ?
            $this->best_answer_id = null: 
            $this->best_answer_id = $answer->id;
        
        $this->save();
    }

    public function questionFavorites(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'question_favorites')->withTimestamps();
    }

    public function isFavorited(): bool
    {
        return $this->questionFavorites()->where('user_id', auth()->id())->count() > 0;
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

    public function getIsFavoritedAttribute()
    {
        return $this->isFavorited();
    }

    public function getFavoritesCountAttribute()
    {
        return $this->questionFavorites->count();
    }

    public function getExcerptAttribute()
    {
        return $this->excerpt(250);
    }

    public function excerpt(int $length=250)
    {
        return Str::limit($this->body, $length);
    }
}
