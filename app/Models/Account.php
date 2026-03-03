<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Account extends Model {
    protected $fillable = ['code','name','level','parent_id','type','balance'];
    public function parent(){ return $this->belongsTo(Account::class,'parent_id'); }
}