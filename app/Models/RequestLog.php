<?php

namespace App\Models;

use App\Enums\RequestType;
use App\Enums\UserRole;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;

/**
 * @property-read int $id
 * @property int $integration_id
 * @property int $employee_id
 * @property RequestType $type
 * @property string $uri
 * @property string $status
 * @property array $payload
 * @property string $response
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * @property-read Employee $employee
 * @property-read Integration $integration
 * @method static RequestLog create($attribute)
 * @method static RequestLog find($id)
 * @method static RequestLog first()
 * @method static Builder|RequestLog query()
 * @method static Builder|RequestLog where(...$args)
 *
 */
class RequestLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'integration_id',
        'employee_id',
        'type',
        'uri',
        'status',
        'payload',
        'response',
    ];

    protected function casts(): array
    {
        return [
            'payload' => 'array',
            'type' => RequestType::class,
        ];
    }

    public function employee(): BelongsTo
    {
        return $this->belongsTo(Employee::class);
    }

    public function integration(): BelongsTo
    {
        return $this->belongsTo(Integration::class);
    }
}
