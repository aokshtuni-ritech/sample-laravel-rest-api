<?php

namespace App\Models;

use App\Enums\EntityType;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;

/**
 * @property-read int $id
 * @property int $user_id
 * @property int $integration_id
 * @property EntityType $entity_type
 * @property array $mapping
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * @property-read User $user
 * @property-read Integration $integration
 * @method static EntityMapping create($attribute)
 * @method static EntityMapping find($id)
 *
 */
class EntityMapping extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'integration_id',
        'entity_type',
        'mapping',
    ];

    protected function casts(): array
    {
        return [
            'mapping' => 'array',
            'entity_type' => EntityType::class,
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function integration(): BelongsTo
    {
        return $this->belongsTo(Integration::class);
    }
}
