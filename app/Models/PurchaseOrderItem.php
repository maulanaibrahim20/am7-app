<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Auth;

class PurchaseOrderItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'purchase_order_id',
        'product_id',
        'quantity_ordered',
        'quantity_received',
        'unit_price',
        'subtotal'
    ];

    protected $casts = [
        'quantity_ordered' => 'integer',
        'quantity_received' => 'integer',
        'unit_price' => 'decimal:2',
        'subtotal' => 'decimal:2'
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($item) {
            $item->subtotal = $item->quantity_ordered * $item->unit_price;
        });

        static::updating(function ($item) {
            $item->subtotal = $item->quantity_ordered * $item->unit_price;
        });
    }

    public function purchaseOrder(): BelongsTo
    {
        return $this->belongsTo(PurchaseOrder::class);
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public function isFullyReceived(): bool
    {
        return $this->quantity_received >= $this->quantity_ordered;
    }

    public function getRemainingQuantityAttribute(): int
    {
        return max(0, $this->quantity_ordered - $this->quantity_received);
    }

    public function receive(int $quantity): void
    {
        $this->quantity_received += $quantity;
        $this->save();

        // Create stock movement
        StockMovement::create([
            'product_id' => $this->product_id,
            'type' => 'in',
            'quantity' => $quantity,
            'unit_cost' => $this->unit_price,
            'reference_type' => PurchaseOrder::class,
            'reference_id' => $this->purchase_order_id,
            'reason' => 'Purchase Order #' . $this->purchaseOrder->po_number,
            'created_by' => Auth::id()
        ]);
    }
}
