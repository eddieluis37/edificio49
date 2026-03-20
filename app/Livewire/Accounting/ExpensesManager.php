<?php

namespace App\Livewire\Accounting;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Expense;
use App\Models\Supplier;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class ExpensesManager extends Component
{
    use WithPagination;

    public $search = '';
    public $statusFilter = ''; // '' = Todos, 'pending' = Por Pagar, 'paid' = Pagados
    public $perPage = 10;

    // Supplier Modal
    public $showSupplierModal = false;
    public $supplier_id, $supplier_name, $supplier_nit, $supplier_contact, $supplier_phone, $supplier_email, $supplier_service, $supplier_active = true;

    // Expense Modal
    public $showExpenseModal = false;
    public $expense_id, $e_supplier_id, $e_reference, $e_category = 'Gasto General', $e_description, $e_amount, $e_date, $e_due_date, $e_status = 'pending', $e_payment_method;

    public $categories = [
        'Servicios Públicos (Agua/Luz/Internet)', 'Nómina Conserjería / Recepción', 'Nómina Aseo y Limpieza',
        'Honorarios Administración', 'Mantenimiento Ascensores', 'Mantenimiento CCTV y Alarmas',
        'Mantenimiento General / Reparaciones', 'Jardinería y Zonas Comunes', 'Honorarios Revisoría Fiscal / Contador',
        'Renovación Seguros y Pólizas', 'Insumos Aseo y Papelería', 'Fondo Imprevistos', 'Otro Gasto Menor'
    ];

    public $totalPaidStr = '$ 0';
    public $totalPendingStr = '$ 0';

    public function updatingSearch() { $this->resetPage(); }
    public function updatingStatusFilter() { $this->resetPage(); }

    public function render()
    {
        $query = Expense::with('supplier')
            ->when($this->search, function($q) {
                $q->where(function($sub) {
                    $sub->where('reference', 'like', "%{$this->search}%")
                        ->orWhere('category', 'like', "%{$this->search}%")
                        ->orWhereHas('supplier', function($qs) {
                            $qs->where('name', 'like', "%{$this->search}%")
                               ->orWhere('nit', 'like', "%{$this->search}%");
                        });
                });
            })
            ->when($this->statusFilter, function($q) {
                $q->where('status', $this->statusFilter);
            });

        $expenses = $query->orderBy('date', 'desc')->paginate($this->perPage);

        // KIPs calculations (Solo de este año, o global según PH. Hagámoslo del año actual)
        $currentYear = now()->year;
        
        $tPaid = Expense::where('status', 'paid')
                        ->whereYear('date', $currentYear)
                        ->sum('amount');
                        
        $tPending = Expense::where('status', 'pending')
                         ->sum('amount'); // Pendientes de todos los tiempos

        $this->totalPaidStr = '$ ' . number_format($tPaid, 0, ',', '.');
        $this->totalPendingStr = '$ ' . number_format($tPending, 0, ',', '.');

        return view('livewire.accounting.expenses-manager', [
            'expenses' => $expenses,
            'suppliersList' => Supplier::where('active', true)->orderBy('name')->get()
        ])->layout('layouts.theme');
    }

    // ======================================
    // SUPPLIERS LOGIC
    // ======================================
    public function openSupplierModal()
    {
        $this->resetSupplierForm();
        $this->showSupplierModal = true;
    }

    public function closeSupplierModal()
    {
        $this->showSupplierModal = false;
        $this->resetSupplierForm();
    }

    public function resetSupplierForm()
    {
        $this->reset(['supplier_id', 'supplier_name', 'supplier_nit', 'supplier_contact', 'supplier_phone', 'supplier_email', 'supplier_service']);
        $this->supplier_active = true;
    }

    public function saveSupplier()
    {
        $this->validate([
            'supplier_name' => 'required|string|max:255',
            'supplier_nit' => 'nullable|string|max:50',
            'supplier_phone' => 'nullable|string|max:50',
            'supplier_email' => 'nullable|email',
        ]);

        $sup = Supplier::updateOrCreate(
            ['id' => $this->supplier_id],
            [
                'name' => $this->supplier_name,
                'nit' => $this->supplier_nit,
                'contact_name' => $this->supplier_contact,
                'phone' => $this->supplier_phone,
                'email' => $this->supplier_email,
                'service_type' => $this->supplier_service,
                'active' => $this->supplier_active,
            ]
        );

        $this->e_supplier_id = $sup->id; // Auto select if creating from Expense Modal
        $this->closeSupplierModal();
        session()->flash('sup_message', 'Proveedor guardado correctamente.');
    }

    // ======================================
    // EXPENSES LOGIC
    // ======================================
    public function openExpenseModal($id = null)
    {
        $this->resetExpenseForm();
        
        if ($id) {
            $e = Expense::findOrFail($id);
            $this->expense_id = $e->id;
            $this->e_supplier_id = $e->supplier_id;
            $this->e_reference = $e->reference;
            $this->e_category = $e->category;
            $this->e_description = $e->description;
            $this->e_amount = $e->amount;
            $this->e_date = $e->date ? $e->date->toDateString() : null;
            $this->e_due_date = $e->due_date ? $e->due_date->toDateString() : null;
            $this->e_status = $e->status;
            $this->e_payment_method = $e->payment_method;
        } else {
            $this->e_date = Carbon::now()->toDateString();
            $this->e_due_date = Carbon::now()->addDays(15)->toDateString();
        }

        $this->showExpenseModal = true;
    }

    public function closeExpenseModal()
    {
        $this->showExpenseModal = false;
        $this->resetExpenseForm();
    }

    public function resetExpenseForm()
    {
        $this->reset(['expense_id', 'e_supplier_id', 'e_reference', 'e_description', 'e_amount', 'e_payment_method', 'e_date', 'e_due_date']);
        $this->e_category = 'Gasto General';
        $this->e_status = 'pending';
    }

    public function saveExpense()
    {
        $this->validate([
            'e_amount' => 'required|numeric|min:1',
            'e_date' => 'required|date',
            'e_category' => 'required|string',
            'e_supplier_id' => 'required|exists:suppliers,id',
            'e_status' => 'required|in:pending,paid'
        ]);

        Expense::updateOrCreate(
            ['id' => $this->expense_id],
            [
                'supplier_id' => $this->e_supplier_id,
                'reference' => $this->e_reference,
                'category' => $this->e_category,
                'description' => $this->e_description,
                'amount' => $this->e_amount,
                'date' => $this->e_date,
                'due_date' => $this->e_due_date,
                'status' => $this->e_status,
                'payment_method' => $this->e_payment_method,
            ]
        );

        $this->closeExpenseModal();
        session()->flash('message', 'Registro de egreso actualizado existosamente.');
    }

    public function deleteExpense($id)
    {
        Expense::findOrFail($id)->delete();
        session()->flash('message', 'Egreso eliminado.');
    }

    public function processInstantPayment($id)
    {
        $e = Expense::findOrFail($id);
        
        $bankAccount = \App\Models\Account::where('code', '111005')->first();
        $expenseAccount = \App\Models\Account::where('code', '5195')->first(); // Gastos Diversos default

        if (!$bankAccount || !$expenseAccount) {
            session()->flash('error', 'Cuentas contables no configuradas (111005 o 5195).');
            return;
        }

        DB::transaction(function() use ($e, $bankAccount, $expenseAccount) {
            // Create Journal Entry
            $je = \App\Models\JournalEntry::create([
                'number' => 'PAY-' . time(),
                'date' => now(),
                'description' => "Pago Proveedor: " . ($e->supplier->name ?? 'Gastos'),
                'total_debit' => $e->amount,
                'total_credit' => $e->amount,
                'status' => 'posted'
            ]);

            // DEBIT Expense
            \App\Models\JournalItem::create([
                'journal_entry_id' => $je->id,
                'account_id' => $expenseAccount->id,
                'debit' => $e->amount,
                'credit' => 0,
                'description' => $e->description ?: $e->category
            ]);

            // CREDIT Bank/Cash
            \App\Models\JournalItem::create([
                'journal_entry_id' => $je->id,
                'account_id' => $bankAccount->id,
                'debit' => 0,
                'credit' => $e->amount,
                'description' => "Pago Factura: " . $e->reference
            ]);

            // Create Treasury Entry
            \App\Models\TreasuryEntry::create([
                'type' => 'expense',
                'date' => now(),
                'amount' => $e->amount,
                'description' => "Pago: " . ($e->description ?: $e->category),
                'account_id' => $bankAccount->id,
                'counterpart_account_id' => $expenseAccount->id,
                'supplier_id' => $e->supplier_id,
                'journal_entry_id' => $je->id,
                'payment_method' => 'Efectivo / Banco',
                'reference_doc' => $e->reference,
            ]);

            $e->update([
                'status' => 'paid',
                'payment_method' => 'Efectivo / Banco',
                'journal_entry_id' => $je->id
            ]);
        });

        session()->flash('message', 'Gasto pagado y contabilizado exitosamente.');
    }

}
