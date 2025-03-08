<?php

namespace App\Enums\User;

use App\Enums\EnumTrait;

/**
 * @OA\Schema(
 *      schema="UserNotificationStatus",
 *      type="integer",
 *      description="Статус уведомления
 *      0 - Не уведомлен
 *      1 - Уведомлен",
 *      example=0
 * )
 */
enum UserNotificationStatus
{
    use EnumTrait;

    case NotNotified;
    case Notified;

    public function value(): ?string
    {
        return match($this) {
            self::NotNotified => 0,
            self::Notified => 1,
        };
    }

    public function label(): ?string
    {
        return match($this) {
            self::NotNotified => 'Не оповещен',
            self::NotNotified => 'Оповещен',
        };
    }
}
