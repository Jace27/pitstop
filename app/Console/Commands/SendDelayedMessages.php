<?php

namespace App\Console\Commands;

use App\Models\DelayedMessages;
use App\Services\Logger;
use Illuminate\Console\Command;

class SendDelayedMessages extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:send-delayed-messages';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send delayed messages';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $messages = DelayedMessages::query()
            ->where('send_at', '<=', date('Y-m-d H:i:s'))
            ->where('sent_at', '=', null)
            ->get();
        $sent = 0;
        foreach ($messages as $message) {
            $message->message->send($message->user->external_id);
            $message->sent_at = date('Y-m-d H:i:s');
            $message->save();
            $sent++;
        }
        $echo = 'Sent '.$sent.' messages';
        Logger::log($echo);
        echo $echo;
    }
}
