<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ExtrabedRequest extends Model
{
    use HasFactory;

    protected $fillable = [
        'reservation_id',
        'qty',
        'price_per_night',
        'num_nights',
        'total_price',
        'status', // requested, installed, removed
        'request_date',
    ];

    protected $casts = [
        'price_per_night' => 'decimal:2',
        'total_price' => 'decimal:2',
        'request_date' => 'datetime',
    ];

    public function reservation(): BelongsTo
    {
        return $this->belongsTo(Reservation::class);
    }
}
