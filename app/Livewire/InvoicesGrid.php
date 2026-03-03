<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Invoice;
use App\Services\AdminFeeService;
use Carbon\Carbon;

class InvoicesGrid extends Component
{
    use WithPagination;

    public $search = '';
    public $perPage = 10;
    public $selectedInvoiceId;
    public $payAmount;
    public $payDate;
    public $showPaymentModal = false; // 👈 AGREGAR

    protected $listeners = ['refreshGrid' => '$refresh'];

    public function mount()
    {
        $this->payDate = Carbon::now()->toDateString();
    }

    public function render()
    {
        $query = Invoice::with(['apartment.owner', 'service'])
            ->when($this->search, function ($q) {
                $q->where(function ($sub) {
                    $sub->whereHas('apartment', function ($qa) {
                        $qa->where('code', 'like', "%{$this->search}%");
                    })
                        ->orWhereHas('apartment.owner', function ($qo) {
                            $qo->where('name', 'like', "%{$this->search}%");
                        });
                });
            });

        return view('livewire.invoices-grid', [
            'invoices' => $query->orderByDesc('date')->paginate($this->perPage)
        ]);
    }

    public function openPayModal($invoiceId)
    {
        $this->selectedInvoiceId = $invoiceId;
        $this->showPaymentModal = true;
    }

    public function registerPayment(AdminFeeService $service)
    {
        $invoice = Invoice::findOrFail($this->selectedInvoiceId);

        $service->registerPayment(
            $invoice,
            (float)$this->payAmount,
            Carbon::parse($this->payDate)
        );

        session()->flash('message', 'Pago registrado.');

        $this->reset(['selectedInvoiceId', 'payAmount']);
        $this->showPaymentModal = false; // 👈 cerrar modal

        $this->dispatch('refreshGrid');
    }
}
