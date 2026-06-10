<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ReportEntry extends Model
{
    use HasFactory;

    public const TYPES = ['stock', 'transaction', 'incident', 'other'];
    public const STATUSES = ['draft', 'submitted', 'reviewed'];

    protected $fillable = [
        'user_id',
        'title',
        'type',
        'status',
        'period_from',
        'period_to',
        'summary',
    ];

    protected function casts(): array
    {
        return [
            'period_from' => 'date',
            'period_to' => 'date',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function getTypeLabelAttribute(): string
    {
        return match ($this->type) {
            'stock' => 'Stok',
            'transaction' => 'Transaksi',
            'incident' => 'Kendala',
            default => 'Lainnya',
        };
    }

    public function getStatusLabelAttribute(): string
    {
        return match ($this->status) {
            'submitted' => 'Diajukan',
            'reviewed' => 'Ditinjau',
            default => 'Draft',
        };
    }
}
