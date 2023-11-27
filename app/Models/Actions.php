<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

/**
 * App\Models\Actions
 *
 * @property int $id
 * @property string $text
 * @property int $message_id
 * @property int $next_message_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\BotMessages|null $message
 * @property-read \App\Models\BotMessages|null $next_message
 * @method static \Illuminate\Database\Eloquent\Builder|Actions newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Actions newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Actions query()
 * @method static \Illuminate\Database\Eloquent\Builder|Actions whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Actions whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Actions whereMessageId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Actions whereNextMessageId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Actions whereText($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Actions whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Actions extends BaseModel
{
    use HasFactory;

    protected $table = 'actions';
    public $timestamps = true;
    protected $fillable = [
        'text',
        'message_id',
        'next_message_id',
    ];
    protected $dates = [
        'created_at',
        'updated_at',
    ];

    public function message(): HasOne
    {
        return $this->hasOne(BotMessages::class, 'id', 'message_id');
    }

    public function next_message(): HasOne
    {
        return $this->hasOne(BotMessages::class, 'id', 'next_message_id');
    }
}
