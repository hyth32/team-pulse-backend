<?php

namespace App\Http\Resources\User;

use App\Enums\Test\TopicCompletionStatus;
use App\Models\AssignedTest;
use App\Models\UserTestCompletion;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Log;

class TestCompletionResource extends JsonResource
{
    private $testId;

    public function __construct($resource, $testId = null)
    {
        parent::__construct($resource);
        $this->testId = $testId;
    }

    public function toArray(Request $request): array
    {
        $test = AssignedTest::where(['id' => $this->testId])->first();
        $completionStatus = UserTestCompletion::where([
            'user_id' => $this->id,
            'assigned_test_id' => $this->testId,
        ])->first()->completion_status;

        $isLate = $test->late_result;
        $endDate = $test->end_date;
        $isCompleted = $completionStatus == TopicCompletionStatus::Passed->value();
        $resultReady = $isCompleted;

        if ($endDate && Carbon::parse($endDate)->isPast() && !$isCompleted) {
            $completionStatus = TopicCompletionStatus::Expired->value();
        }

        if ($isLate) {
            if ($endDate && Carbon::parse($endDate)->isPast() && $isCompleted) {
                $resultReady = true;
            } else {
                $resultReady = false;
            }
        }

        return [
            'id' => $this->id,
            'fullName' => UserFullNameResource::make($this),
            'login' => $this->login,
            'testId' => $this->testId,
            'completionStatus' => TopicCompletionStatus::getLabelFromValue($completionStatus),
            'result' => $resultReady,
        ];
    }
}
