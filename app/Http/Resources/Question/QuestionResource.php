<?php

namespace App\Http\Resources\Question;

use App\Http\Resources\Answer\AnswerResource;
use App\Http\Resources\Tag\TagResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class QuestionResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'question' => $this->text,
            'answerType' => $this->answer_type,
            'tags' => TagResource::collection($this->tags),
            'answers' => AnswerResource::collection($this->answers),
        ];
    }
}
