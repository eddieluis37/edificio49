<?php

declare(strict_types=1);

namespace App\Livewire;

use App\Models\Space;
use App\Models\Type;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\On;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Validate;

/**
 * ParkingSpaces Index Component
 * 
 * Componente para gestionar los espacios físicos del estacionamiento.
 * Permite crear, editar, eliminar y buscar espacios (cajones).
 */
class ParkingSpaces extends Component
{
    use WithPagination;

    /*
    |--------------------------------------------------------------------------
    | PROPIEDADES PÚBLICAS
    |--------------------------------------------------------------------------
    */

    #[Validate('required|string|max:20')]
    public string $code = '';

    #[Validate('required|string|min:3|max:200')]
    public string $description = '';

    #[Validate('required|integer|exists:types,id')]
    public int $type_id = 0;

    #[Validate('required|string|in:available,occupied,maintenance,reserved')]
    public string $status = 'available';

    #[Validate('nullable|string|max:500')]
    public string $notes = '';

    public ?int $selected_id = null;
    public string $search = '';
    public bool $slideOverOpen = false;
    public bool $isEditMode = false;

    /*
    |--------------------------------------------------------------------------
    | CICLO DE VIDA
    |--------------------------------------------------------------------------
    */

    /**
     * Inicialización del componente.
     * 
     * Estrategia: Verificar permisos al cargar el componente.
     */
    public function mount(): void
    {
        // Si usas autorización, descomenta:
        // $this->authorize('viewAny', Space::class);
    }

    /**
     * Renderizar la vista.
     * 
     * Estrategia: Usar computed properties para queries optimizadas.
     */
    public function render()
    {
        return view('livewire.parking-spaces', [
            'spaces' => $this->spaces,
            'stats' => $this->stats,
            'types' => $this->types,
        ]);
    }

    /*
    |--------------------------------------------------------------------------
    | COMPUTED PROPERTIES
    |--------------------------------------------------------------------------
    */

    /**
     * Get parking spaces with filters and pagination.
     * 
     * Estrategia: Usar eager loading para evitar N+1 queries.
     * Se incluye el tipo de vehículo y el conteo de rentas.
     */
    #[Computed]
    public function spaces()
    {
        return Space::query()
            ->with('type') // Eager loading para evitar N+1 // ->withCount('rentals') // Contar historial de rentas           
            ->search($this->search)
            ->ordered()
            ->paginate(12);
    }

    /**
     * Get parking spaces statistics.
     * 
     * Estrategia: Calcular estadísticas en una sola query para dashboard.
     */
    #[Computed]
    public function stats(): array
    {
        return [
            'total_spaces' => Space::count(),
            'available_spaces' => Space::available()->count(),
            'occupied_spaces' => Space::occupied()->count(),
            'maintenance_spaces' => Space::maintenance()->count(),
        ];
    }

    /**
     * Get all vehicle types for dropdown.
     * 
     * Estrategia: Cachear tipos ya que no cambian frecuentemente.
     */
    #[Computed]
    public function types()
    {
        return Type::all(); //ordered()->get();
    }

    /*
    |--------------------------------------------------------------------------
    | ACCIONES CRUD
    |--------------------------------------------------------------------------
    */

    /**
     * Open create form.
     * 
     * Estrategia: Limpiar formulario y establecer valores por defecto.
     */
    public function create(): void
    {
        // Si usas autorización, descomenta:
        // $this->authorize('create', Space::class);

        $this->resetForm();
        $this->isEditMode = false;
        $this->slideOverOpen = true;
    }

    /**
     * Open edit form for a parking space.
     * 
     * Estrategia: Cargar datos existentes al formulario.
     * 
     * @param int $id
     */
    public function edit(int $id): void
    {
        $space = Space::findOrFail($id);

        // Si usas autorización, descomenta:
        // $this->authorize('update', $space);

        $this->selected_id = $id;
        $this->code = $space->code;
        $this->description = $space->description;
        $this->type_id = $space->type_id;
        $this->status = $space->status;
        $this->notes = $space->notes ?? '';
        $this->isEditMode = true;
        $this->slideOverOpen = true;
    }

    /**
     * Store or update parking space.
     * 
     * Estrategia: Validar primero, luego decidir si crear o actualizar.
     */
    public function store(): void
    {
        $this->validate();

        try {
            if ($this->isEditMode) {
                $this->update();
            } else {
                $this->createNew();
            }

            $this->dispatch('notify', [
                'type' => 'success',
                'message' => $this->isEditMode
                    ? 'Espacio actualizado exitosamente'
                    : 'Espacio creado exitosamente'
            ]);

            $this->closeSlideOver();
            $this->resetForm();
        } catch (\Exception $e) {
            $this->dispatch('notify', [
                'type' => 'error',
                'message' => 'Error al guardar: ' . $e->getMessage()
            ]);
        }
    }

