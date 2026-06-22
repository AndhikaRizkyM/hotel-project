<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class LaundryDamageReport extends Model
{
    use HasFactory;

    protected $fillable = [
        'laundry_order_id',
        'item_name',
        'issue_type', // damage, lost
        'description',
        'compensation_amount',
        'status', // pending, resolved
    ];

    protected $casts = [
        'compensation_amount' => 'decimal:2',
    ];

    public function order(): BelongsTo
    {
        return $this->belongsTo(LaundryOrder::class, 'laundry_order_id');
    }
}
