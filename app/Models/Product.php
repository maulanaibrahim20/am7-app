<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'category_id',
        'sku',
        'name',
        'description',
        'unit',
        'purchase_price',
        'selling_price',
        'stock_quantity',
        'min_stock',
        'max_stock',
        'reorder_point',
        'reorder_quantity',
        'avg_daily_usage',
        'lead_time_days',
        'brand',
        'compatible_vehicles',
        'is_active'
    ];

    protected $casts = [
        'purchase_price' => 'decimal:2',
        'selling_price' => 'decimal:2',
        'avg_daily_usage' => 'decimal:2',
        'is_active' => 'boolean'
    ];

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function stockMovements(): HasMany
    {
        return $this->hasMany(StockMovement::class);
    }

    public function purchaseOrderItems(): HasMany
    {
        return $this->hasMany(PurchaseOrderItem::class);
    }

    public function inventoryAlerts(): HasMany
    {
        return $this->hasMany(InventoryAlert::class);
    }

    // public function inventorySnapshots(): HasMany
    // {
    //     return $this->hasMany(InventorySnapshot::class);
    // }

    public function suppliers(): BelongsToMany
    {
        return $this->belongsToMany(Supplier::class, 'product_suppliers')
            ->withPivot('supplier_price', 'min_order_qty', 'is_primary')
            ->withTimestamps();
    }

    public function primarySupplier()
    {
        return $this->suppliers()->wherePivot('is_primary', true)->first();
    }

    // Polymorphic relation untuk sale items
    public function saleItems(): MorphMany
    {
        return $this->morphMany(SaleItem::class, 'saleable');
    }

    // Check if need reorder
    public function needsReorder(): bool
    {
        return $this->stock_quantity <= $this->reorder_point;
    }

    // Check if low stock
    public function isLowStock(): bool
    {
        return $this->stock_quantity <= $this->min_stock;
    }

    // Check if over stock
    public function isOverStock(): bool
    {
        return $this->stock_quantity >= $this->max_stock;
    }

    // Calculate reorder point based on lead time and average usage
    public function calculateReorderPoint(): int
    {
        return ceil($this->avg_daily_usage * $this->lead_time_days);
    }

    // Update stock
    public function updateStock(int $quantity, string $type = 'out'): void
    {
        if ($type === 'in') {
            $this->stock_quantity += $quantity;
        } else {
            $this->stock_quantity -= $quantity;
        }
        $this->save();
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeLowStock($query)
    {
        return $query->whereRaw('stock_quantity <= min_stock');
    }

    public function scopeNeedReorder($query)
    {
        return $query->whereRaw('stock_quantity <= reorder_point');
    }
}
