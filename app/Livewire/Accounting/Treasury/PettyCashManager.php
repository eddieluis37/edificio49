<?php

namespace App\Livewire\Accounting\Treasury;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\TreasuryEntry;
use App\Models\Account;
use App\Models\JournalEntry;
use App\Models\JournalItem;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class PettyCashManager extends Component
{
    use WithPagination;

    public $search = '';
    public $showModal = false;
    
    // Form fields
    public $entry_id;
    public $type = 'petty_cash_out';
    public $date;
    public $amount;
    public $description;
    public $counterpart_account_id;
    public $reference_doc;

    protected $rules = [
        'date' => 'required|date',
        'amount' => 'required|numeric|min:0.01',
        'description' => 'required|string|max:500',
        'counterpart_account_id' => 'required|exists:accounts,id',
        'type' => 'required|in:petty_cash_in,petty_cash_out',
    ];

    public function mount()
    {
        $this->date = now()->toDateString();
    }

    public function render()
    {
        $pettyCashAccount = Account::where('code', '110505')->first();
        $balance = $pettyCashAccount ? $pettyCashAccount->balance : 0;

        // Gastos del mes actual (explícito)
        $startOfMonth = now()->startOfMonth();
        $endOfMonth = now()->endOfMonth();
        
        $monthlyExpenses = TreasuryEntry::where('type', 'petty_cash_out')
            ->whereBetween('date', [$startOfMonth, $endOfMonth])
            ->sum('amount');

        $currentMonthName = now()->locale('es')->isoFormat('MMMM YYYY');

        $query = TreasuryEntry::with(['counterpart'])
            ->whereIn('type', ['petty_cash_in', 'petty_cash_out'])
            ->when($this->search, function($q) {
                $q->where('description', 'like', "%{$this->search}%")
                  ->orWhere('reference_doc', 'like', "%{$this->search}%");
            })
            ->orderBy('date', 'desc')
            ->orderBy('id', 'desc');

        return view('livewire.accounting.treasury.petty-cash-manager', [
            'movements' => $query->paginate(10),
            'balance' => $balance,
            'monthlyExpenses' => $monthlyExpenses,
            'currentMonthName' => ucfirst($currentMonthName),
            'accounts' => Account::where('level', '>=', 2)->orderBy('code')->get(),
        ])->layout('layouts.theme');
    }

    public function openModal($id = null)
    {
        $this->resetForm();
        if ($id) {
            $e = TreasuryEntry::findOrFail($id);
            $this->entry_id = $e->id;
            $this->type = $e->type;
            $this->date = $e->date->toDateString();
            $this->amount = $e->amount;
            $this->description = $e->description;
            $this->counterpart_account_id = $e->counterpart_account_id;
            $this->reference_doc = $e->reference_doc;
        }
        $this->showModal = true;
    }

    public function resetForm()
    {
        $this->reset(['entry_id', 'amount', 'description', 'counterpart_account_id', 'reference_doc']);
        $this->type = 'petty_cash_out';
        $this->date = now()->toDateString();
    }

    public function save()
    {
        $this->validate();

        $account_petty_cash = Account::where('code', '110505')->first();
        if (!$account_petty_cash) {
            session()->flash('error', 'No se encontró la cuenta de Caja Menor (110505)');
            return;
        }

        DB::transaction(function() use ($account_petty_cash) {
            $isNew = !$this->entry_id;
            
            // Create Journal Entry
            $je = JournalEntry::create([
                'number' => 'PC-' . time(),
                'date' => $this->date,
                'description' => "Caja Menor: " . $this->description,
                'total_debit' => $this->amount,
                'total_credit' => $this->amount,
                'status' => 'posted'
            ]);

            if ($this->type === 'petty_cash_out') {
                // GASTO: Debit Counterpart, Credit Petty Cash
                JournalItem::create([
                    'journal_entry_id' => $je->id,
                    'account_id' => $this->counterpart_account_id,
                    'debit' => $this->amount,
                    'credit' => 0,
                    'description' => $this->description
                ]);
                JournalItem::create([
                    'journal_entry_id' => $je->id,
                    'account_id' => $account_petty_cash->id,
                    'debit' => 0,
                    'credit' => $this->amount,
                    'description' => $this->description
                ]);
                
                // Update balances (simplified)
                $account_petty_cash->decrement('balance', $this->amount);
                Account::find($this->counterpart_account_id)->increment('balance', $this->amount);
            } else {
                // INGRESO/REEMBOLSO: Debit Petty Cash, Credit Counterpart (usually Bank)
                JournalItem::create([
                    'journal_entry_id' => $je->id,
                    'account_id' => $account_petty_cash->id,
                    'debit' => $this->amount,
                    'credit' => 0,
                    'description' => $this->description
                ]);
                JournalItem::create([
                    'journal_entry_id' => $je->id,
                    'account_id' => $this->counterpart_account_id,
                    'debit' => 0,
                    'credit' => $this->amount,
                    'description' => $this->description
                ]);
                
                // Update balances
                $account_petty_cash->increment('balance', $this->amount);
                Account::find($this->counterpart_account_id)->decrement('balance', $this->amount);
            }

            TreasuryEntry::create([
                'type' => $this->type,
                'date' => $this->date,
                'amount' => $this->amount,
                'description' => $this->description,
                'account_id' => $account_petty_cash->id,
                'counterpart_account_id' => $this->counterpart_account_id,
                'journal_entry_id' => $je->id,
                'reference_doc' => $this->reference_doc,
            ]);
        });

        $this->showModal = false;
        $this->resetForm();
        session()->flash('message', 'Movimiento registrado correctamente.');
    }
}
