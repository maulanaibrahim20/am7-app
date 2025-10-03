<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\{BelongsTo, MorphTo};

class SaleItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'sale_id',
        'saleable_type',
        'saleable_id',
        'item_name',
        'quantity',
        'unit_price',
        'discount_percent',
        'discount_amount',
        'subtotal',
        'mechanic_id'
    ];

    protected $casts = [
        'quantity' => 'integer',
        'unit_price' => 'decimal:2',
        'discount_percent' => 'decimal:2',
        'discount_amount' => 'decimal:2',
        'subtotal' => 'decimal:2'
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($item) {
            $item->calculateSubtotal();
        });

        static::updating(function ($item) {
            $item->calculateSubtotal();
        });
    }

    public function sale(): BelongsTo
    {
        return $this->belongsTo(Sale::class);
    }

    public function saleable(): MorphTo
    {
        return $this->morphTo();
    }

    public function mechanic(): BelongsTo
    {
        return $this->belongsTo(User::class, 'mechanic_id');
    }

    public function calculateSubtotal()
    {
        $total = $this->quantity * $this->unit_price;

        if ($this->discount_percent > 0) {
            $this->discount_amount = $total * ($this->discount_percent / 100);
        }

        $this->subtotal = $total - $this->discount_amount;
    }

    public function isProduct(): bool
    {
        return $this->saleable_type === Product::class;
    }

    public function isService(): bool
    {
        return $this->saleable_type === Service::class;
    }
}
