<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Supplier extends Model
{
    use HasFactory;

    protected $fillable = [
        'code',
        'name',
        'contact_person',
        'phone',
        'email',
        'address',
        'lead_time_days',
        'is_active'
    ];

    protected $casts = [
        'lead_time_days' => 'integer',
        'is_active' => 'boolean'
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($supplier) {
            if (empty($supplier->code)) {
                $supplier->code = 'SUP' . str_pad(Supplier::count() + 1, 4, '0', STR_PAD_LEFT);
            }
        });
    }

    public function purchaseOrders(): HasMany
    {
        return $this->hasMany(PurchaseOrder::class);
    }

    public function products(): BelongsToMany
    {
        return $this->belongsToMany(Product::class, 'product_suppliers')
            ->withPivot('supplier_price', 'min_order_qty', 'is_primary')
            ->withTimestamps();
    }

    public function primaryProducts()
    {
        return $this->products()->wherePivot('is_primary', true);
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}
