<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\{BelongsTo, BelongsToMany, HasOne};

class Booking extends Model
{
    use HasFactory;

    protected $fillable = [
        'booking_code',
        'customer_name',
        'customer_phone',
        'customer_email',
        'whatsapp_id',
        'vehicle_type',
        'vehicle_number',
        'problem_description',
        'booking_date',
        'booking_time',
        'status',
        'approved_by',
        'mechanic_id',
        'admin_notes',
        'mechanic_notes',
        'approved_at',
        'started_at',
        'completed_at'
    ];

    protected $casts = [
        'booking_date' => 'date',
        'booking_time' => 'datetime:H:i',
        'approved_at' => 'datetime',
        'started_at' => 'datetime',
        'completed_at' => 'datetime'
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($booking) {
            $booking->booking_code = 'BK' . date('Ymd') . str_pad(rand(1, 9999), 4, '0', STR_PAD_LEFT);
        });
    }

    public function approvedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    public function mechanic(): BelongsTo
    {
        return $this->belongsTo(User::class, 'mechanic_id');
    }

    public function services(): BelongsToMany
    {
        return $this->belongsToMany(Service::class, 'booking_services')
            ->withPivot('estimated_price')
            ->withTimestamps();
    }

    public function sale(): HasOne
    {
        return $this->hasOne(Sale::class);
    }

    // Get or create customer based on phone
    public function getCustomer()
    {
        return Customer::firstOrCreate(
            ['phone' => $this->customer_phone],
            [
                'name' => $this->customer_name,
                'email' => $this->customer_email,
                'vehicle_number' => $this->vehicle_number,
                'vehicle_type' => $this->vehicle_type
            ]
        );
    }

    public function getEstimatedTotalAttribute(): float
    {
        return $this->services()->sum('booking_services.estimated_price');
    }

    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopeApproved($query)
    {
        return $query->where('status', 'approved');
    }

    public function scopeToday($query)
    {
        return $query->whereDate('booking_date', today());
    }

    public function scopeUpcoming($query)
    {
        return $query->whereDate('booking_date', '>=', today())
            ->where('status', 'approved');
    }
}
