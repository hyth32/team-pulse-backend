<?php

namespace App\Enums;

enum EntityStatus
{
    use EnumTrait;

    case Deleted;
    case Active;

    public function value(): ?string
    {
        return match($this) {
            self::Deleted => 0,
            self::Active => 1,
        };
    }

    public function label(): ?string
    {
        return match($this) {
            self::Deleted => 'Удален',
            self::Active => 'Активен',
        };
    }
}
