<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;

class FinancialPendency extends Model
{
    protected $table = 'financial_pendencies';

    public $incrementing = false;

    protected $keyType = 'string';

    protected $fillable = [
        'id',
        'citizen_document',
        'property_registration',
        'description',
        'due_date',
        'amount',
        'currency',
        'status',
    ];

    protected function casts(): array
    {
        return [
            'due_date' => 'date',
            'amount' => 'decimal:2',
        ];
    }

    public static function filter(?string $document, ?string $registration): Collection
    {
        $doc = $document ? preg_replace('/\D/', '', $document) : null;
        $reg = $registration ? strtoupper($registration) : null;

        return self::query()
            ->when($doc, fn ($query) => $query->where('citizen_document', $doc))
            ->when($reg, fn ($query) => $query->whereRaw('UPPER(property_registration) = ?', [$reg]))
            ->orderBy('due_date')
            ->get();
    }
}
