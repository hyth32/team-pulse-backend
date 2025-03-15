<?php

namespace App\Models;

/**
 * @OA\Schema(schema="UserNotification", description="Уведомление пользователя о создании аккаунта", properties={
 *      @OA\Property(property="is_notified", type="integer", description="Метка уведомления", ref="#/components/schemas/UserNotificationStatus"),
 *      @OA\Property(property="user_id", type="string", format="uuid", description="ID пользователя", example="123e4567-e89b-12d3-a456-426614174000"),
 * })
 */
class UserCreateNotification extends BaseModel
{
    protected $fillable = [
        'is_notified',
        'user_id',
    ];
}
