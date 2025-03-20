<?php

namespace App\Http\Resources\Answer;

use App\Http\Resources\Tag\TagResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AnswerTagPointsResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'tag' => TagResource::make($this),
            'points' => $this->pivot->point_count,
        ];
    }
}
