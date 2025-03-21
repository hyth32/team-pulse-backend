<?php

namespace App\Http\Resources\Topic;

use App\Enums\Test\TopicCompletionStatus;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TopicCompletionResource extends JsonResource
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
            'name' => $this->name,
            'completionStatus' => TopicCompletionStatus::getLabelFromValue($this->pivot->completion_status),
        ];
    }
}
