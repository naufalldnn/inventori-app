<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Item extends Model
{
    use HasFactory;

    protected $fillable = [
        'category_id',
        'code',
        'name',
        'unit',
        'price',
        'stock',
        'minimum_stock',
        'description',
        'media_url',
        'media_public_id',
        'media_type',
    ];

    protected function casts(): array
    {
        return [
            'stock' => 'integer',
            'minimum_stock' => 'integer',
            'price' => 'decimal:2',
        ];
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function transactions(): HasMany
    {
        return $this->hasMany(StockTransaction::class);
    }

    public function getStockStatusAttribute(): string
    {
        if ($this->stock <= 0) {
            return 'habis';
        }

        if ($this->stock <= $this->minimum_stock) {
            return 'menipis';
        }

        return 'aman';
    }
}
