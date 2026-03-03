<?php

namespace App\Modules\Device\Models;

use App\Models\Tenant;
use App\Modules\Partner\Models\Partner;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Device extends Model
{
    use HasFactory;

    protected $fillable = [
        'tenant_id',
        'partner_id',
        'device_uuid',
        'name',
        'platform',
        'app_version',
        'ip_address',
        'connected_ip',
        'last_seen_at',
        'connected_at',
        'disconnected_at',
        'is_active',
        'metadata',
    ];

    protected function casts(): array
    {
        return [
            'last_seen_at' => 'datetime',
            'connected_at' => 'datetime',
            'disconnected_at' => 'datetime',
            'is_active' => 'boolean',
            'metadata' => 'array',
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
}
