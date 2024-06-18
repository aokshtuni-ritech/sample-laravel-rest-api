<?php

namespace App\Models;

use App\Enums\RequestType;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

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
