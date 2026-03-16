<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Supplier extends Model
{
    protected $fillable = ['name', 'nit', 'contact_name', 'phone', 'email', 'service_type', 'active'];
    protected $casts = [
        'active' => 'boolean'
    ];

    public function expenses()
    {
        return $this->hasMany(Expense::class);
    }
}
