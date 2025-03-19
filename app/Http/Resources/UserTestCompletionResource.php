<?php

namespace App\Http\Resources;

use App\Enums\Test\TestCompletionStatus;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserTestCompletionResource extends JsonResource
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
            'lastname' => $this->lastname,
            'login' => $this->login,
            'completionStatus' => TestCompletionStatus::getLabelFromValue($this->pivot->completion_status),
        ];
    }
}
