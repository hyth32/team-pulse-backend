<?php

namespace App\Http\Resources\Template;

use App\Enums\Test\TemplateStatus;
use App\Http\Resources\Topic\TopicShortResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TemplateResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'description' => $this->description,
            'status' => TemplateStatus::getLabelFromValue($this->status),
            'authorLogin' => $this->author->name,
            'topics' => TopicShortResource::collection($this->questions->topics),
            'createdAt' => $this->created_at,
            'updatedAt' => $this->updated_at,
        ];
    }
}
