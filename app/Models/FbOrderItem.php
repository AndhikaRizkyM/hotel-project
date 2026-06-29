<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class FbOrderItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'fb_order_id',
        'fb_menu_id',
        'qty',
        'price',
        'subtotal',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'subtotal' => 'decimal:2',
    ];

    public function order(): BelongsTo
    {
        return $this->belongsTo(FbOrder::class, 'fb_order_id');
    }

    public function menu(): BelongsTo
    {
        return $this->belongsTo(FbMenu::class, 'fb_menu_id');
    }
}
