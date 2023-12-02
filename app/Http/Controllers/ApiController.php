<?php

namespace App\Http\Controllers;

use App\Components\Bot;
use App\Components\Structures\BotInput;
use App\Components\Structures\SessionData;
use App\Models\BotMessages;
use App\Models\Sessions;
use App\Services\Logger;
use App\Services\TelegramApi;
use Illuminate\Http\Request;

class ApiController extends Controller
{
    public function handle(Request $request): string
    {
        try {
            $json = json_decode($request->getContent(), true);
            $json = $json['message'] ?? $json['callback_query'];

            $inputText = $json['text'] ?? null;
            $inputButton = $json['data'] ?? null;

            $user = Sessions::query()->firstOrCreate(
                ['external_id' => $json['from']['id']],
                [
                    'external_id' => $json['from']['id'],
                    'first_name' => $json['from']['first_name'],
                    'last_name' => $json['from']['last_name'],
                    'username' => $json['from']['username'],
                    'data' => (new SessionData('[]'))->encode(),
                    'status' => Sessions::STATUS_ACTIVE,
                ]
            );

            if (!is_null($inputButton)) {
                TelegramApi::removeKeyboard($json['message']['chat']['id'], $json['message']['message_id']);
            }

            $bot = new Bot($request, $user, new BotInput($inputText, $inputButton));
            $message = $bot->handle();
            $message->send($user->external_id);
        } catch (\Throwable $ex) {
            Logger::error([
                'message' => $ex->getMessage(),
                'file' => $ex->getFile().':'.$ex->getLine(),
                'trace' => $ex->getTraceAsString(),
            ]);
            if (isset($user)) {
                $message = BotMessages::whereSlug(BotMessages::SLUG_ERROR)->first();
                $message->send($user->external_id);
            }
        }
        return 'ok';
    }
}
