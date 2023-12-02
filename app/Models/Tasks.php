<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * App\Models\Tasks
 *
 * @property int $id
 * @property int $number
 * @property string $title
 * @property int $enabled
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\BotMessages> $messages
 * @property-read int|null $messages_count
 * @method static \Illuminate\Database\Eloquent\Builder|Tasks newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Tasks newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Tasks onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|Tasks query()
 * @method static \Illuminate\Database\Eloquent\Builder|Tasks whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Tasks whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Tasks whereEnabled($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Tasks whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Tasks whereNumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Tasks whereTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Tasks whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Tasks withTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|Tasks withoutTrashed()
 * @mixin \Eloquent
 */
class Tasks extends BaseModel
{
    use HasFactory, SoftDeletes;

    protected $table = 'tasks';
    public $timestamps = true;
    protected $fillable = [
        'number',
        'title',
        'enabled',
    ];
    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    public function messages(): HasMany
    {
        return $this->hasMany(BotMessages::class, 'task_id', 'id');
    }

    public function getStartMessage(): ?BotMessages
    {
        $slug = 'task_'.$this->number.'_start';
        return BotMessages::whereSlug($slug)->first();
    }
}
