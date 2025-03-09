<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class GroupShortResource extends JsonResource
{
    /**
     * Сериализация списка групп
     */
    public function toArray(Request $request): array
    {
        return [
            'name' => $this->name,
        ];
    }
}
