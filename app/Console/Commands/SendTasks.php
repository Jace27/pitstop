<?php

namespace App\Console\Commands;

use App\Models\BotMessages;
use App\Models\Sessions;
use App\Models\Tasks;
use App\Models\UserAnswers;
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

                if ($taskNumber > 0) {
                    $task = Tasks::whereNumber($taskNumber)->first();
                    $questions = BotMessages::whereTaskId($task?->id)->whereWaitAnswer(true)->get();
                    $taskDone = true;
                    foreach ($questions as $question) {
                        if (!UserAnswers::whereUserId($user->id)->whereMessageId($question->id)->exists()) {
                            $taskDone = false;
                        }
                    }
                    if (!$taskDone) continue;
                }

                $taskNumber++;
                $task = Tasks::whereNumber($taskNumber)->first();
                if (is_null($task)) continue;

                $message = $task->getStartMessage();
                if (is_null($message)) continue;

                $message->send($user->external_id);

                $user->getJsonData()->last_task_id = $task->id;
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
