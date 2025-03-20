<?php

namespace App\Http\Resources\AssignedTest;

use App\Http\Resources\Group\GroupResource;
use App\Http\Resources\Template\TemplateResource;
use App\Http\Resources\User\UserResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AssignedTestResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'description' => $this->description,
            'test' => TemplateResource::make($this->template),
            'isAnonymous' => $this->is_anonymous,
            'subjectUser' => UserResource::make($this->subject),
            'startDate' => $this->start_date,
            'endDate' => $this->end_date,
            'groups' => GroupResource::collection($this->groups),
            'employees' => UserResource::collection($this->users),
        ];
    }
}