    /**
     * Create a new parking space.
     * 
     * Estrategia: Validar unicidad del código antes de crear.
     */
    protected function createNew(): void
    {
        // Si usas autorización, descomenta:
        // $this->authorize('create', Space::class);

        // Validar código único
        if (Space::where('code', $this->code)->exists()) {
            throw new \Exception('Ya existe un espacio con este código');
        }

        Space::create([
            'code' => $this->code,
            'description' => $this->description,
            'type_id' => $this->type_id,
            'status' => $this->status,
            'notes' => $this->notes,
        ]);
    }

    /**
     * Update existing parking space.
     * 
     * Estrategia: Validar código único excluyendo el registro actual.
     */
    protected function update(): void
    {
        $space = Space::findOrFail($this->selected_id);

        // Si usas autorización, descomenta:
        // $this->authorize('update', $space);

        // Validar código único (excepto el actual)
        if (Space::where('code', $this->code)
            ->where('id', '!=', $this->selected_id)
            ->exists()
        ) {
            throw new \Exception('Ya existe un espacio con este código');
        }

        $space->update([
            'code' => $this->code,
            'description' => $this->description,
            'type_id' => $this->type_id,
            'status' => $this->status,
            'notes' => $this->notes,
        ]);
    }

    /**
     * Delete a parking space.
     * 
     * Estrategia: Verificar que no tenga rentas activas antes de eliminar.
     * 
     * @param int $id
     */
    #[On('delete')]
    public function delete(int $id): void
    {
        $space = Space::findOrFail($id);

        // Si usas autorización, descomenta:
        // $this->authorize('delete', $space);

        try {
            // Verificar si tiene rentas (historial)
            if ($space->rentals()->count() > 0) {
                $this->dispatch('notify', [
                    'type' => 'error',
                    'message' => 'No se puede eliminar porque tiene historial de rentas'
                ]);
                return;
            }

            // Verificar si está ocupado actualmente
            if ($space->is_occupied) {
                $this->dispatch('notify', [
                    'type' => 'error',
                    'message' => 'No se puede eliminar un espacio que está ocupado'
                ]);
                return;
            }

            $space->delete();

            $this->dispatch('notify', [
                'type' => 'success',
                'message' => 'Espacio eliminado exitosamente'
            ]);
        } catch (\Exception $e) {
            $this->dispatch('notify', [
                'type' => 'error',
                'message' => 'Error al eliminar: ' . $e->getMessage()
            ]);
        }
    }

    /*
    |--------------------------------------------------------------------------
    | ACCIONES DE ESTADO
    |--------------------------------------------------------------------------
    */

    /**
     * Change parking space status.
     * 
     * Estrategia: Permitir cambios rápidos de estado desde la vista.
     * 
     * @param int $id
     * @param string $status
     */
    public function changeStatus(int $id, string $status): void
    {
        try {
            $space = Space::findOrFail($id);

            // Validar que el estado sea válido
            if (!in_array($status, ['available', 'occupied', 'maintenance', 'reserved'])) {
                throw new \Exception('Estado no válido');
            }

            $space->update(['status' => $status]);

            $this->dispatch('notify', [
                'type' => 'success',
                'message' => 'Estado actualizado exitosamente'
            ]);
        } catch (\Exception $e) {
            $this->dispatch('notify', [
                'type' => 'error',
                'message' => 'Error al cambiar estado: ' . $e->getMessage()
            ]);
        }
    }

    /*
    |--------------------------------------------------------------------------
    | UTILIDADES
    |--------------------------------------------------------------------------
    */

    /**
     * Clear search input.
     * 
     * Estrategia: Limpiar búsqueda y resetear paginación.
     */
    public function clearSearch(): void
    {
        $this->reset('search');
        $this->resetPage();
    }

    /**
     * Close slide-over panel.
     * 
     * Estrategia: Cerrar panel y limpiar formulario.
     */
    public function closeSlideOver(): void
    {
        $this->slideOverOpen = false;
        $this->resetForm();
    }

    /**
     * Reset form fields.
     * 
     * Estrategia: Restaurar todos los campos a sus valores por defecto.
     */
    protected function resetForm(): void
    {
        $this->reset([
            'code',
            'description',
            'type_id',
            'status',
            'notes',
            'selected_id',
            'isEditMode',
        ]);

        // Establecer valores por defecto
        $this->type_id = 0;
        $this->status = 'available';

        $this->resetValidation();
    }

    /*
    |--------------------------------------------------------------------------
    | PAGINACIÓN
    |--------------------------------------------------------------------------
    */

    /**
     * Reset pagination when searching.
     * 
     * Estrategia: Al buscar, volver a la primera página.
     */
    public function updatingSearch(): void
    {
        $this->resetPage();
    }
}
