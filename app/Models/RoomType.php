<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class RoomType extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'capacity',
        'size',
        'description',
        'price_per_night',
        'breakfast_included',
        'extra_bed_available',
        'facilities',
        'status',
    ];

    protected $casts = [
        'breakfast_included' => 'boolean',
        'extra_bed_available' => 'boolean',
        'price_per_night' => 'decimal:2',
    ];

    public function rooms(): HasMany
    {
        return $this->hasMany(Room::class);
    }
}
