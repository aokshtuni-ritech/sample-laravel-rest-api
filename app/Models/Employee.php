<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;

/**
 * @property-read int $id
 * @property string $internal_id
 * @property string $external_id
 * @property string $first_name
 * @property string $last_name
 * @property string $email
 * @property string $job_title
 * @property string $primary_phone
 * @property array $tags
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * @property int $user_id
 * @property-read Collection|RequestLog[] $requestLogs
 * @property-read User $user
 * @method static Employee create($attribute)
 * @method static Employee find($id)
 *
 */
class Employee extends Model
{
    use HasFactory;

    protected $fillable = [
        'internal_id',
        'external_id',
        'first_name',
        'last_name',
        'email',
        'job_title',
        'primary_phone',
        'tags',
        'user_id',
    ];

    protected $casts = [
        'tags' => 'array'
    ];

    public function requestLogs()
    {
        return $this->hasMany(RequestLog::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
