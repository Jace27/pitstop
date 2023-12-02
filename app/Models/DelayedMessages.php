<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

/**
 * App\Models\DelayedMessages
 *
 * @property int $id
 * @property int $user_id
 * @property int $message_id
 * @property string $send_at
 * @property string|null $sent_at
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\BotMessages|null $message
 * @property-read \App\Models\Sessions|null $user
 * @method static \Illuminate\Database\Eloquent\Builder|DelayedMessages newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|DelayedMessages newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|DelayedMessages query()
 * @method static \Illuminate\Database\Eloquent\Builder|DelayedMessages whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DelayedMessages whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DelayedMessages whereMessageId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DelayedMessages whereSendAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DelayedMessages whereSentAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DelayedMessages whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|DelayedMessages whereUserId($value)
 * @mixin \Eloquent
 */
class DelayedMessages extends Model
{
    use HasFactory;

    protected $table = 'delayed_messages';
    public $timestamps = true;
    protected $fillable = [
        'user_id',
        'message_id',
        'send_at',
        'sent_at',
    ];
    protected $dates = [
        'send_at',
        'sent_at',
        'created_at',
        'updated_at',
    ];

    public function user(): HasOne
    {
        return $this->hasOne(Sessions::class, 'id', 'user_id');
    }

    public function message(): HasOne
    {
        return $this->hasOne(BotMessages::class, 'id', 'message_id');
    }
}
