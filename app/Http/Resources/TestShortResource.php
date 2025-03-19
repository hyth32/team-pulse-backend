<?php

namespace App\Http\Resources;

use App\Enums\Test\TestCompletionStatus;
use App\Enums\User\UserRole;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @OA\Schema(schema="TestShortResource", description="Тест", properties={
 *      @OA\Property(property="id", type="string", format="uuid", description="ID теста"),
 *      @OA\Property(property="name", type="string", description="Название теста"),
 *      @OA\Property(property="assigner", type="object", description="Пользователь, назначивший тест", ref="#/components/schemas/User"),
 *      @OA\Property(property="startDate", type="datetime", description="Дата начала"),
 *      @OA\Property(property="endDate", type="datetime", description="Дата окончания"),
 *      @OA\Property(property="completionStatus", type="string", description="Статус прохождения теста", ref="#/components/schemas/TestCompletionStatus"),
 *      @OA\Property(property="isAnonymous", type="bool", description="Метка анонимности теста"),
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
        $isAdmin = in_array($user->role, UserRole::adminRoles());
        $userTestCompletionStatus = !$isAdmin
            ? $this->assignedUsers()->where(['id' => $user->id])->first()->pivot->completion_status
            : null;

        return [
            'id' => $this->id,
            'name' => $this->name,
            'assigner' => UserResource::make($this->assigners()->first()),
            'startDate' => $this->start_date,
            'endDate' => $this->end_date,
            'completionStatus' => !$isAdmin ? TestCompletionStatus::getLabelFromValue($userTestCompletionStatus) : null,
            'isAnonymous' => $this->is_anonymous,
        ];
    }
}
