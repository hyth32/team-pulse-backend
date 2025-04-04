<?php

namespace App\Enums\User;

use App\Enums\EnumTrait;

/**
 * @OA\Schema(
 *     schema="UserRole",
 *     type="integer",
 *     description="Роли пользователей
 *     employee - Сотрудник
 *     supervisor - Руководитель
 *     admin - Администратор",
 *     example=0
 * )
 */
enum UserRole
{
    use EnumTrait;

    case Employee;
    case Supervisor;
    case Admin;

    public function value(): ?string
    {
        return match($this) {
            self::Employee => 0,
            self::Supervisor => 1,
            self::Admin => 2,
        };
    }

    public function label(): ?string
    {
        return match($this) {
            self::Employee => 'employee',
            self::Supervisor => 'supervisor',
            self::Admin => 'admin',
        };
    }

    public static function adminRoles(): array
    {
        return [self::Admin->value(), self::Supervisor->value()];
    }
}
