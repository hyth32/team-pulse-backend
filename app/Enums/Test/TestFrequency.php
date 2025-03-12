<?php

namespace App\Enums\Test;

use App\Enums\EnumTrait;

/**
 * @OA\Schema(
 *      schema="TestFrequency",
 *      type="integer",
 *      description="Статус теста
 *          0 - Каждый день
 *          1 - Каждую неделю
 *          2 - Каждый месяц
 *          3 - Каждые полгода
 *          4- Каждый год",
 *      example="0"
 * )
 */
enum TestFrequency
{
    use EnumTrait;

    case Daily;
    case Weekly;
    case Monthly;
    case HalfYearly;
    case Yearly;

    public function value(): ?string
    {
        return match($this) {
            self::Daily => 0,
            self::Weekly => 1,
            self::Monthly => 2,
            self::HalfYearly => 3,
            self::Yearly => 4,
        };
    }

    public function label(): ?string
    {
        return match($this) {
            self::Daily => 'Каждый день',
            self::Weekly => 'Каждую неделю',
            self::Monthly => 'Каждый месяц',
            self::HalfYearly => 'Каждые полгода',
            self::Yearly => 'Каждый год',
        };
    }
}