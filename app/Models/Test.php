<?php

namespace App\Models;

use App\Models\BaseModel;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

/**
 * @OA\Schema(schema="Test", description="Тест", properties={
 *      @OA\Property(property="id", type="string", format="uuid", description="ID теста"),
 *      @OA\Property(property="name", type="string", description="Название теста"),
 *      @OA\Property(property="description", type="text", description="Описание теста"),
 *      @OA\Property(property="frequency", type="integer", ref="#/components/schemas/TestFrequency"),
 *      @OA\Property(property="start_date", type="datetime", description="Дата начала теста"),
 *      @OA\Property(property="end_date", type="datetime", description="Дата окончания теста"),
 *      @OA\Property(property="author_id", type="string", format="uuid", description="ID пользователя, создавшего тест", example="123e4567-e89b-12d3-a456-426614174000"),
*       @OA\Property(property="subject_id", type="string", format="uuid", description="ID пользователя, на оценку которого направлен тест"),
*       @OA\Property(property="is_anonymous", type="bool", description="Метка анонимности"),
 * })
 *
 * @OA\Schema(schema="TestView", description="Главная страница теста", properties={
 *      @OA\Property(property="id", type="string", format="uuid", description="ID теста"),
 *      @OA\Property(property="name", type="string", description="Название теста"),
 *      @OA\Property(property="description", type="text", description="Описание теста"),
 *      @OA\Property(property="testSubject", type="object", description="Пользователь, на оценку которого направлен тест"),
 *      @OA\Property(property="topics", type="array", @OA\Items(ref="#/components/schemas/Topic"))
 * })
 */
class Test extends BaseModel
{
    protected $dates = [
        'created_at',
        'updated_at',
        'start_date',
        'end_date',
    ];

    protected $fillable = [
        'name',
        'description',
        'type',
        'frequency',
        'start_date',
        'status',
        'end_date',
        'test_status',
        'author_id',
        'subject_id',
        'is_anonymous',
    ];

    public function assignedUsers(): BelongsToMany
    {
        return $this->belongsToMany(User::class, UserTest::class)->withPivot(['assigner_id', 'completion_status']);
    }

    public function assigners()
    {
        return $this->belongsToMany(User::class, UserTest::class, 'test_id', 'assigner_id')->distinct();
    }

    public function author(): BelongsTo
    {
        return $this->belongsTo(User::class, 'author_id');
    }

    public function subject(): HasOne
    {
        return $this->hasOne(User::class, 'id', 'subject_id');
    }

    public function questions(): BelongsToMany
    {
        return $this->belongsToMany(Question::class, TestQuestion::class, 'test_id', 'question_id');
    }

    public function topics()
    {
        return $this->belongsToMany(Topic::class, TestQuestion::class, 'test_id', 'topic_id')->distinct();
    }

    public function groups(): BelongsToMany
    {
        return $this->belongsToMany(Group::class, TestGroup::class);
    }
}
