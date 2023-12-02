<?php

namespace App\Console\Commands;

use App\Models\BotMessages;
use App\Models\Sessions;
use App\Models\Tasks;
use Illuminate\Console\Command;

class SendTasks extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:send-tasks';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send tasks';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $sent = 0;
        foreach (Sessions::whereStatus(Sessions::STATUS_ACTIVE)->get() as $user) {
            $currentMessage = BotMessages::whereId($user->getJsonData()->message_id)->first();
            if (is_null($currentMessage)) continue;

            $taskNumber = $currentMessage->task?->number ?? 1;
            $task = Tasks::whereNumber($taskNumber)->first();
            if (is_null($task)) continue;

            $message = $task->getStartMessage();
            if (is_null($message)) continue;
            $message->send($user->external_id);

            $user->getJsonData()->message_id = $message->id;
            $user->save();

            $sent++;
        }
        echo 'Sent '.$sent.' messages';
    }
}
