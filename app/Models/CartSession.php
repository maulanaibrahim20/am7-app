<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CartSession extends Model
{
    use HasFactory;

    protected $fillable = [
        'session_code',
        'customer_id',
        'customer_name',
        'customer_phone',
        'booking_id',
        'cashier_id',
        'status',
        'notes',
        'hold_at',
        'expired_at',
    ];

    protected $casts = [
        'hold_at' => 'datetime',
        'expired_at' => 'datetime',
    ];

    // Relationships
    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function booking()
    {
        return $this->belongsTo(Booking::class);
    }

    public function cashier()
    {
        return $this->belongsTo(User::class, 'cashier_id');
    }

    public function items()
    {
        return $this->hasMany(CartItem::class);
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    public function scopeHold($query)
    {
        return $query->where('status', 'hold');
    }

    public function scopeNotExpired($query)
    {
        return $query->where(function ($q) {
            $q->whereNull('expired_at')
                ->orWhere('expired_at', '>', now());
        });
    }

    // Helpers
    public function getTotalAmount()
    {
        return $this->items->sum('subtotal');
    }

    public function getItemsCount()
    {
        return $this->items->sum('quantity');
    }

    public static function generateSessionCode()
    {
        $date = now()->format('Ymd');
        $lastSession = self::whereDate('created_at', now())
            ->orderBy('id', 'desc')
            ->first();

        $number = $lastSession ? (int)substr($lastSession->session_code, -3) + 1 : 1;

        return 'CS-' . $date . '-' . str_pad($number, 3, '0', STR_PAD_LEFT);
    }
}
