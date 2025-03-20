<?php

namespace App\Enums\Test;

use App\Enums\EnumTrait;

/**
 * @OA\Schema(
 *      schema="TestCompletionStatus",
 *      type="string",
 *      description="Статус прохождения теста
 *          not_passed - Не пройден
 *          in_progress - Выполняется
 *          passed - Пройден,
 *          expired - Просрочен",
 *      example="not_passed"
 * )
 */
enum TestCompletionStatus
{
    use EnumTrait;

    case NotPassed;
    case InProgress;
    case Passed;
    case Expired;

    public function value(): ?string
    {
        return match ($this) {
            self::NotPassed => 0,
            self::InProgress => 1,
            self::Passed => 2,
            self::Expired => 3,
        };
    }

    public function label(): ?string
    {
        return match ($this) {
            self::NotPassed => 'not_passed',
            self::InProgress => 'in_progress',
            self::Passed => 'passed',
            self::Expired => 'expired',
        };
    }
}
