<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

/**
 * App\Models\UserAnswers
 *
 * @property int $id
 * @property int $user_id
 * @property int $message_id
 * @property string $answer
 * @property int|null $correct
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\BotMessages|null $message
 * @property-read \App\Models\Sessions|null $user
 * @method static \Illuminate\Database\Eloquent\Builder|UserAnswers newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|UserAnswers newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|UserAnswers query()
 * @method static \Illuminate\Database\Eloquent\Builder|UserAnswers whereAnswer($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserAnswers whereCorrect($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserAnswers whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserAnswers whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserAnswers whereMessageId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserAnswers whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserAnswers whereUserId($value)
 */
class UserAnswers extends Model
{
    use HasFactory;

    protected $table = 'user_answers';
    public $timestamps = true;
    protected $fillable = [
        'user_id',
        'message_id',
        'answer',
        'correct',
    ];
    protected array $dates = [
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
