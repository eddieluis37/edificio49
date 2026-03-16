<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Expense extends Model
{
    protected $fillable = [
        'supplier_id',
        'reference',
        'category',
        'description',
        'amount',
        'date',
        'due_date',
        'status',
        'payment_method',
        'payment_reference',
        'meta'
    ];

    protected $casts = [
        'date' => 'date',
        'due_date' => 'date',
        'amount' => 'decimal:2',
        'meta' => 'array'
    ];

    public function supplier()
    {
        return $this->belongsTo(Supplier::class);
    }
}
