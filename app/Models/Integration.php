<?php

namespace App\Models;

use App\Enums\EntityType;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

/**
 * @property-read int $id
 * @property string $base_url
 * @property bool $has_auth
 * @property string $token_type
 * @property string $access_token
 * @property string $refresh_token
 * @property string $client_id
 * @property string $client_secret
 * @property array $mapping
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * @property-read Collection|RequestLog[] $requestLogs
 * @method static Integration create($attribute)
 * @method static Integration find($id)
 * @method static Integration first()
 * @method static Builder|Integration query()
 * @method static Builder|Integration where(...$args)
 *
 */
class Integration extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'enabled',
        'base_url',
        'has_auth',
        'token_type',
        'access_token',
        'refresh_token',
        'client_id',
        'client_secret',
    ];

    protected $casts = [
        'enabled' => 'boolean',
        'has_auth' => 'boolean',
    ];

    public function requestLogs()
    {
        return $this->hasMany(RequestLog::class);
    }
}
