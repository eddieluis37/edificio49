<?php

declare(strict_types=1);

namespace App\Livewire;

use App\Models\Customer;
use App\Models\Rental;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Computed;
use Livewire\Attributes\On;
use Livewire\Attributes\Validate;
use Illuminate\Support\Facades\DB;

/**
 * Customers Manager Component
 * 
 * Gestión completa de clientes del estacionamiento.
 * Diseño tipo cards, responsive, con filtros y búsqueda en tiempo real.
 */
class CustomersManager extends Component
{
    use WithPagination;

    /*
    |--------------------------------------------------------------------------
    | PROPIEDADES PÚBLICAS
    |--------------------------------------------------------------------------
    */

    // Modal states
    public bool $showModal = false;
    public bool $showDetailModal = false;
    public bool $isEditing = false;

    // Customer ID para edición y detalle
    public ?int $customer_id = null;

    // Form fields
    #[Validate('required|string|min:3|max:200')]
    public string $name = '';

    #[Validate('nullable|email|max:200')]
    public string $email = '';

    #[Validate('nullable|string|max:20')]
    public string $phone = '';

    #[Validate('nullable|string|max:20')]
    public string $mobile = '';

    #[Validate('nullable|string|max:500')]
    public string $address = '';

    #[Validate('nullable|string|max:100')]
    public string $city = '';

    #[Validate('nullable|string|max:100')]
    public string $state = '';

    #[Validate('nullable|string|max:20')]
    public string $zip_code = '';

    #[Validate('nullable|string|max:100')]
    public string $country = 'México';

    #[Validate('nullable|string')]
    public string $notes = '';

    public bool $is_active = true;

    // Filtros y búsqueda
    public string $search = '';
    public string $filter_status = 'all'; // all, active, inactive

    // Ordenamiento
    public string $sortBy = 'created_at';
    public string $sortDirection = 'desc';

    /*
    |--------------------------------------------------------------------------
    | CICLO DE VIDA
    |--------------------------------------------------------------------------
    */

    public function mount(): void
    {
        // Inicialización
    }

    public function render()
    {
        return view('livewire.customers.customers-manager', [
            'customers' => $this->customers,
            'stats' => $this->stats,
        ]);
    }

    /*
    |--------------------------------------------------------------------------
    | COMPUTED PROPERTIES
    |--------------------------------------------------------------------------
    */

    /**
     * Obtener clientes con filtros, búsqueda y paginación
     */
    #[Computed]
    public function customers()
    {
        $query = Customer::query()
            ->withCount(['vehicles', 'rentals']);

        // Búsqueda
        if (!empty($this->search)) {
            $query->search($this->search);
        }

        // Filtro por estado
        if ($this->filter_status === 'active') {
            $query->active();
        } elseif ($this->filter_status === 'inactive') {
            $query->inactive();
        }

        // Ordenamiento
        $query->orderBy($this->sortBy, $this->sortDirection);

        return $query->paginate(10);
    }

    /**
     * Estadísticas de clientes
     */
    #[Computed]
    public function stats(): array
    {
        return [
            'total_customers' => Customer::count(),
            'active_customers' => Customer::active()->count(),
            'customers_with_vehicles' => Customer::withVehicles()->count(),
            'customers_with_rentals' => Customer::withActiveRentals()->count(),
        ];
    }

    /*
    |--------------------------------------------------------------------------
    | MODAL ACTIONS
    |--------------------------------------------------------------------------
    */

    /**
     * Abrir modal para crear cliente
     */
    public function create(): void
    {
        $this->resetForm();
        $this->isEditing = false;
        $this->showModal = true;
    }

    /**
     * Abrir modal para editar cliente
     */
    public function edit(int $customerId): void
    {
        $customer = Customer::findOrFail($customerId);

        $this->customer_id = $customer->id;
        $this->name = $customer->name;
        $this->email = $customer->email ?? '';
        $this->phone = $customer->phone ?? '';
        $this->mobile = $customer->mobile ?? '';
        $this->address = $customer->address ?? '';
        $this->city = $customer->city ?? '';
        $this->state = $customer->state ?? '';
        $this->zip_code = $customer->zip_code ?? '';
        $this->country = $customer->country ?? 'México';
        $this->notes = $customer->notes ?? '';
        $this->is_active = $customer->is_active;

        $this->isEditing = true;
        $this->showModal = true;
    }

    /**
     * Cerrar modal de formulario
     */
    public function closeModal(): void
    {
        $this->showModal = false;
        $this->resetForm();
    }

    /**
     * Ver detalles del cliente
     */
    public function viewDetails(int $customerId): void
    {
        $this->customer_id = $customerId;
        $this->showDetailModal = true;
    }

