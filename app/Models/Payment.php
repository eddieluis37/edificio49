<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model {
    protected $fillable = ['invoice_id','owner_id','amount','date','interest','method','reference','meta'];
    protected $casts = ['meta'=>'array','date'=>'date'];

    public function invoice(){ return $this->belongsTo(Invoice::class); }
    public function owner(){ return $this->belongsTo(Owner::class); }
}