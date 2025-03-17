<?php

namespace App\Http\Resources;

use App\Enums\Test\TestCompletionStatus;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @OA\Schema(schema="TestShortResource", description="Тест", properties={
 *      @OA\Property(property="id", type="string", format="uuid", description="ID теста"),
 *      @OA\Property(property="name", type="string", description="Название теста"),
 *      @OA\Property(property="assigner", type="object", description="Пользователь, назначивший тест", ref="#/components/schemas/User"),
 *      @OA\Property(property="startDate", type="date-time", description="Дата начала"),
 *      @OA\Property(property="endDate", type="date-time", description="Дата окончания"),
 *      @OA\Property(property="completionStatus", type="string", description="Статус прохождения теста", ref="#/components/schemas/TestCompletionStatus"),
 * })
 */
class TestShortResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $user = $request->user();

        return [
            'id' => $this->id,
            'name' => $this->name,
            'assigner' => UserShortResource::make($this->assigner),
            'startDate' => $this->start_date,
            'endDate' => $this->end_date,
            'completionStatus' => TestCompletionStatus::getLabelFromValue($this->getCompletionStatus($user->id)),
        ];
    }
}
