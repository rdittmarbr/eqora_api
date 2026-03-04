<?php

namespace App\Modules\Device\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DeviceConnectionLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'device_id',
        'tenant_id',
        'partner_id',
        'event',
        'ip_address',
        'is_active',
        'payload',
    ];

    protected function casts(): array
    {
        return [
            'tenant_id' => 'integer',
            'partner_id' => 'integer',
            'is_active' => 'boolean',
            'payload' => 'array',
        ];
    }

    public function device(): BelongsTo
    {
        return $this->belongsTo(Device::class);
    }
}
