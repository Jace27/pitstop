<?php

namespace App\Components;

use App\Components\Structures\BotInput;
use App\Components\Structures\BotOutput;
use App\Models\Actions;
use App\Models\BotMessages;
use App\Models\Sessions;
use App\Models\UserAnswers;
use App\Services\Logger;
use App\Services\Notifications;
use App\Services\TelegramApi;
use Illuminate\Http\Request;

class Bot
{
    public Request $request;
    public Sessions $user;
    public BotInput $input;
    public BotOutput $output;

    public function __construct(Request $request, Sessions $user, BotInput $input)
    {
        $this->request = $request;
        $this->user = $user;
        $this->input = $input;
        $this->output = new BotOutput();
    }

    public function handle(): BotMessages
    {
        $currentMessage = BotMessages::whereId($this->user->getJsonData()->message_id)->first();
        if (is_null($currentMessage))
            $currentMessage = BotMessages::whereSlug(BotMessages::SLUG_START)->firstOrFail();

        switch ($this->input->button) {
            case null:
                break;
            case 'start':
                $currentMessage = BotMessages::whereSlug(BotMessages::SLUG_START)->firstOrFail();
                break;
            default:
                /** @var Actions $action */
                $action = $currentMessage->actions()->where(['id' => $this->input->button])->first();
                if (!is_null($action)) {
                    $currentMessage = $action->next_message ?? $currentMessage;
                }
        }

        if ($currentMessage->wait_answer && is_null($this->input->button) && !is_null($this->input->text)) {
            $answer = new UserAnswers();
            $answer->user_id = $this->user->id;
            $answer->message_id = $currentMessage->id;
            $answer->answer = trim($this->input->text);
            $answer->save();

            $currentMessage = $currentMessage->next_message ?? $currentMessage;

            Notifications::notificate(
                Notifications::NEW_ANSWER,
                [
                    '{{USER}}' => $answer->user->username,
                    '{{DATETIME}}' => date('H:i:s d.m.Y', strtotime($answer->created_at)),
                    '{{ANSWER}}' => $answer->answer,
                ]
            );
        }

        $this->user->getJsonData()->message_id = $currentMessage->id;
        $this->user->save();
        return $currentMessage;
    }
}
