<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class StockMovement extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_id',
        'type',
        'quantity',
        'stock_before',
        'stock_after',
        'unit_cost',
        'reference_type',
        'reference_id',
        'reason',
        'created_by'
    ];

    protected $casts = [
        'quantity' => 'integer',
        'stock_before' => 'integer',
        'stock_after' => 'integer',
        'unit_cost' => 'decimal:2'
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($movement) {
            $product = $movement->product;
            $movement->stock_before = $product->stock_quantity;

            if ($movement->type === 'in') {
                $movement->stock_after = $product->stock_quantity + $movement->quantity;
            } elseif ($movement->type === 'out') {
                $movement->stock_after = $product->stock_quantity - $movement->quantity;
            } elseif ($movement->type === 'adjustment') {
                $movement->stock_after = $movement->quantity;
            }
        });

        static::created(function ($movement) {
            $movement->product->update(['stock_quantity' => $movement->stock_after]);
        });
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public function createdBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function reference()
    {
        if ($this->reference_type && $this->reference_id) {
            return $this->reference_type::find($this->reference_id);
        }
        return null;
    }

    public function scopeStockIn($query)
    {
        return $query->where('type', 'in');
    }

    public function scopeStockOut($query)
    {
        return $query->where('type', 'out');
    }

    public function scopeToday($query)
    {
        return $query->whereDate('created_at', today());
    }
}
