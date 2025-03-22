<?php

namespace App\Http\Resources\User;

use App\Enums\Test\TopicCompletionStatus;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TestCompletionResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        $topicCompletions = $this->assignedTests()->where(['id' => $this->pivot->assigned_test_id])->first()->topicCompletions->toArray();

        $testCompletionStatus = TopicCompletionStatus::NotPassed;

        $completionStatuses = array_map(function ($item) {
            return $item['pivot']['completion_status'];
        }, $topicCompletions);

        if (in_array(2, $completionStatuses)) {
            $testCompletionStatus = in_array(0, $completionStatuses) || in_array(1, $completionStatuses)
                ? TopicCompletionStatus::InProgress
                : TopicCompletionStatus::Passed;
        }

        return [
            'id' => $this->id,
            'fullName' => UserFullNameResource::make($this),
            'login' => $this->login,
            'completionStatus' => $testCompletionStatus->label(),
        ];
    }
}
