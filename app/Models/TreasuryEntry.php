<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class TreasuryEntry extends Model
{
    use HasFactory;

    protected $fillable = [
        'date',
        'type',
        'number',
        'account_id',
        'counterpart_account_id',
        'amount',
        'description',
        'supplier_id',
        'owner_id',
        'journal_entry_id',
        'status',
        'payment_method',
        'reference_doc',
    ];

    protected $casts = [
        'date' => 'date',
        'amount' => 'decimal:2',
    ];

    public function account()
    {
        return $this->belongsTo(Account::class, 'account_id');
    }

    public function counterpart()
    {
        return $this->belongsTo(Account::class, 'counterpart_account_id');
    }

    public function supplier()
    {
        return $this->belongsTo(Supplier::class);
    }

    public function owner()
    {
        return $this->belongsTo(Owner::class);
    }

    public function journalEntry()
    {
        return $this->belongsTo(JournalEntry::class);
    }

    // SCOPES
    public function scopeIncome($query) { return $query->where('type', 'income'); }
    public function scopeExpense($query) { return $query->where('type', 'expense'); }
    public function scopePettyCash($query) { return $query->whereIn('type', ['petty_cash_in', 'petty_cash_out']); }
}
