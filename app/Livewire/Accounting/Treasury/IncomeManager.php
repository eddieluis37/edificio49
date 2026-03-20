<?php

namespace App\Livewire\Accounting\Treasury;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\TreasuryEntry;
use App\Models\Account;
use App\Models\JournalEntry;
use App\Models\JournalItem;
use App\Models\Owner;
use Illuminate\Support\Facades\DB;

class IncomeManager extends Component
{
    use WithPagination;

    public $search = '';
    public $showModal = false;
    
    // Form fields
    public $entry_id;
    public $date;
    public $amount;
    public $description;
    public $account_id; // Destino (Caja/Banco)
    public $counterpart_account_id; // Origen (Cuenta de Ingreso 4195)
    public $owner_id;
    public $reference_doc;
    public $payment_method = 'Transferencia';

    protected $rules = [
        'date' => 'required|date',
        'amount' => 'required|numeric|min:0.01',
        'description' => 'required|string|max:500',
        'account_id' => 'required|exists:accounts,id',
        'counterpart_account_id' => 'required|exists:accounts,id',
        'payment_method' => 'required',
    ];

    public function mount()
    {
        $this->date = now()->toDateString();
        // Default accounts if they exist
        $this->account_id = Account::where('code', '111005')->first()?->id; // Bancos
        $this->counterpart_account_id = Account::where('code', '4195')->first()?->id; // Ingresos Varios
    }

    public function render()
    {
        $query = TreasuryEntry::with(['account', 'counterpart', 'owner'])
            ->where('type', 'income')
            ->when($this->search, function($q) {
                $q->where('description', 'like', "%{$this->search}%")
                  ->orWhere('reference_doc', 'like', "%{$this->search}%");
            })
            ->orderBy('date', 'desc');

        return view('livewire.accounting.treasury.income-manager', [
            'incomes' => $query->paginate(10),
            'assetAccounts' => Account::where('type', 'asset')->where('level', '>=', 2)->get(),
            'incomeAccounts' => Account::where('type', 'income')->get(),
            'owners' => Owner::orderBy('name')->get(),
        ])->layout('layouts.theme');
    }

    public function openModal()
    {
        $this->resetForm();
        $this->showModal = true;
    }

    public function resetForm()
    {
        $this->reset(['entry_id', 'amount', 'description', 'owner_id', 'reference_doc']);
        $this->date = now()->toDateString();
        $this->payment_method = 'Transferencia';
        $this->account_id = Account::where('code', '111005')->first()?->id;
        $this->counterpart_account_id = Account::where('code', '4195')->first()?->id;
    }

    public function save()
    {
        $this->validate();

        DB::transaction(function() {
            // Create Journal Entry
            $je = JournalEntry::create([
                'number' => 'INC-' . time(),
                'date' => $this->date,
                'description' => "Ingreso Vario: " . $this->description,
                'total_debit' => $this->amount,
                'total_credit' => $this->amount,
                'status' => 'posted'
            ]);

            // DEBIT: Destino (Asset - Caja/Banco)
            JournalItem::create([
                'journal_entry_id' => $je->id,
                'account_id' => $this->account_id,
                'debit' => $this->amount,
                'credit' => 0,
                'description' => $this->description
            ]);

            // CREDIT: Origen (Revenue/Liability - Counterpart)
            JournalItem::create([
                'journal_entry_id' => $je->id,
                'account_id' => $this->counterpart_account_id,
                'debit' => 0,
                'credit' => $this->amount,
                'description' => $this->description
            ]);

            // Update balances
            Account::find($this->account_id)->increment('balance', $this->amount);
            Account::find($this->counterpart_account_id)->increment('balance', $this->amount); // Ingresos aumentan por el Haber pero balance es + si es Revenue

            TreasuryEntry::create([
                'type' => 'income',
                'date' => $this->date,
                'amount' => $this->amount,
                'description' => $this->description,
                'account_id' => $this->account_id,
                'counterpart_account_id' => $this->counterpart_account_id,
                'owner_id' => $this->owner_id,
                'journal_entry_id' => $je->id,
                'reference_doc' => $this->reference_doc,
                'payment_method' => $this->payment_method,
            ]);
        });

        $this->showModal = false;
        session()->flash('message', 'Ingreso registrado correctamente.');
    }
}
