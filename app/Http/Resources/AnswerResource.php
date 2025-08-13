<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin \App\Models\Answer */
class AnswerResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'body' => $this->body,
            'is_best_answer' => $this->is_best_answer,
            'user_voted' => $this->user_voted,
            'vote_count' => $this->vote_count,
            'created_date' => $this->created_at,
            'user' => UserResource::make($this->whenLoaded('user'))
        ];
    }
}
