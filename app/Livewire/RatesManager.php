<?php



namespace App\Livewire;

use App\Models\Rate;
use App\Models\Type;
use App\Models\Rental;
use Livewire\Component;
use Livewire\Attributes\On;
use Livewire\WithPagination;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Validate;
use Illuminate\Support\Facades\Log;



/**
 * Rates Index Component
 * 
 * Componente para gestionar las tarifas del estacionamiento.
 * Permite crear, editar, eliminar y buscar tarifas por tipo de vehículo.
 */
class RatesManager extends Component
{
    use WithPagination;

    /*
    |--------------------------------------------------------------------------
    | PROPIEDADES PÚBLICAS
    |--------------------------------------------------------------------------
    */

    #[Validate('required|integer|exists:types,id')]
    public int $type_id = 0;

    #[Validate('required|string|min:3|max:100')]
    public string $description = '';

    #[Validate('required|numeric|min:0.01|max:999999.99')]
    public float $price = 0;

    #[Validate('nullable|integer|min:1|max:525600')]
    public ?int $time = null;

    #[Validate('required|string|in:hourly,daily,monthly,fractional')]
    public string $rate_type = 'hourly';

    #[Validate('boolean')]
    public bool $active = true;

    public ?int $selected_id = null;
    public string $search = '';
    public string $filter_type = 'all'; // all, hourly, daily, monthly, fractional
    public string $filter_status = 'all'; // all, active, inactive
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
        // $this->authorize('viewAny', Rate::class);
    }

    /**
     * Renderizar la vista.
     * 
     * Estrategia: Usar computed properties para queries optimizadas.
     */
    public function render()
    {
        return view('livewire.rates-manager', [
            'rates' => $this->rates,
            'stats' => $this->stats,
            'types' => $this->types,
            'rateTypes' => Rate::getRateTypes(),
        ]);
    }

    /*
    |--------------------------------------------------------------------------
    | COMPUTED PROPERTIES
    |--------------------------------------------------------------------------
    */

    /**
     * Get rates with filters and pagination.
     * 
     * Estrategia: Usar eager loading para evitar N+1 queries.
     * Aplicar filtros dinámicos según la selección del usuario.
     */
    #[Computed]
    public function rates()
    {
        $query = Rate::query()
            ->with('type') // Eager loading para evitar N+1
            ->search($this->search);

        // Filtrar por tipo de tarifa
        if ($this->filter_type !== 'all') {
            $query->byRateType($this->filter_type);
        }

        // Filtrar por estado
        if ($this->filter_status === 'active') {
            $query->active();
        } elseif ($this->filter_status === 'inactive') {
            $query->inactive();
        }

        return $query->orderBy('rate_type')
            ->orderBy('type_id')
            ->orderBy('time')
            ->paginate(12);
    }

    /**
     * Get rates statistics.
     * 
     * Estrategia: Calcular estadísticas en queries separadas para dashboard.
     */
    #[Computed]
    public function stats(): array
    {
        return [
            'total_rates' => Rate::count(),
            'active_rates' => Rate::active()->count(),
            'inactive_rates' => Rate::inactive()->count(),
            'hourly_rates' => Rate::hourly()->count(),
            'daily_rates' => Rate::daily()->count(),
            'monthly_rates' => Rate::monthly()->count(),
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
        return Type::orderBy('name')->get();
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
        // $this->authorize('create', Rate::class);

        $this->resetForm();
        $this->isEditMode = false;
        $this->slideOverOpen = true;
    }

    /**
     * Open edit form for a rate.
     * 
     * Estrategia: Cargar datos existentes al formulario.
     * 
     * @param int $id
     */
    public function edit(int $id): void
    {
        $rate = Rate::findOrFail($id);

        // Si usas autorización, descomenta:
        // $this->authorize('update', $rate);

        $this->selected_id = $id;
        $this->type_id = $rate->type_id;
        $this->description = $rate->description;
        $this->price = (float) $rate->price;
        $this->time = $rate->time;
        $this->rate_type = $rate->rate_type;
        $this->active = $rate->active;
        $this->isEditMode = true;
        $this->slideOverOpen = true;
    }

    /**
     * Store or update rate.
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
                    ? 'Tarifa actualizada exitosamente'
                    : 'Tarifa creada exitosamente'
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
     * Create a new rate.
     * 
     * Estrategia: Establecer tiempo por defecto según el tipo de tarifa.
     */
    protected function createNew(): void
    {
        // Si usas autorización, descomenta:
        // $this->authorize('create', Rate::class);

        // Si no se especificó tiempo, usar el tiempo por defecto del tipo
        if (is_null($this->time) || $this->time === 0) {
            $this->time = Rate::getDefaultTime($this->rate_type);
        }

        Rate::create([
            'type_id' => $this->type_id,
            'description' => $this->description,
            'price' => $this->price,
            'time' => $this->time,
            'rate_type' => $this->rate_type,
            'active' => $this->active,
        ]);
    }

    /**
     * Update existing rate.
     * 
     * Estrategia: Actualizar solo los campos modificados.
     */
    protected function update(): void
    {
        $rate = Rate::findOrFail($this->selected_id);

        // Si usas autorización, descomenta:
        // $this->authorize('update', $rate);

        $rate->update([
            'type_id' => $this->type_id,
            'description' => $this->description,
            'price' => $this->price,
            'time' => $this->time,
            'rate_type' => $this->rate_type,
            'active' => $this->active,
        ]);
    }

    /**
     * Delete a rate.
     * 
     * Estrategia: Soft delete para mantener historial.
     * 
     * @param int $id
     */
    #[On('delete')]
    public function delete(int $id): void
    {
        $rate = Rate::findOrFail($id);

        // Si usas autorización, descomenta:
        // $this->authorize('delete', $rate);

        try {
            // TODO: Verificar si tiene rentas asociadas cuando exista el modelo Rental
            // if ($rate->rentals()->exists()) {
            //     $this->dispatch('notify', [
            //         'type' => 'error',
            //         'message' => 'No se puede eliminar porque tiene rentas asociadas'
            //     ]);
            //     return;
            // }

            $rate->delete();

            $this->dispatch('notify', [
                'type' => 'success',
                'message' => 'Tarifa eliminada exitosamente'
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
     * Toggle rate active status.
     * 
     * Estrategia: Permitir activar/desactivar desde la vista.
     * 
     * @param int $id
     */
    public function toggleActive(int $id): void
    {
        try {
            $rate = Rate::findOrFail($id);

            // Si usas autorización, descomenta:
            // $this->authorize('update', $rate);

            $rate->toggleActive();

            $this->dispatch('notify', [
                'type' => 'success',
                'message' => $rate->isActive()
                    ? 'Tarifa activada exitosamente'
                    : 'Tarifa desactivada exitosamente'
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
    | UTILIDADES DE FORMULARIO
    |--------------------------------------------------------------------------
    */

    /**
     * Update time field when rate type changes.
     * 
     * Estrategia: Auto-completar el tiempo según el tipo de tarifa seleccionado.
     */
    public function updatedRateType(): void
    {
        // Solo actualizar si no hay un valor personalizado
        if (is_null($this->time) || $this->time === 0) {
            $this->time = Rate::getDefaultTime($this->rate_type);
        }
    }

    /**
     * Set default time for rate type.
     * 
     * Estrategia: Botón para resetear al tiempo por defecto.
     */
    public function setDefaultTime(): void
    {
        $this->time = Rate::getDefaultTime($this->rate_type);
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
     * Clear all filters.
     * 
     * Estrategia: Resetear todos los filtros a sus valores por defecto.
     */
    public function clearFilters(): void
    {
        $this->reset(['search', 'filter_type', 'filter_status']);
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
            'type_id',
            'description',
            'price',
            'time',
            'rate_type',
            'active',
            'selected_id',
            'isEditMode',
        ]);

        // Establecer valores por defecto
        $this->type_id = 0;
        $this->price = 0;
        $this->rate_type = 'hourly';
        $this->active = true;
        $this->time = Rate::getDefaultTime('hourly');

        $this->resetValidation();
    }

    /**
     * Format minutes to human-readable string.
     * 
     * Estrategia: Helper para mostrar tiempo legible en el formulario.
     * 
     * @param int|null $minutes
     * @return string
     */
    public function formatMinutes(?int $minutes): string
    {
        if (is_null($minutes) || $minutes === 0) {
            return 'N/A';
        }

        // Months (30 days)
        if ($minutes >= Rate::MINUTES_PER_MONTH) {
            $months = floor($minutes / Rate::MINUTES_PER_MONTH);
            $remainder = $minutes % Rate::MINUTES_PER_MONTH;

            if ($remainder === 0) {
                return $months . ($months === 1 ? ' mes' : ' meses');
            }

            $days = floor($remainder / Rate::MINUTES_PER_DAY);
            if ($days > 0) {
                return $months . ($months === 1 ? ' mes' : ' meses') . ' y ' . $days . ($days === 1 ? ' día' : ' días');
            }
        }

        // Days
        if ($minutes >= Rate::MINUTES_PER_DAY) {
            $days = floor($minutes / Rate::MINUTES_PER_DAY);
            $remainder = $minutes % Rate::MINUTES_PER_DAY;

            if ($remainder === 0) {
                return $days . ($days === 1 ? ' día' : ' días');
            }

            $hours = floor($remainder / Rate::MINUTES_PER_HOUR);
            if ($hours > 0) {
                return $days . ($days === 1 ? ' día' : ' días') . ' y ' . $hours . ($hours === 1 ? ' hora' : ' horas');
            }
        }

        // Hours
        if ($minutes >= Rate::MINUTES_PER_HOUR) {
            $hours = floor($minutes / Rate::MINUTES_PER_HOUR);
            $mins = $minutes % Rate::MINUTES_PER_HOUR;

            if ($mins > 0) {
                return $hours . ($hours === 1 ? ' hora' : ' horas') . ' y ' . $mins . ' min';
            }

            return $hours . ($hours === 1 ? ' hora' : ' horas');
        }

        // Minutes only
        return $minutes . ' minutos';
    }

    
    /*
    |--------------------------------------------------------------------------
    | PAGINACIÓN
    |--------------------------------------------------------------------------
    */

    /**
     * Reset pagination when searching.
     * 
     * Estrategia: Al buscar o filtrar, volver a la primera página.
     */
    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    public function updatingFilterType(): void
    {
        $this->resetPage();
    }

    public function updatingFilterStatus(): void
    {
        $this->resetPage();
    }
}
