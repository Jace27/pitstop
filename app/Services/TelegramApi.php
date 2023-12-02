<?php
namespace App\Services;

use Illuminate\Support\Facades\Log;

class TelegramApi
{
    const PHOTO_ATTACHMENT = 'photo';

    private static $url = 'https://api.telegram.org/bot';

    public static function sendMessage(int $user_id, string $text, array $keyboard = [], array $attachments = [])
    {
        $params = [
            'parse_mode' => 'html',
            'chat_id' => $user_id,
            'disable_web_page_preview' => true,
        ];
        if (!empty($keyboard)) {
            $params['reply_markup'] = json_encode([
                'inline_keyboard' => self::formatKeyboard($keyboard)
            ]);
        }

        if (count($attachments) != 1) {
            $params['text'] = $text;
            $result = self::call('sendMessage', $params);
        }
        if (count($attachments) == 1) {
            $result = self::sendFile($user_id, $text, $attachments[0], $keyboard);
        } else if (count($attachments) > 1) {
            $result = [];
            foreach ($attachments as $attachment) {
                $result[] = self::sendFile($user_id, null, $attachment);
            }
        }

        return $result;
    }

    public static function sendFile(int $user_id, ?string $caption, string $attachment, array $keyboard = [])
    {
        $attachment = config('app.external_url') . '/' . trim($attachment, "/\\");
        $params = [
            'parse_mode' => 'html',
            'chat_id' => $user_id,
            'caption' => $caption,
            self::PHOTO_ATTACHMENT => $attachment,
            'disable_web_page_preview' => true,
        ];
        if (!empty($keyboard)) {
            $params['reply_markup'] = json_encode([
                'inline_keyboard' => self::formatKeyboard($keyboard)
            ]);
        }
        return self::call('sendPhoto', $params);
    }

    public static function checkButton(int $chat_id, int $message_id, array $keyboard, string $data, string $message_text = null)
    {
        foreach ($keyboard['inline_keyboard'] as $key => $row) {
            foreach ($row as $rkey => $button) {
                if ($button['callback_data'] == $data) {
                    $keyboard['inline_keyboard'][$key][$rkey]['text'] = '✅ '.$button['text'];
                }
            }
        }

        self::editKeyboard($chat_id, $message_id, $keyboard);
    }

    public static function uncheckButton(int $chat_id, int $message_id, array $keyboard, string $data, string $message_text = null)
    {
        foreach ($keyboard['inline_keyboard'] as $key => $row) {
            foreach ($row as $rkey => $button) {
                if ($button['callback_data'] != $data) continue;
                if (mb_strpos($button['text'], '✅') !== 0) continue;
                $keyboard['inline_keyboard'][$key][$rkey]['text'] = explode('✅ ', $button['text'])[1];
            }
        }

        self::editKeyboard($chat_id, $message_id, $keyboard);
    }

    public static function removeKeyboard(int $chat_id, int $message_id, string $message_text = null)
    {
        self::editKeyboard($chat_id, $message_id, []);
    }

    public static function editKeyboard(int $chat_id, int $message_id, array $keyboard, string $message_text = null)
    {
        $params = [
            'chat_id' => $chat_id,
            'message_id' => $message_id,
            'reply_markup' => !empty($keyboard) ? json_encode($keyboard) : '{"inline_keyboard":[]}',
        ];

        self::call('editMessageReplyMarkup', $params);
    }

    public static function setWebhook()
    {
        return self::call('setWebhook', ['url' => config('app.external_url').'/']);
    }

    public static function call(string $method, array $params)
    {
        $token = config('external_api.telegram.bot.token');

        $curl = curl_init(self::$url.$token.'/'.$method);
        curl_setopt($curl, CURLOPT_POST, true);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $params);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        $json = curl_exec($curl);
        curl_close($curl);

        $json = json_decode($json, true);
        if (!$json['ok']) {
            Log::error(json_encode([
                'request' => [
                    'method' => $method,
                    'params' => $params,
                ],
                'answer' => $json,
            ]));
        }

        return $json;
    }

    public static function formatKeyboard(array $actions, int $columns = 1)
    {
        $keyboard = [];
        $row = 0;
        foreach ($actions as $key => $action) {
            if (isset($keyboard[$row]) && count($keyboard[$row]) == $columns)
                $row++;
            if (!isset($keyboard[$row]))
                $keyboard[$row] = [];

            $keyboard[$row][] = [
                'text' => $action,
                'callback_data' => $key,
            ];
        }
        return $keyboard;
    }
}
