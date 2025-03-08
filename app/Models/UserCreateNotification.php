<?php

namespace App\Models;

/**
 * @OA\Schema(schema="UserNotification", description="Уведомление пользователя о создании аккаунта", properties={
 *      @OA\Property(property="is_notified", type="integer", description="Метка уведомления", ref="#/components/schemas/UserNotificationStatus"),
 *      @OA\Property(property="user_id", type="integer", description="ID пользователя"),
 * })
 */
class UserCreateNotification extends BaseModel
{
    protected $dates = [
        'created_at',
        'updated_at',
    ];

    protected $fillable = [
        'is_notified',
        'user_id',
    ];
}
