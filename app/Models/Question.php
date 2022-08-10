<?php

namespace App\Models;

use App\Models\User;
use App\Models\Answer;
use App\Models\VoteTrait;
use Illuminate\Support\Str;
use Mews\Purifier\Facades\Purifier;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Question extends Model
{
    use VoteTrait;
    use HasFactory;

    protected $fillable = [
        'title', 'body'
    ];

    protected $appends = [
        'created_date', 'is_favorited', 'favorites_count', 'body_html', 'user_voted'
    ];

    public function user() {
        return $this->BelongsTo(User::class);
    }

    public function setTitleAttribute($value) 
    {
        $this->attributes['title'] = $value;
        $this->attributes['slug'] = Str::slug($value);
    }

    public function getStatusAttribute() 
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

    public function getUrlAttribute() 
    {
        return route('questions.show', $this->slug);
    }

    public function getCreatedDateAttribute() 
    {
        return $this->created_at->diffForHumans();
    }

    public function getBodyHtmlAttribute() 
    {
        return clean($this->bodyHtml());
    }

    public function answers()
    {
        return $this->hasMany(Answer::class);
    }

    public function acceptBestAnswer(Answer $answer)
    {
        $this->best_answer_id = $answer->id;
        $this->save();
    }

    public function questionFavorites()
    {
        return $this->belongsToMany(User::class, 'question_favorites')->withTimestamps();
    }

    public function isFavorited()
    {
        return $this->questionFavorites()->where('user_id', auth()->id())->count() > 0;
    }

    public function getUserVote()
    {
       $user_voted = $this->votes()
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

    public function excerpt($length=250)
    {
        return Str::limit(strip_tags($this->bodyHtml()), $length);
    }

    private function bodyHtml()
    {
        return Purifier::clean($this->body);
    }
}
