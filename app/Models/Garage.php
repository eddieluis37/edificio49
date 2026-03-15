<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Garage extends Model
{
    use HasFactory;

    protected $fillable = [
        'code',
        'apartment_id',
        'owner_id',
        'share_fraction',
        'status',
    ];

    /**
     * Relationship: An apartment can have multiple garages.
     */
    public function apartment()
    {
        return $this->belongsTo(Apartment::class);
    }

    /**
     * Relationship: Optional connection to an owner.
     */
    public function owner()
    {
        return $this->belongsTo(Owner::class);
    }
}
