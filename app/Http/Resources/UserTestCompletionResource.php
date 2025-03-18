<?php

namespace App\Http\Resources;

use App\Enums\Test\TestCompletionStatus;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserTestCompletionResource extends JsonResource
{
    protected $testId;

    public function __construct($resource, $testId = null)
    {
        parent::__construct($resource);
        $this->testId = $testId;
    }

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
            'completionStatus' => TestCompletionStatus::getLabelFromValue($this->getCompletionStatus($this->testId)),
        ];
    }
}
