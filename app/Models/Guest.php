<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Guest extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'id_number',
        'birth_date',
        'gender',
        'address',
        'country',
        'phone',
        'email',
        'vehicle_no',
        'profession',
        'company',
        'member_card_no',
    ];

    protected $casts = [
        'birth_date' => 'date',
    ];

    public function reservations(): HasMany
    {
        return $this->hasMany(Reservation::class);
    }
}
