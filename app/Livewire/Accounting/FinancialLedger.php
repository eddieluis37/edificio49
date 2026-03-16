<?php

namespace App\Livewire\Accounting;

use Livewire\Component;
use App\Models\Invoice;
use App\Models\Payment;
use App\Models\Expense;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

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

        // 1. Ingresos (Pagos de Recibos recibidos en este corte)
        $payments = Payment::with('invoice.apartment')
            ->whereBetween('date', [$startDate->toDateString(), $endDate->toDateString()])
            ->get();

        // 2. Egresos (Gastos PAGADOS en este corte)
        $expenses = Expense::with('supplier')
            ->where('status', 'paid')
            ->whereBetween('date', [$startDate->toDateString(), $endDate->toDateString()])
            ->get();

        $this->totalIncome = $payments->sum('amount');
        $this->totalExpenses = $expenses->sum('amount');
        $this->balance = $this->totalIncome - $this->totalExpenses;

        $movements = collect();

        foreach ($payments as $p) {
            $movements->push([
                'type' => 'income',
                'amount' => $p->amount,
                'date' => Carbon::parse($p->date),
                'concept' => 'Recaudo Apto. ' . ($p->invoice->apartment->code ?? 'N/A'),
                'desc' => 'Recibo #' . ($p->invoice->number ?? 'N/A') . ' Pagado con ' . $p->method,
                'id' => 'p_'.$p->id
            ]);
        }

        foreach ($expenses as $e) {
            $movements->push([
                'type' => 'expense',
                'amount' => $e->amount,
                'date' => Carbon::parse($e->date),
                'concept' => 'Pago de Proveedor: ' . ($e->supplier->name ?? 'Gastos'),
                'desc' => $e->category . ' | ' . $e->description,
                'id' => 'e_'.$e->id
            ]);
        }

        // Ordenar cronológicamente ascendente para libro diario
        $this->movements = $movements->sortBy(function($mov) {
            return $mov['date']->timestamp;
        })->values()->all();
    }

    public function render()
    {
        return view('livewire.accounting.financial-ledger')->layout('layouts.theme');
    }
}
