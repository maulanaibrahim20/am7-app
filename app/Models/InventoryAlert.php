<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Auth;

class InventoryAlert extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_id',
        'alert_type',
        'message',
        'is_resolved',
        'resolved_at',
        'resolved_by',
    ];

    protected $casts = [
        'is_resolved' => 'boolean',
        'resolved_at' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public function resolvedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'resolved_by');
    }

    public function resolve(): void
    {
        $this->update([
            'is_resolved' => true,
            'resolved_at' => now(),
            'resolved_by' => Auth::id()
        ]);
    }

    public function scopeUnresolved($query)
    {
        return $query->where('is_resolved', false);
    }

    public function scopeResolved($query)
    {
        return $query->where('is_resolved', true);
    }

    public function scopeByType($query, $type)
    {
        return $query->where('alert_type', $type);
    }

    public function scopeForProduct($query, int $productId)
    {
        return $query->where('product_id', $productId);
    }
    public function markAsResolved(?int $userId = null): bool
    {
        return $this->update([
            'is_resolved' => true,
            'resolved_at' => now(),
            'resolved_by' => $userId ?? Auth::id(),
        ]);
    }

    public function getTypeBadgeColorAttribute(): string
    {
        return match ($this->alert_type) {
            'low_stock' => 'danger',
            'reorder_point' => 'warning',
            'max_stock' => 'info',
            'expiry' => 'secondary',
            default => 'secondary'
        };
    }

    public function getTypeLabelAttribute(): string
    {
        return match ($this->alert_type) {
            'low_stock' => 'Low Stock',
            'reorder_point' => 'Reorder Point',
            'max_stock' => 'Overstock',
            'expiry' => 'Expiry',
            default => 'Unknown'
        };
    }

    public function getStatusBadgeAttribute(): string
    {
        if ($this->is_resolved) {
            return '<span class="badge bg-success">Resolved</span>';
        }
        return '<span class="badge bg-' . $this->type_badge_color . '">Unresolved</span>';
    }
}
