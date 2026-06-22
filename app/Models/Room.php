<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Room extends Model
{
    use HasFactory;

    protected $fillable = [
        'room_number',
        'floor',
        'room_type_id',
        'status',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function roomType(): BelongsTo
    {
        return $this->belongsTo(RoomType::class);
    }

    public function reservations(): HasMany
    {
        return $this->hasMany(Reservation::class);
    }

    public function housekeepingTasks(): HasMany
    {
        return $this->hasMany(HousekeepingTask::class);
    }

    public function maintenanceRequests(): HasMany
    {
        return $this->hasMany(MaintenanceRequest::class);
    }

    public function damageReports(): HasMany
    {
        return $this->hasMany(DamageReport::class);
    }

    public function getStatusTextAttribute(): string
    {
        return match($this->status) {
            'A' => 'Available',
            'O' => 'Occupied',
            'D' => 'Dirty',
            'C' => 'Cleaning',
            'M' => 'Maintenance',
            'R' => 'Reserved',
            'B' => 'Blocked',
            default => 'Unknown'
        };
    }

    public function getStatusColorAttribute(): string
    {
        return match($this->status) {
            'A' => 'success',      // Green
            'O' => 'primary',      // Blue
            'D' => 'danger',       // Red (Dirty)
            'C' => 'warning',      // Amber (Cleaning)
            'M' => 'dark',         // Dark (Maintenance)
            'R' => 'info',         // Sky Blue (Reserved)
            'B' => 'secondary',    // Gray (Blocked)
            default => 'light'
        };
    }
}
