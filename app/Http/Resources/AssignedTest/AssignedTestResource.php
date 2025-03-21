<?php

namespace App\Http\Resources\AssignedTest;

use App\Http\Resources\Topic\TopicShortResource;
use App\Http\Resources\User\UserFullNameResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AssignedTestResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        $isAdmin = $request->user()->isAdmin();

        $baseData = [
            'id' => $this->id,
            'name' => $this->name,
            'description' => $this->description,
            'isAnonymous' => $this->is_anonymous,
            'subjectFullname' => UserFullNameResource::make($this->subject),
            'startDate' => $this->start_date,
            'endDate' => $this->end_date,
        ];

        if ($isAdmin) {
            return $baseData;
        }

        return array_merge($baseData, [
            'topics' => TopicShortResource::collection($this->template->topics),
        ]);
    }
}
