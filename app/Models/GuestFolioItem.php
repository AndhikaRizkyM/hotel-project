<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class GuestFolioItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'guest_folio_id',
        'item_type', // Room Charge, Breakfast, Food & Beverage, Extra Bed, Laundry, Damage Charge, Lost Item Charge, Miscellaneous Charge
        'description',
        'amount',
        'reference_id',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
    ];

    public function folio(): BelongsTo
    {
        return $this->belongsTo(GuestFolio::class, 'guest_folio_id');
    }
}
