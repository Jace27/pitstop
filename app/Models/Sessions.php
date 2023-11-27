<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * App\Models\Sessions
 *
 * @property int $id
 * @property string $external_id
 * @property string|null $first_name
 * @property string|null $last_name
 * @property string|null $username
 * @property int $status
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @method static \Illuminate\Database\Eloquent\Builder|Sessions newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Sessions newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Sessions onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|Sessions query()
 * @method static \Illuminate\Database\Eloquent\Builder|Sessions whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Sessions whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Sessions whereExternalId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Sessions whereFirstName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Sessions whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Sessions whereLastName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Sessions whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Sessions whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Sessions whereUsername($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Sessions withTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|Sessions withoutTrashed()
 * @mixin \Eloquent
 */
class Sessions extends BaseModel
{
    use HasFactory, SoftDeletes;

    const STATUS_ACTIVE = 1;
    const STATUS_BANNED = 2;

    public static function getStatuses(): array
    {
        return [
            self::STATUS_ACTIVE => 'Активный',
            self::STATUS_BANNED => 'Заблокированный',
        ];
    }

    protected $table = 'sessions';
    public $timestamps = true;
    protected $fillable = [
        'external_id',
        'first_name',
        'last_name',
        'username',
        'status',
    ];
    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];
}
