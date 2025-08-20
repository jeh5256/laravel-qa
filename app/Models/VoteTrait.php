<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\MorphToMany;

trait VoteTrait
{
    /** @return \Illuminate\Database\Eloquent\Relations\MorphToMany<
    *    \App\Models\User,
    *    $this,
    *    \Illuminate\Database\Eloquent\Relations\MorphPivot, 
    *   'pivot'
    * > 
    */
    public function votes(): MorphToMany
    {
        return $this->morphToMany(User::class, 'vote');
    }

    /** @return \Illuminate\Database\Eloquent\Relations\MorphToMany<
    *    \App\Models\User,
    *    $this,
    *    \Illuminate\Database\Eloquent\Relations\MorphPivot, 
    *   'pivot'
    * > 
    */
    public function upVotes(): MorphToMany
    {
        return $this->votes()->wherePivot('vote', 1);
    }

    /** @return \Illuminate\Database\Eloquent\Relations\MorphToMany<
    *    \App\Models\User,
    *    $this,
    *    \Illuminate\Database\Eloquent\Relations\MorphPivot, 
    *   'pivot'
    * > 
    */
    public function downVotes(): MorphToMany
    {
        return $this->votes()->wherePivot('vote', -1);
    }
}

