<?php

namespace App\Http\Resources\User;

use App\Enums\Test\TopicCompletionStatus;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserTestListResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        $completionStatus = $this->completion_status;
        $isCompleted = $completionStatus == TopicCompletionStatus::Passed->value();
        $isLateResult = $this->test->late_result;
        $endDate = $this->test?->end_date;

        $resultReady = $isCompleted;
        if ($endDate && Carbon::parse($endDate)->isPast() && !$isCompleted) {
            $completionStatus = TopicCompletionStatus::Expired->value();
        }

        if ($isLateResult) {
            if ($endDate && Carbon::parse($endDate)->isPast() && $isCompleted) {
                $resultReady = true;
            } else {
                $resultReady = false;
            }
        }

        return [
            'id' => $this->test->id,
            'name' => $this->test->name,
            'description' => $this->test->description,
            'startDate' => $this->test->start_date,
            'endDate' => $this->test->end_date,
            'completionStatus' => TopicCompletionStatus::getLabelFromValue($completionStatus),
            'result' => $resultReady,
        ];
    }
}
