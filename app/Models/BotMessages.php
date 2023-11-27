<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

/**
 * App\Models\BotMessages
 *
 * @property int $id
 * @property string|null $title
 * @property int|null $task_id
 * @property int $content_type
 * @property string|null $text_content
 * @property string|null $external_url
 * @property string|null $image
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Actions> $actions
 * @property-read int|null $actions_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Actions> $prev_actions
 * @property-read int|null $prev_actions_count
 * @property-read \App\Models\Tasks|null $task
 * @method static \Illuminate\Database\Eloquent\Builder|BotMessages newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|BotMessages newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|BotMessages query()
 * @method static \Illuminate\Database\Eloquent\Builder|BotMessages whereContentType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BotMessages whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BotMessages whereExternalUrl($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BotMessages whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BotMessages whereTaskId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BotMessages whereTextContent($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BotMessages whereTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BotMessages whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class BotMessages extends BaseModel
{
    use HasFactory;

    const CONTENT_TYPE_TEXT = 1;
    const CONTENT_TYPE_LINK = 2;
    const CONTENT_TYPE_IMAGE = 3;
    const CONTENT_TYPE_VIDEO = 4;

    public static function getContentTypes(): array
    {
        return [
            self::CONTENT_TYPE_TEXT     => 'Текст',
            self::CONTENT_TYPE_LINK     => 'Ссылка',
            self::CONTENT_TYPE_IMAGE    => 'Изображение',
            self::CONTENT_TYPE_VIDEO    => 'Видео',
        ];
    }

    protected $table = 'bot_messages';
    public $timestamps = true;
    protected $fillable = [
        'title',
        'task_id',
        'content_type',
        'text_content',
        'external_url',
        'image',
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

    public function getImageUrl(): string
    {
        return '/storage/'.$this->image;
    }
}
