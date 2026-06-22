<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class LaundryOrder extends Model
{
    use HasFactory;

    protected $fillable = [
        'reservation_id',
        'laundry_service_id',
        'status', // Pending, Collected, Washing, Ready, Delivered, Cancelled
        'order_date',
        'total_amount',
    ];

    protected $casts = [
        'order_date' => 'datetime',
        'total_amount' => 'decimal:2',
    ];

    public function reservation(): BelongsTo
    {
        return $this->belongsTo(Reservation::class);
    }

    public function service(): BelongsTo
    {
        return $this->belongsTo(LaundryService::class, 'laundry_service_id');
    }

    public function items(): HasMany
    {
        return $this->hasMany(LaundryOrderItem::class, 'laundry_order_id');
    }

    public function damageReports(): HasMany
    {
        return $this->hasMany(LaundryDamageReport::class, 'laundry_order_id');
    }
}
