<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class RoomInspection extends Model
{
    use HasFactory;

    protected $fillable = [
        'housekeeping_task_id',
        'room_id',
        'result', // passed, failed
        'status_after_inspection', // Available, Maintenance
        'notes',
    ];

    public function housekeepingTask(): BelongsTo
    {
        return $this->belongsTo(HousekeepingTask::class, 'housekeeping_task_id');
    }

    public function room(): BelongsTo
    {
        return $this->belongsTo(Room::class);
    }
}
