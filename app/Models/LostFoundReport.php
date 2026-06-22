<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class LostFoundReport extends Model
{
    use HasFactory;

    protected $fillable = [
        'room_id',
        'reported_by_user_id',
        'item_description',
        'location_found',
        'guest_name',
        'contact_number',
        'status', // lost, claimed
        'claim_date',
    ];

    protected $casts = [
        'claim_date' => 'datetime',
    ];

    public function room(): BelongsTo
    {
        return $this->belongsTo(Room::class);
    }

    public function reportedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'reported_by_user_id');
    }
}
