<?php

namespace App\Http\Resources\User;

use App\Enums\User\UserRole;
use App\Http\Resources\Group\GroupResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'fullName' => UserFullNameResource::make($this),
            'login' => $this->login,
            'email' => $this->email,
            'role' => UserRole::getLabelFromValue($this->role),
            'groups' => GroupResource::collection($this->groups),
            'createdAt' => $this->created_at,
        ];
    }
}
