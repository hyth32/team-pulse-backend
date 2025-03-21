<?php

namespace App\Http\Resources\User;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserFullNameResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'firstName' => $this->name,
            'lastName' => $this->lastname,
        ];
    }
}
