<?php

namespace App\Http\Resources\User;

use App\Enums\Test\TopicCompletionStatus;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TestCompletionResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'fullName' => UserFullNameResource::make($this),
            'login' => $this->login,
            'completionStatus' => TopicCompletionStatus::getLabelFromValue($this->pivot->completion_status),
        ];
    }
}
