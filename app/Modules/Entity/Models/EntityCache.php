<?php

namespace App\Modules\Entity\Models;

use Illuminate\Database\Eloquent\Model;

class EntityCache extends Model
{
    protected $table = 'entity_caches';

    protected $fillable = [
        'document',
        'partner_id',
        'source',
        'payload',
        'fetched_at',
    ];

    protected function casts(): array
    {
        return [
            'partner_id' => 'integer',
            'payload' => 'array',
            'fetched_at' => 'datetime',
        ];
    }
}
