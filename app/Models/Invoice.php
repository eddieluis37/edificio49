<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Invoice extends Model
{
    protected $fillable = [
        'apartment_id',
        'owner_id',
        'service_id',
        'number',
        'date',
        'due_date',
        'amount',
        'admin_base',
        'honorarios',
        'final_amount',
        'discount',
        'balance',
        'status',
        'meta'
    ];

    protected $casts = [
        'date' => 'date',
        'due_date' => 'date',
        'admin_base' => 'decimal:2',
        'honorarios' => 'decimal:2',
        'final_amount' => 'decimal:2',
        'amount' => 'decimal:2',
    ];

    public function apartment()
    {
        return $this->belongsTo(Apartment::class);
    }
    public function owner()
    {
        return $this->belongsTo(Owner::class);
    }
    public function service()
    {
        return $this->belongsTo(Service::class);
    }
    public function payments()
    {
        return $this->hasMany(Payment::class);
    }
}
