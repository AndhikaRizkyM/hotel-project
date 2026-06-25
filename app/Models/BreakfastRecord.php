<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BreakfastRecord extends Model
{
    use HasFactory;

    protected $fillable = [
        'reservation_id',
        'date',
        'status', // Pending, Preparing, Delivered, Skipped
        'pax',
        'notes',
        'timeline',
    ];

    protected $casts = [
        'date' => 'date',
        'timeline' => 'array',
        'pax' => 'integer',
    ];

    public function reservation(): BelongsTo
    {
        return $this->belongsTo(Reservation::class);
    }
}
