<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Apartment extends Model {
    protected $fillable = ['code','floor','number','area','share_fraction','status'];

    public function owner() {
        return $this->hasOne(Owner::class);
    }
    public function invoices(){ return $this->hasMany(Invoice::class); }
    public function garages(){ return $this->hasMany(Garage::class); }
}