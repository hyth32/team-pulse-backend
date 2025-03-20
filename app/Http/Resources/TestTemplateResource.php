<?php

namespace App\Http\Resources;

use App\Enums\Test\TestStatus;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @OA\Schema(schema="TestTemplateResource", description="Шаблон теста", properties={
 *      @OA\Property(property="id", type="string", format="uuid", description="ID теста"),
 *      @OA\Property(property="name", type="string", description="Название теста"),
 *      @OA\Property(property="status", type="integer", description="Статус теста", ref="#/components/schemas/TestStatus"),
 *      @OA\Property(property="author", type="object", description="Автор теста", ref="#/components/schemas/User"),
 *      @OA\Property(property="createdAt", type="string", description="Дата создания"),
 *      @OA\Property(property="updatedAt", type="string", description="Дата обновления"),
 * })
 */
class TestTemplateResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $user = $request->user();
        $isAdmin = $user->isAdmin();

        return [
            'id' => $this->id,
            'name' => $this->name,
            'description' => $this->description,
            'topics' => !$isAdmin ? TopicShortResource::collection($this->topics) : [],
            'status' => TestStatus::getLabelFromValue($this->test_status),
            // 'completionStatus' => $this->completionStatus,
            'author' => UserResource::make($this->author),
            'createdAt' => $this->created_at,
            'updatedAt' => $this->updated_at,
        ];
    }
}
