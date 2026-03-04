<?php

namespace App\Modules\Device\Models;

use App\Models\Tenant;
use App\Modules\Partner\Models\Partner;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Device extends Model
{
    use HasFactory;

    protected $fillable = [
        'tenant_id',
        'partner_id',
        'device_uuid',
        'device_type',
        'device_name',
        'name',
        'platform',
        'app_version',
        'browser_name',
        'browser_version',
        'specifications',
        'ip_address',
        'connected_ip',
        'last_seen_at',
        'connected_at',
        'disconnected_at',
        'is_active',
        'is_blocked',
        'blocked_reason',
        'blocked_at',
        'metadata',
    ];

    protected function casts(): array
    {
        return [
            'tenant_id' => 'integer',
            'partner_id' => 'integer',
            'last_seen_at' => 'datetime',
            'connected_at' => 'datetime',
            'disconnected_at' => 'datetime',
            'blocked_at' => 'datetime',
            'is_active' => 'boolean',
            'is_blocked' => 'boolean',
            'metadata' => 'array',
            'specifications' => 'array',
        ];
    }

    public function tenant(): BelongsTo
    {
        return $this->belongsTo(Tenant::class);
    }

    public function partner(): BelongsTo
    {
        return $this->belongsTo(Partner::class);
    }

    public function connectionLogs(): HasMany
    {
        return $this->hasMany(DeviceConnectionLog::class);
    }
}
