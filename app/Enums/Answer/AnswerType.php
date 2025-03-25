<?php

namespace App\Enums\Answer;

use App\Enums\EnumTrait;

/**
 * @OA\Schema(
 *      schema="AnswerType",
 *      type="integer",
 *      description="Типы ответов
 *      0 - Текст
 *      1 - Одиночный выбор
 *      2 - Множественный выбор
 *      3 - Оценка
 *      example=0
 * )
 */
enum AnswerType
{
    use EnumTrait;

    case Text;
    case SingleChoice;
    case MultipleChoice;
    case Scale;

    public function value(): ?int
    {
        return match($this) {
            self::Text => 0,
            self::SingleChoice => 1,
            self::MultipleChoice => 2,
            self::Scale => 3,
        };
    }

    public function label(): ?string
    {
        return match($this) {
            self::Text => 'Текст',
            self::SingleChoice => 'Одиночный выбор',
            self::MultipleChoice => 'Множественный выбор',
            self::Scale => 'Оценка',
        };
    }
}
