<?php
namespace App\Livewire;

use Livewire\Component;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class GenerateMonthlyInvoices extends Component
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
        return view('livewire.generate-monthly-invoices');
    }

    public function generate()
    {
        Log::info('GenerateMonthlyInvoices.generate called', ['year'=>$this->year,'month'=>$this->month]);

        $service = app(\App\Services\AdminFeeService::class);
        $service->generateMonthlyInvoices((int)$this->year, (int)$this->month);

        session()->flash('message','Facturas generadas.');

        // dispatch event to any component listening
        $this->dispatch('refreshGrid');

        Log::info('GenerateMonthlyInvoices finished and dispatched refreshGrid');
    }
}