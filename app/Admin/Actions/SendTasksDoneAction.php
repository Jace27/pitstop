<?php

namespace App\Admin\Actions;

use App\Models\BotMessages;
use App\Models\Sessions;
use App\Models\Tasks;
use Illuminate\Database\Eloquent\Model;

class SendTasksDoneAction extends \Encore\Admin\Actions\RowAction
{
    public $name = 'Send tasks done message';

    public function __construct()
    {
        $this->name = __('admin.Send tasks done message');
        parent::__construct();
    }

    public function handle(Sessions $user): \Encore\Admin\Actions\Response
    {
        $tasksCount = Tasks::query()->count();

        if ($tasksCount == $user->getTasksAnswersCount(true)) {
            $message = BotMessages::whereSlug(BotMessages::SLUG_ALL_TASKS_DONE_RIGHT)->first();
        } else if ($tasksCount == $user->getTasksAnswersCount()) {
            $message = BotMessages::whereSlug(BotMessages::SLUG_ALL_TASKS_DONE)->first();
        } else {
            return $this->response()->error(__('User hasn\'t done all tasks yet'))->refresh();
        }

        $message?->send($user->external_id);

        return $this->response()->success(__('Message sent'))->refresh();
    }
}
