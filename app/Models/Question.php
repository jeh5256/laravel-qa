<?php

namespace App\Models;

use App\Models\User;
use App\Models\Answer;
use App\Models\VoteTrait;
use Illuminate\Support\Str;
use Mews\Purifier\Casts\CleanHtml;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

/**
 * @property bool $is_favorited
 * @property int $answer_count
 * @proerty int $user_id
 * @property string $body
 * @property \Illuminate\Database\Eloquent\Relations\MorphPivot&object{vote: int} $pivot
 * @property-read string $slug
 * @property-read User $questionFavorites
 * @property-read mixed $is_favorited
 */
class Question extends Model
{
    use VoteTrait;

    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory;

    protected $fillable = [
        'title', 'slug', 'body', 'user_id'
    ];

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'body' => CleanHtml::class
        ];
    }

    /** @return \Illuminate\Database\Eloquent\Relations\BelongsTo<
    *    \App\Models\User,
    *    $this
    * > 
    */
    public function user(): BelongsTo 
    {
        return $this->BelongsTo(User::class);
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
    *    \App\Models\User,
    *    $this
    * > 
    */
    public function questionFavorites(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'question_favorites')->withTimestamps();
    }

    public function acceptBestAnswer(Answer $answer): void
    {
        $answer->id === $this->best_answer_id ?
            $this->best_answer_id = null: 
            $this->best_answer_id = $answer->id;
        
        $this->save();
    }

    public function getUserVote(): string|bool
    {
        $user_voted = $this->votes()
            ->where('id', Auth::user()->id)
            ->withPivot('vote')
            ->wherePivotNotNull('vote')
            ->first();

        if (!$user_voted) {
            return false;
        }

        /** @var \Illuminate\Database\Eloquent\Relations\MorphPivot&object{vote: int} $pivot */
        $pivot = $user_voted->pivot;

        return $pivot->vote === 1  ? 'upvoted' : 'downvoted';
    }

    /**
    * @return \Illuminate\Database\Eloquent\Casts\Attribute<string, never>
    */
    protected function slug(): Attribute
    {
         return Attribute::make(
            set: fn(string $value) => !empty($value) ? $value : Str::slug($this->title)
        );
    }

    /**
    * @return \Illuminate\Database\Eloquent\Casts\Attribute<string, never>
    */
    protected function url(): Attribute
    {
        return Attribute::make(
            get: fn() => route('questions.show', $this->slug)
        );
    }

    /**
    * @return \Illuminate\Database\Eloquent\Casts\Attribute<string, never>
    */
    protected function createdDate(): Attribute
    {
        return Attribute::make(
            get: fn() => $this->created_at->diffForHumans()
        );
    }

    /**
    * @return \Illuminate\Database\Eloquent\Casts\Attribute<string, never>
    */
    protected function userVoted(): Attribute
    {
        return Attribute::make(
            get: fn() => $this->getUserVote()
        );
    }

    /**
    * @return \Illuminate\Database\Eloquent\Casts\Attribute<string, never>
    */
    protected function isFavorited(): Attribute
    {
        return Attribute::make(
            get:  fn() => $this->questionFavorites()
                ->where('user_id', Auth::id())
                ->count() > 0
        );
    }

     /**
    * @return \Illuminate\Database\Eloquent\Casts\Attribute<string, never>
    */
    protected function favoritesCount(): Attribute
    {
        return Attribute::make(
            get: fn() => $this->questionFavorites->count()
        );
    }

    /**
    * @return \Illuminate\Database\Eloquent\Casts\Attribute<string, never>
    */
    public function excerpt(): Attribute
    {
        return Attribute::make(
            get: fn() => Str::limit($this->body, 250)
        );
    }
}
