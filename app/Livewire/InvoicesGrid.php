<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Invoice;
use App\Models\Payment;
use App\Services\AdminFeeService;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class InvoicesGrid extends Component
{
    use WithPagination;

    public $search = '';
    public $statusFilter = ''; 
    public $perPage = 10;
    
    // Modal & Details State
    public $selectedInvoice = null;
    public $showPaymentModal = false;

    // Payment Form
    public $payAmount;
    public $payDate;
    public $payMethod = 'transferencia';
    public $payReference = '';

    // Summary Analytics
    public $totalPendingStr = '$0';
    public $totalCollectedStr = '$0';
    public $pendingCount = 0;

    protected $listeners = ['refreshGrid' => '$refresh'];

    public function mount()
    {
        // Initialization if needed
    }

    public function updatingSearch() { $this->resetPage(); }
    public function updatingStatusFilter() { $this->resetPage(); }

    public function render()
    {
        $query = Invoice::with(['apartment.owner', 'service'])
            ->when($this->search, function ($q) {
                $q->where(function ($sub) {
                    $sub->whereHas('apartment', function ($qa) {
                        $qa->where('code', 'like', "%{$this->search}%");
                    })
                    ->orWhereHas('owner', function ($qo) { 
                        $qo->where('name', 'like', "%{$this->search}%");
                    })
                    ->orWhere('number', 'like', "%{$this->search}%");
                });
            })
            ->when($this->statusFilter, function($q) {
                if ($this->statusFilter === 'pending') {
                    $q->whereIn('status', ['issued', 'partially']);
                } elseif ($this->statusFilter !== '') {
                    $q->where('status', $this->statusFilter);
                }
            });

        // Fast metrics
        $tPending = Invoice::whereIn('status', ['issued', 'partially'])->sum('balance');
        $this->totalPendingStr = '$' . number_format($tPending, 0, ',', '.');
        
        $tCollected = DB::table('payments')->sum('amount');
        $this->totalCollectedStr = '$' . number_format($tCollected, 0, ',', '.');
        
        $this->pendingCount = Invoice::whereIn('status', ['issued', 'partially'])->count();

        return view('livewire.invoices-grid', [
            'invoices' => $query->orderByDesc('date')->paginate($this->perPage)
        ])->layout('layouts.theme');
    }

    public function viewDetails($invoiceId)
    {
        $this->selectedInvoice = Invoice::with(['payments', 'apartment', 'owner'])->findOrFail($invoiceId);
        $this->payAmount = $this->selectedInvoice->balance; 
        $this->payDate = Carbon::now()->toDateString();
        $this->payMethod = 'transferencia';
        $this->payReference = '';
        
        $this->showPaymentModal = true;
    }

    public function closePayModal()
    {
        $this->showPaymentModal = false;
        $this->selectedInvoice = null;
    }

    public function registerPayment()
    {
        $this->validate([
            'payAmount' => 'required|numeric|min:1',
            'payDate' => 'required|date',
            'payMethod' => 'required|string',
            'payReference' => 'nullable|string|max:255'
        ]);

        if (!$this->selectedInvoice) return;

        if ($this->payAmount > $this->selectedInvoice->balance) {
            session()->flash('error', 'El monto a pagar no puede superar el saldo adeudado ($'. number_format($this->selectedInvoice->balance, 0, ',', '.') .').');
            return;
        }

        $service = app(AdminFeeService::class);
        $service->registerPayment(
            $this->selectedInvoice,
            (float)$this->payAmount,
            Carbon::parse($this->payDate),
            [
                'method' => $this->payMethod,
                'reference' => $this->payReference
            ]
        );

        session()->flash('success_payment', '¡Exito! Abono registrado contablemente y descontado de la deuda de manera irreversible.');
        
        // Refrescar el detalle en lugar de cerrar el modal (para que vea el historial actualizado)
        $this->selectedInvoice = Invoice::with(['payments', 'apartment', 'owner'])->findOrFail($this->selectedInvoice->id);
        $this->payAmount = $this->selectedInvoice->balance;
        
        $this->dispatch('refreshGrid');
    }
}
