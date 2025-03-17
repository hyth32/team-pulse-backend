<?php

namespace App\Enums\Test;

use App\Enums\EnumTrait;

/**
 * @OA\Schema(
 *      schema="TestCompletionStatus",
 *      type="string",
 *      description="Статус прохождения теста
 *          not_passed - Не пройден
 *          passed - Пройден,
 *          expired - Просрочен",
 *      example="not_passed"
 * )
 */
enum TestCompletionStatus
{
    use EnumTrait;

    case NotPassed;
    case Passed;
    case Expired;

    public function value(): ?string
    {
        return match ($this) {
            self::NotPassed => 0,
            self::Passed => 1,
            self::Expired => 2,
        };
    }

    public function label(): ?string
    {
        return match ($this) {
            self::NotPassed => 'not_passed',
            self::Passed => 'passed',
            self::Expired => 'expired',
        };
    }
}
