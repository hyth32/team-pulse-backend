<?php

namespace App\Http\Resources\Topic;

use App\Enums\Test\TopicCompletionStatus;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TopicCompletionResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->topic->id,
            'name' => $this->topic->name,
            'completionStatus' => TopicCompletionStatus::getLabelFromValue($this->completion_status),
        ];
    }
}
