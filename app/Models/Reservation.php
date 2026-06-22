<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Reservation extends Model
{
    use HasFactory;

    protected $fillable = [
        'reservation_number',
        'guest_id',
        'room_id',
        'check_in_date',
        'check_out_date',
        'room_charge_per_night',
        'total_room_charge',
        'tax',
        'service_charge',
        'total_charge',
        'status',
    ];

    protected $casts = [
        'check_in_date' => 'date',
        'check_out_date' => 'date',
        'room_charge_per_night' => 'decimal:2',
        'total_room_charge' => 'decimal:2',
        'tax' => 'decimal:2',
        'service_charge' => 'decimal:2',
        'total_charge' => 'decimal:2',
    ];

    public function guest(): BelongsTo
    {
        return $this->belongsTo(Guest::class);
    }

    public function room(): BelongsTo
    {
        return $this->belongsTo(Room::class);
    }

    public function folio(): HasOne
    {
        return $this->hasOne(GuestFolio::class);
    }

    public function deposits(): HasMany
    {
        return $this->hasMany(Deposit::class);
    }

    public function fbOrders(): HasMany
    {
        return $this->hasMany(FbOrder::class);
    }

    public function laundryOrders(): HasMany
    {
        return $this->hasMany(LaundryOrder::class);
    }

    public function extrabedRequests(): HasMany
    {
        return $this->hasMany(ExtrabedRequest::class);
    }

    public function getStatusTextAttribute(): string
    {
        return match($this->status) {
            'RSV' => 'Reserved',
            'CI' => 'Checked In',
            'CO' => 'Checked Out',
            'CAN' => 'Cancelled',
            'NS' => 'No Show',
            default => 'Unknown'
        };
    }

    public function getStatusColorAttribute(): string
    {
        return match($this->status) {
            'RSV' => 'info',
            'CI' => 'success',
            'CO' => 'secondary',
            'CAN' => 'danger',
            'NS' => 'warning',
            default => 'light'
        };
    }
}
