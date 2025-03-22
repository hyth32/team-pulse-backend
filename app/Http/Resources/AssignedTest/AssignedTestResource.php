<?php

namespace App\Http\Resources\AssignedTest;

use App\Enums\Test\TopicCompletionStatus;
use App\Http\Resources\Topic\TopicCompletionResource;
use App\Http\Resources\User\UserFullNameResource;
use App\Models\UserTopicCompletion;
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
            'subjectFullName' => UserFullNameResource::make($this->subject),
            'startDate' => $this->start_date,
            'endDate' => $this->end_date,
        ];

        if ($isAdmin) {
            return $baseData;
        }

        return array_merge($baseData, [
            'topics' => TopicCompletionResource::collection(
                collect(UserTopicCompletion::query()
                        ->where([
                            'user_id' => $request->user()->id,
                            'assigned_test_id' => $this->id,
                            'completion_status' => TopicCompletionStatus::NotPassed->value(),
                        ])
                        ->get()
                    )
                ),
        ]);
    }
}
