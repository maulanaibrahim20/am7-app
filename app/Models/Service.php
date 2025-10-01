<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\{BelongsTo, BelongsToMany, MorphMany};

class Service extends Model
{
    use HasFactory;

    protected $fillable = [
        'category_id',
        'code',
        'name',
        'description',
        'base_price',
        'estimated_duration',
        'vehicle_type',
        'is_active'
    ];

    protected $casts = [
        'base_price' => 'decimal:2',
        'estimated_duration' => 'integer',
        'is_active' => 'boolean'
    ];

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function bookings(): BelongsToMany
    {
        return $this->belongsToMany(Booking::class, 'booking_services')
            ->withPivot('estimated_price')
            ->withTimestamps();
    }

    // Polymorphic relation untuk sale items
    public function saleItems(): MorphMany
    {
        return $this->morphMany(SaleItem::class, 'saleable');
    }

    // Get duration in hours
    public function getDurationInHoursAttribute(): float
    {
        return round($this->estimated_duration / 60, 1);
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeForVehicle($query, $type)
    {
        return $query->where(function ($q) use ($type) {
            $q->where('vehicle_type', $type)
                ->orWhere('vehicle_type', 'both');
        });
    }
}
