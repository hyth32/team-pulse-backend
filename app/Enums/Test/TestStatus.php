<?php

namespace App\Enums\Test;

use App\Enums\EnumTrait;

/**
 * @OA\Schema(
 *      schema="TestStatus",
 *      type="string",
 *      description="Статус теста
 *          draft - Черновик
 *          done - Готовый тест",
 *      example="draft"
 * )
 */
enum TestStatus
{
    use EnumTrait;

    case Draft;
    case Done;

    public function value(): ?string
    {
        return match($this) {
            self::Draft => 0,
            self::Done => 1,
        };
    }

    public function label(): ?string
    {
        return match($this) {
            self::Draft => 'draft',
            self::Done => 'done',
        };
    }
}
