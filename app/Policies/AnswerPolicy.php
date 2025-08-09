<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Answer;
use Illuminate\Auth\Access\HandlesAuthorization;

class AnswerPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can update the answer.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Answer  $answer
     * @return bool
     */
    public function update(User $user, Answer $answer): bool
    {
        return $user->id === $answer->user_id;
    }

    /**
     * Determine whether the user can accept the answer as best answer.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Answer  $answer
     * @return bool
     */
    public function accept(User $user, Answer $answer): bool
    {
        return $user->id === $answer->question->user_id;
    }

    /**
     * Determine whether the user can delete the answer.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Answer  $answer
     * @return bool
     */
    public function delete(User $user, Answer $answer): bool
    {
        return $user->id === $answer->user_id;
    }
}
