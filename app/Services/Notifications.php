<?php

namespace App\Services;

use App\Models\Sessions;

class Notifications
{
    const NEW_ANSWER = 1;

    public static function getNotifications(): array
    {
        return [
            self::NEW_ANSWER => "Новый ответ на задание от пользователя {{USER}} от {{DATETIME}}:\n\n{{ANSWER}}",
        ];
    }

    public static function notificate(int $notification, array $params): void
    {
        $text = self::getNotifications()[$notification];
        foreach ($params as $param => $value) {
            $text = str_replace($param, $value, $text);
        }
        $admins = Sessions::whereStatus(Sessions::STATUS_ACTIVE)->where('is_admin', '=', true)->get();
        foreach ($admins as $admin) {
            TelegramApi::sendMessage($admin->external_id, $text);
        }
    }
}
