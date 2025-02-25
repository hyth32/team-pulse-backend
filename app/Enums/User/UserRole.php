<?php

namespace App\Enums\User;

use App\Enums\EnumTrait;

/**
 * @OA\Schema(
 *     schema="UserRole",
 *     type="integer",
 *     description="Роли пользователей
 *     0 - Администратор
 *     1 - Руководитель
 *     2 - Сотрудник",
 *     example=0
 * )
 */
enum UserRole
{
    use EnumTrait;

    case Admin;
    case Supervisor;
    case Employee;

    public function value(): ?string
    {
        return match($this) {
            self::Admin => 0,
            self::Supervisor => 1,
            self::Employee => 2,
        };
    }

    public function label(): ?string
    {
        return match($this) {
            self::Admin => 'Администратор',
            self::Supervisor => 'Руководитель',
            self::Employee => 'Сотрудник',
        };
    }
}