    /**
     * Cerrar modal de detalles
     */
    public function closeDetailModal(): void
    {
        $this->showDetailModal = false;
        $this->customer_id = null;
    }

    /*
    |--------------------------------------------------------------------------
    | CRUD OPERATIONS
    |--------------------------------------------------------------------------
    */

    /**
     * Guardar cliente (crear o actualizar)
     */
    public function save(): void
    {
        // Validar todos los campos primero
        $this->validate();

        // Validar email único
        $this->validate([
            'email' => [
                'nullable',
                'email',
                'max:200',
                'unique:customers,email' . ($this->isEditing ? ',' . $this->customer_id : ''),
            ],
        ]);

        try {
            DB::beginTransaction();

            $data = [
                'name' => $this->name,
                'email' => $this->email ?: null,
                'phone' => $this->phone ?: null,
                'mobile' => $this->mobile ?: null,
                'address' => $this->address ?: null,
                'city' => $this->city ?: null,
                'state' => $this->state ?: null,
                'zip_code' => $this->zip_code ?: null,
                'country' => $this->country ?: 'México',
                'notes' => $this->notes ?: null,
                'is_active' => $this->is_active,
            ];

            if ($this->isEditing) {
                $customer = Customer::findOrFail($this->customer_id);
                $customer->update($data);
                $message = 'Cliente actualizado exitosamente';
            } else {
                Customer::create($data);
                $message = 'Cliente creado exitosamente';
            }

            DB::commit();

            $this->dispatch('notify', [
                'type' => 'success',
                'message' => $message
            ]);

            $this->closeModal();
        } catch (\Exception $e) {
            DB::rollBack();

            $this->dispatch('notify', [
                'type' => 'error',
                'message' => 'Error: ' . $e->getMessage()
            ]);
        }
    }

    /**
     * Eliminar cliente (soft delete)
     */
    public function delete(int $customerId): void
    {
        try {
            $customer = Customer::findOrFail($customerId);

            // Verificar si tiene rentas abiertas
            if ($customer->hasOpenRentals()) {
                $this->dispatch('notify', [
                    'type' => 'error',
                    'message' => 'No se puede eliminar un cliente con rentas abiertas'
                ]);
                return;
            }

            $customer->delete();

            $this->dispatch('notify', [
                'type' => 'success',
                'message' => 'Cliente eliminado exitosamente'
            ]);
        } catch (\Exception $e) {
            $this->dispatch('notify', [
                'type' => 'error',
                'message' => 'Error al eliminar: ' . $e->getMessage()
            ]);
        }
    }

    /**
     * Activar/Desactivar cliente
     */
    public function toggleStatus(int $customerId): void
    {
        try {
            $customer = Customer::findOrFail($customerId);

            if ($customer->is_active) {
                $customer->deactivate();
                $message = 'Cliente desactivado';
            } else {
                $customer->activate();
                $message = 'Cliente activado';
            }

            $this->dispatch('notify', [
                'type' => 'success',
                'message' => $message
            ]);
        } catch (\Exception $e) {
            $this->dispatch('notify', [
                'type' => 'error',
                'message' => 'Error: ' . $e->getMessage()
            ]);
        }
    }

    /*
    |--------------------------------------------------------------------------
    | UTILIDADES
    |--------------------------------------------------------------------------
    */

    /**
     * Resetear formulario
     */
    protected function resetForm(): void
    {
        $this->customer_id = null;
        $this->name = '';
        $this->email = '';
        $this->phone = '';
        $this->mobile = '';
        $this->address = '';
        $this->city = '';
        $this->state = '';
        $this->zip_code = '';
        $this->country = 'México';
        $this->notes = '';
        $this->is_active = true;
        $this->resetValidation();
    }

    /**
     * Cambiar ordenamiento
     */
    public function sortBy(string $field): void
    {
        if ($this->sortBy === $field) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortBy = $field;
            $this->sortDirection = 'asc';
        }
    }

    /**
     * Limpiar búsqueda
     */
    public function clearSearch(): void
    {
        $this->search = '';
        $this->resetPage();
    }

    /**
     * Actualizar búsqueda
     */
    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    /**
     * Actualizar filtro de estado
     */
    public function updatingFilterStatus(): void
    {
        $this->resetPage();
    }

    /*
    |--------------------------------------------------------------------------
    | GETTERS PARA VISTA DETALLE
    |--------------------------------------------------------------------------
    */

    /**
     * Obtener cliente actual para vista detalle
     */
    public function getCurrentCustomer(): ?Customer
    {
        if (!$this->customer_id) {
            return null;
        }

        return Customer::with(['vehicles', 'rentals.space', 'rentals.rate'])
            ->withCount(['vehicles', 'rentals'])
            ->find($this->customer_id);
    }
}
