<?php

namespace App\Http\Resources\Question;

use App\Http\Resources\Answer\AnswerResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class QuestionResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'text' => $this->text,
            'answerType' => $this->answer_type,
            'answers' => AnswerResource::collection($this->answers),
        ];
    }
}
