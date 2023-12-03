<?php

namespace App\Console\Commands;

use App\Models\BotMessages;
use App\Models\Sessions;
use App\Models\Tasks;
use App\Services\Logger;
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
        foreach (Sessions::whereStatus(Sessions::STATUS_ACTIVE)->where('is_admin', '=', false)->get() as $user) {
            try {
                $currentMessage = BotMessages::whereId($user->getJsonData()->message_id)->first();
                if (is_null($currentMessage)) continue;

                $taskNumber = ($user->getJsonData()->last_task_id ?? $currentMessage->task?->number) ?? 0;
                $taskNumber++;
                $task = Tasks::whereNumber($taskNumber)->first();
                if (is_null($task)) {
                    $tasksCount = Tasks::query()->count();
                    if ($tasksCount == $user->getAnswersCount(true)) {
                        $message = BotMessages::whereSlug(BotMessages::SLUG_ALL_TASKS_DONE_RIGHT)->first();
                    } else if ($tasksCount == $user->getAnswersCount()) {
                        $message = BotMessages::whereSlug(BotMessages::SLUG_ALL_TASKS_DONE)->first();
                    } else continue;
                    $message?->send($user->external_id);
                } else {
                    $message = $task->getStartMessage();
                    if (is_null($message)) continue;
                    $message->send($user->external_id);
                    $user->getJsonData()->last_task_id = $task->id;
                }
                $user->getJsonData()->message_id = $message->id;
                $user->save();
            } catch (\Throwable $ex) {
                Logger::error([
                    'message' => $ex->getMessage(),
                    'file' => $ex->getFile() . ':' . $ex->getLine(),
                    'trace' => $ex->getTraceAsString(),
                ]);
            }

            $sent++;
        }
        $echo = 'Sent ' . $sent . ' messages';
        Logger::log($echo);
        echo $echo;
    }
}
