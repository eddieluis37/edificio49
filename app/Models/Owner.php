<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Owner extends Model {
    protected $fillable = ['apartment_id','name','document_type','document_number','email','phone','active'];
    public function apartment(){ return $this->belongsTo(Apartment::class); }
    public function invoices(){ return $this->hasMany(Invoice::class); }
    public function payments(){ return $this->hasMany(Payment::class); }
    public function garages(){ return $this->hasMany(Garage::class); }
}