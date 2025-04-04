<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class QuestionResource extends JsonResource
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
            'question' => $this->text,
            'answerType' => $this->type,
            'tags' => TagShortResource::collection($this->tags),
            'answers' => AnswerResource::collection($this->answers),
        ];
    }
}
