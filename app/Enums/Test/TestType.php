<?php

namespace App\Enums\Test;

use App\Enums\EnumTrait;

/**
 * @OA\Schema(
 *     schema="TestType",
 *     type="integer",
 *     description="Типы тестов
 *     0 - Готовый
 *     1 - Опрос
 *     2 - 360",
 *     example=0
 * )
 */
enum TestType
{
    use EnumTrait;

    case Prepared;
    case Survey;
    case Overall;

    public function value(): ?int
    {
        return match($this) {
            self::Prepared => 0,
            self::Survey => 1,
            self::Overall => 2,
        };
    }

    public function label(): ?string
    {
        return match($this) {
            self::Prepared => 'Готовый',
            self::Survey => 'Опрос',
            self::Overall => '360',
        };
    }
}
