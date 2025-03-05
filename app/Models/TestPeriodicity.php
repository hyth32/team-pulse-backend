<?php

namespace App\Models;

use App\Models\BaseModel;

/**
 * @OA\Schema(schema="TestPeriodicity", description="Периодичность теста", properties={
 *      @OA\Property(property="name", type="string", description="Название"),
 *      @OA\Property(property="timeframe", type="string", description="Временной интервал"),
 * })
 */
class TestPeriodicity extends BaseModel
{
    protected $dates = [
        'created_at',
        'updated_at',
    ];

    protected $fillable = [
        'name',
        'timeframe',
    ];
}
