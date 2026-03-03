<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class JournalEntry extends Model {
    protected $fillable = ['number','date','description','total_debit','total_credit','status'];
    protected $casts = ['date'=>'date'];
    public function items(){ return $this->hasMany(JournalItem::class); }
}