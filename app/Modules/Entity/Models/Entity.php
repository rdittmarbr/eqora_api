<?php

namespace App\Modules\Entity\Models;

use Illuminate\Database\Eloquent\Model;

class Entity extends Model
{
    protected $table = 'entities';

    protected $fillable = [
        'document',
        'type',
        'name',
        'email',
        'phone',
        'rg',
        'address',
    ];

    protected function casts(): array
    {
        return [
            'address' => 'array',
        ];
    }

    public static function findByDocument(string $document): ?array
    {
        $normalized = preg_replace('/\D/', '', $document);

        if (!$normalized) {
            return null;
        }

        /** @var self|null $record */
        $record = self::query()
            ->where('document', $normalized)
            ->first();

        if (!$record) {
            return null;
        }

        return [
            'document' => (string) $record->document,
            'type' => (string) $record->type,
            'name' => (string) $record->name,
            'email' => (string) $record->email,
            'phone' => (string) $record->phone,
            'rg' => $record->rg,
            'address' => is_array($record->address) ? $record->address : [],
            'updated_at' => $record->updated_at?->toIso8601String(),
        ];
    }
}
