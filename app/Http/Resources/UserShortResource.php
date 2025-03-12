<?php

namespace App\Http\Resources;

use App\Enums\User\UserRole;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserShortResource extends JsonResource
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
            'email' => $this->email,
            'role' => UserRole::getLabelFromValue($this->role),
            'createdAt' => $this->created_at,
        ];
    }
}
