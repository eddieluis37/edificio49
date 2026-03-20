<?php

namespace App\Livewire\Accounting;

use Livewire\Component;
use App\Models\Payment;
use App\Models\Expense;
use App\Models\TreasuryEntry;
use Carbon\Carbon;

class FinancialLedger extends Component
{
    public $month;
    public $year;
    
    // Summary values
    public $totalIncome = 0;
    public $totalExpenses = 0;
    public $balance = 0;

    // Timeline arrays
    public $movements = [];

    public function mount()
    {
        $this->year = Carbon::now()->year;
        $this->month = Carbon::now()->month;
        $this->loadData();
    }

    public function updated($propertyName)
    {
        if (in_array($propertyName, ['month', 'year'])) {
            $this->loadData();
        }
    }

    public function loadData()
    {
        $startDate = Carbon::create($this->year, $this->month, 1)->startOfMonth();
        $endDate = $startDate->copy()->endOfMonth();

        // 1. Ingresos por Administración (Payments)
        $payments = Payment::with('invoice.apartment')
            ->whereBetween('date', [$startDate->toDateString(), $endDate->toDateString()])
            ->get();

        // 2. Gastos Legacy (Egresos directos de la tabla original)
        $legacyExpenses = Expense::with('supplier')
            ->where('status', 'paid')
            ->whereBetween('date', [$startDate->toDateString(), $endDate->toDateString()])
            ->get();

        // 3. Tesorería Nueva (Ingresos Varios, Caja Menor, etc.)
        $treasury = TreasuryEntry::with(['account', 'counterpart', 'owner', 'supplier'])
            ->whereBetween('date', [$startDate->toDateString(), $endDate->toDateString()])
            ->get();

        $movements = collect();

        // Agregar Pagos Administración
        foreach ($payments as $p) {
            $movements->push([
                'type' => 'income',
                'amount' => $p->amount,
                'date' => Carbon::parse($p->date),
                'concept' => 'Recaudo Apto. ' . ($p->invoice->apartment->code ?? 'N/A'),
                'desc' => 'Recibo #' . ($p->invoice->number ?? 'N/A') . ' Pagado con ' . $p->method,
                'id' => 'p_'.$p->id,
                'source' => 'Admin'
            ]);
        }

        // Agregar Movimientos de Tesorería (Nuevos)
        foreach ($treasury as $t) {
            $isIncome = in_array($t->type, ['income', 'petty_cash_in']);
            $movements->push([
                'type' => $isIncome ? 'income' : 'expense',
                'amount' => $t->amount,
                'date' => Carbon::parse($t->date),
                'concept' => ($isIncome ? 'Ingreso: ' : 'Egreso: ') . $t->description,
                'desc' => ($t->type === 'petty_cash_in' || $t->type === 'petty_cash_out' ? '[Caja Menor] ' : '') . 
                          ($t->supplier ? 'Prov: ' . $t->supplier->name : ($t->owner ? 'Prop: ' . $t->owner->name : 'Varios')),
                'id' => 't_'.$t->id,
                'source' => $t->type
            ]);
        }
        
        // Agregar Gastos Legacy (para retrocompatibilidad si no tienen TreasuryEntry herencia)
        // Solo si NO están ya en treasuryEntries (evitar duplicados si migramos luego)
        foreach ($legacyExpenses as $e) {
            // Check if there is a treasury entry for this expense (not yet implemented linkage, but defensive)
            $movements->push([
                'type' => 'expense',
                'amount' => $e->amount,
                'date' => Carbon::parse($e->date),
                'concept' => 'Pago Proveedor: ' . ($e->supplier->name ?? 'Varios'),
                'desc' => '[Legacy] ' . $e->category . ' | ' . $e->description,
                'id' => 'le_'.$e->id,
                'source' => 'LegacyExpense'
            ]);
        }

        $this->totalIncome = $movements->where('type', 'income')->sum('amount');
        $this->totalExpenses = $movements->where('type', 'expense')->sum('amount');
        $this->balance = $this->totalIncome - $this->totalExpenses;

        // Ordenar cronológicamente descendente para que el más reciente esté arriba
        $this->movements = $movements->sortByDesc(function($mov) {
            return $mov['date']->timestamp;
        })->values()->all();
    }

    public function render()
    {
        return view('livewire.accounting.financial-ledger')->layout('layouts.theme');
    }
}
