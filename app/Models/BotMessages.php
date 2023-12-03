<?php

namespace App\Models;

use App\Services\TelegramApi;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

/**
 * App\Models\BotMessages
 *
 * @property int $id
 * @property string|null $slug
 * @property string|null $title
 * @property int|null $task_id
 * @property string|null $text_content
 * @property string|null $external_url
 * @property string|null $image
 * @property boolean $wait_answer
 * @property int|null $next_message_id
 * @property int|null $prev_message_id
 * @property int|null $wait_time
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Tasks|null $task
 * @property-read \App\Models\BotMessages|null $next_message
 * @property-read \App\Models\BotMessages|null $prev_message
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Actions> $actions
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Actions> $prev_actions
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\BotMessages> $next_messages
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\BotMessages> $prev_messages
 * @method static \Illuminate\Database\Eloquent\Builder|BotMessages newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|BotMessages newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|BotMessages query()
 * @method static \Illuminate\Database\Eloquent\Builder|BotMessages whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BotMessages whereExternalUrl($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BotMessages whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BotMessages whereSlug($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BotMessages whereTaskId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BotMessages whereTextContent($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BotMessages whereTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BotMessages whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BotMessages whereWaitAnswer($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BotMessages whereWaitTime($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BotMessages wherePrevMessageId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BotMessages whereNextMessageId($value)
 * @mixin \Eloquent
 */
class BotMessages extends BaseModel
{
    use HasFactory;

    const SLUG_ERROR = 'error';
    const SLUG_START = 'start';
    const SLUG_ALL_TASKS_DONE = 'all_tasks_done';
    const SLUG_ALL_TASKS_DONE_RIGHT = 'all_tasks_done_right';

    public static function getSlugs(): array
    {
        $slugs = [
            self::SLUG_ERROR => 'Ошибка',
            self::SLUG_START => 'Старт бота',
            self::SLUG_ALL_TASKS_DONE => 'Все задания выполнены',
            self::SLUG_ALL_TASKS_DONE_RIGHT => 'Все задания выполнены верно',
        ];
        foreach (Tasks::query()->get() as $task) {
            $slugs['task_'.$task->number.'_start'] = 'Старт задания '.$task->number;
        }
        return $slugs;
    }

    protected $table = 'bot_messages';
    public $timestamps = true;
    protected $fillable = [
        'slug',
        'title',
        'task_id',
        'text_content',
        'external_url',
        'image',
        'wait_answer',
        'next_message_id',
        'prev_message_id',
        'wait_time',
    ];
    protected $dates = [
        'created_at',
        'updated_at',
    ];

    public function task(): HasOne
    {
        return $this->hasOne(Tasks::class, 'id', 'task_id');
    }

    public function actions(): HasMany
    {
        return $this->hasMany(Actions::class, 'message_id', 'id');
    }

    public function prev_actions(): HasMany
    {
        return $this->hasMany(Actions::class, 'next_message_id', 'id');
    }

    public function prev_message(): HasOne
    {
        return $this->hasOne(BotMessages::class, 'id', 'prev_message_id');
    }

    public function next_messages(): HasMany
    {
        return $this->hasMany(BotMessages::class, 'prev_message_id', 'id');
    }

    public function next_message(): HasOne
    {
        return $this->hasOne(BotMessages::class, 'id', 'next_message_id');
    }

    public function prev_messages(): HasMany
    {
        return $this->hasMany(BotMessages::class, 'next_message_id', 'id');
    }

    public function getImageUrl(): string
    {
        return '/storage/'.$this->image;
    }

    public function send(int $external_id)
    {
        $text = '';
        $actions = [];
        $attachments = [];

        $textFields = [];
        if (!is_null($this->external_url))
            $textFields[] = $this->external_url;
        $textFields[] = $this->text_content;
        $text = implode("\n\n", $textFields);

        foreach ($this->actions as $action) {
            $actions[$action->id] = $action->text;
        }

        if (!is_null($this->image)) {
            $attachments[] = $this->getImageUrl();
        }

        foreach ($this->next_messages as $message) {
            $delayedMessage = new DelayedMessages();
            $delayedMessage->user_id = Sessions::whereExternalId($external_id)->first()->id;
            $delayedMessage->message_id = $message->id;
            $delayedMessage->send_at = date('Y-m-d H:i:s', time() + ($message->wait_time ?? 0) * 60);
            $delayedMessage->save();
        }

        TelegramApi::sendMessage($external_id, $text, $actions, $attachments);
    }
}
