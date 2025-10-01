<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Customer extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'phone',
        'email',
        'address',
        'vehicle_number',
        'vehicle_type',
        'total_spent',
        'visit_count'
    ];

    protected $casts = [
        'total_spent' => 'decimal:2',
        'visit_count' => 'integer'
    ];

    public function sales(): HasMany
    {
        return $this->hasMany(Sale::class);
    }

    public function bookings()
    {
        return Booking::where('customer_phone', $this->phone)->get();
    }

    public function updateStatistics()
    {
        $this->total_spent = $this->sales()->where('payment_status', 'paid')->sum('total_amount');
        $this->visit_count = $this->sales()->count();
        $this->save();
    }
}
