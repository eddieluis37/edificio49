<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Invoice;
use App\Models\Owner;

class AdminFeeReport extends Component
{
    public int $year;
    public int $month;

    public function mount()
    {
        $this->year = now()->year;
        $this->month = now()->month;
    }

    public function render()
    {
        $periodStart = now()->startOfMonth()->setDate($this->year, $this->month, 1);
        $periodEnd = now()->startOfMonth()->setDate($this->year, $this->month, 1)->endOfMonth();

        $invoices = Invoice::whereYear('date', $this->year)
            ->whereMonth('date', $this->month);

        $totalIssued = $invoices->sum('final_amount');
        $totalBalance = $invoices->sum('balance');
        $paidInvoicesCount = Invoice::whereYear('date', $this->year)
            ->whereMonth('date', $this->month)
            ->where('status', 'paid')
            ->count();

        $totalCollected = $invoices->get()->sum(function ($invoice) {
            return ($invoice->final_amount ?? 0) - ($invoice->balance ?? 0);
        });

        $newInvoices = Invoice::whereBetween('date', [$periodStart, $periodEnd])->count();

        $agedDebtors = Invoice::query()
            ->select('owner_id')
            ->where('balance', '>', 0)
            ->groupBy('owner_id')
            ->orderByRaw('SUM(balance) DESC')
            ->limit(5)
            ->pluck('owner_id');

        $topDebtors = Owner::whereIn('id', $agedDebtors)
            ->with(['invoices' => function ($q) {
                $q->where('balance', '>', 0);
            }])
            ->get()
            ->map(function ($owner) {
                return [
                    'name' => $owner->name,
                    'balance' => $owner->invoices->sum('balance'),
                    'pending_invoices' => $owner->invoices->count(),
                ];
            });

        return view('livewire.admin-fee-report', [
            'totalIssued' => round($totalIssued, 2),
            'totalCollected' => round($totalCollected, 2),
            'totalBalance' => round($totalBalance, 2),
            'paidInvoicesCount' => $paidInvoicesCount,
            'newInvoices' => $newInvoices,
            'topDebtors' => $topDebtors,
            'year' => $this->year,
            'month' => $this->month,
        ]);
    }
}
