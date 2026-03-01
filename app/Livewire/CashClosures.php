<?php

declare(strict_types=1);

namespace App\Livewire;

use App\Models\Rental;
use App\Models\User;
use App\Models\CashClosure;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Validate;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

/**
 * Cash Closures Component
 * 
 * Gestión de arqueos y corte de caja.
 * Consulta ventas directamente de la tabla rentals sin crear registros adicionales.
 */
class CashClosures extends Component
{
    use WithPagination;

    /*
    |--------------------------------------------------------------------------
    | PROPIEDADES PÚBLICAS
    |--------------------------------------------------------------------------
    */

    // Modal de corte
    public bool $showClosureModal = false;

    // Filtros
    public ?int $filter_user_id = null; // null = todos
    public string $filter_period = 'today'; // today, week, month, custom
    public ?string $date_from = null;
    public ?string $date_to = null;

    // Datos del corte
    #[Validate('required|numeric|min:0')]
    public float $real_cash = 0;

    #[Validate('nullable|string|max:500')]
    public string $notes = '';

    // Datos calculados (se llenan al abrir modal)
    public float $expected_cash = 0;
    public float $difference = 0;
    public int $total_rentals = 0;
    public int $open_tickets = 0;
    public array $closure_data = [];

    /*
    |--------------------------------------------------------------------------
    | CICLO DE VIDA
    |--------------------------------------------------------------------------
    */

    public function mount(): void
    {
        // Inicializar fechas
        $this->setDefaultDates();
    }

    public function render()
    {
        return view('livewire.closures.cash-closures', [
            'stats' => $this->stats,
            'users' => $this->users,
            'recentClosures' => $this->recentClosures,
        ]);
    }

    /*
    |--------------------------------------------------------------------------
    | COMPUTED PROPERTIES
    |--------------------------------------------------------------------------
    */

    /**
     * Estadísticas del período seleccionado
     */
    #[Computed]
    public function stats(): array
    {
        $query = $this->getBaseQuery();

        // Solo rentas cerradas para estadísticas
        $closedQuery = clone $query;
        $closedRentals = $closedQuery->where('status', Rental::STATUS_CLOSED)->get();

        // Tickets abiertos (alerta)
        $openQuery = clone $query;
        $openTickets = $openQuery->where('status', Rental::STATUS_OPEN)->count();

        return [
            'total_income' => $closedRentals->sum('total_amount'),
            'total_rentals' => $closedRentals->count(),
            'average_per_rental' => $closedRentals->count() > 0
                ? $closedRentals->avg('total_amount')
                : 0,
            'open_tickets' => $openTickets,
            'has_open_tickets' => $openTickets > 0,
        ];
    }

    /**
     * Lista de usuarios (cajeros)
     */
    #[Computed]
    public function users()
    {
        return User::orderBy('name')->get();
    }

    /**
     * Historial de cortes recientes desde la tabla cash_closures
     */
    #[Computed]
    public function recentClosures()
    {
        $query = CashClosure::query()
            ->with(['user', 'cashier'])
            ->latest('created_at');

        // Filtrar por cajero si aplica
        if ($this->filter_user_id) {
            $query->where('cashier_id', $this->filter_user_id);
        }

        return $query->take(10)->get();
    }

    /*
    |--------------------------------------------------------------------------
    | FILTROS Y PERÍODOS
    |--------------------------------------------------------------------------
    */

    /**
     * Establecer fechas por defecto (hoy)
     */
    protected function setDefaultDates(): void
    {
        $this->date_from = now()->startOfDay()->format('Y-m-d H:i:s');
        $this->date_to = now()->endOfDay()->format('Y-m-d H:i:s');
    }

    /**
     * Cambiar período de filtro
     */
    public function updatedFilterPeriod(): void
    {
        switch ($this->filter_period) {
            case 'today':
                $this->date_from = now()->startOfDay()->format('Y-m-d H:i:s');
                $this->date_to = now()->endOfDay()->format('Y-m-d H:i:s');
                break;
            case 'week':
                $this->date_from = now()->startOfWeek()->format('Y-m-d H:i:s');
                $this->date_to = now()->endOfWeek()->format('Y-m-d H:i:s');
                break;
            case 'month':
                $this->date_from = now()->startOfMonth()->format('Y-m-d H:i:s');
                $this->date_to = now()->endOfMonth()->format('Y-m-d H:i:s');
                break;
            case 'custom':
                // El usuario debe seleccionar las fechas manualmente
                break;
        }
    }

    /**
     * Obtener query base con filtros aplicados
     */
    protected function getBaseQuery()
    {
        $query = Rental::query();

        // Filtrar por usuario
        if ($this->filter_user_id) {
            $query->where('user_id', $this->filter_user_id);
        }

        // Filtrar por rango de fechas
        if ($this->date_from && $this->date_to) {
            $query->whereBetween('check_out', [
                Carbon::parse($this->date_from),
                Carbon::parse($this->date_to)
            ]);
        }

        return $query;
    }

    /*
    |--------------------------------------------------------------------------
    | PROCESO DE CORTE
    |--------------------------------------------------------------------------
    */

    /**
     * Abrir modal de corte de caja
     */
    public function openClosureModal(): void
    {
        // Calcular datos del período
        $query = $this->getBaseQuery();

        // Rentas cerradas
        $closedRentals = $query->where('status', Rental::STATUS_CLOSED)->get();

        // Tickets abiertos (alerta)
        $openTickets = $this->getBaseQuery()
            ->where('status', Rental::STATUS_OPEN)
            ->with('space')
            ->get();

        $this->expected_cash = $closedRentals->sum('total_amount');
        $this->total_rentals = $closedRentals->count();
        $this->open_tickets = $openTickets->count();

        // Guardar datos para mostrar en modal
        $this->closure_data = [
            'closed_rentals' => $closedRentals,
            'open_tickets' => $openTickets,
            'expected_cash' => $this->expected_cash,
            'total_rentals' => $this->total_rentals,
        ];

        // Resetear campos
        $this->real_cash = $this->expected_cash; // Sugerir el esperado
        $this->notes = '';
        $this->difference = 0;

        $this->showClosureModal = true;
    }

    /**
     * Calcular diferencia cuando cambia el efectivo real
     */
    public function updatedRealCash(): void
    {
        $this->difference = $this->real_cash - $this->expected_cash;
    }

    /**
     * Confirmar y cerrar corte de caja
     */
    public function confirmClosure(): void
    {
        $this->validate();

        // Si hay tickets abiertos, requiere confirmación adicional
        if ($this->open_tickets > 0) {
            $this->dispatch('confirm-closure-with-open-tickets');
            return;
        }

        $this->processClosure();
    }

    /**
     * Procesar el corte (después de confirmación)
     */
    public function processClosure(): void
    {
        try {
            DB::beginTransaction();

            $periodStart = Carbon::parse($this->date_from);
            $periodEnd = Carbon::parse($this->date_to);

            // Verificar si ya existe un corte para este período y usuario
            if (CashClosure::existsForPeriod($this->filter_user_id, $periodStart, $periodEnd)) {
                $this->dispatch('notify', [
                    'type' => 'warning',
                    'message' => '⚠️ Ya existe un corte registrado para este período y usuario'
                ]);
                return;
            }

            // Crear registro del corte
            $closure = CashClosure::createClosure([
                'cashier_id' => $this->filter_user_id,
                'period_start' => $periodStart,
                'period_end' => $periodEnd,
                'expected_cash' => $this->expected_cash,
                'total_rentals' => $this->total_rentals,
                'real_cash' => $this->real_cash,
                'open_tickets' => $this->open_tickets,
                'notes' => $this->notes,
            ]);

            DB::commit();

            $userName = $this->filter_user_id
                ? User::find($this->filter_user_id)->name
                : 'Todos los usuarios';

            $message = "✅ Corte #{$closure->id} registrado: {$userName} | " .
                "Esperado: $" . number_format($this->expected_cash, 2) . " | " .
                "Real: $" . number_format($this->real_cash, 2);

            if ($this->difference != 0) {
                $type = $this->difference > 0 ? 'Sobrante' : 'Faltante';
                $message .= " | {$type}: $" . number_format(abs($this->difference), 2);
            }

            $this->dispatch('notify', [
                'type' => $this->difference >= 0 ? 'success' : 'warning',
                'message' => $message
            ]);

            $this->closeClosureModal();
        } catch (\Exception $e) {
            DB::rollBack();

            $this->dispatch('notify', [
                'type' => 'error',
                'message' => 'Error al procesar corte: ' . $e->getMessage()
            ]);
        }
    }

    /**
     * Cerrar modal de corte
     */
    public function closeClosureModal(): void
    {
        $this->showClosureModal = false;
        $this->reset(['real_cash', 'notes', 'expected_cash', 'difference', 'total_rentals', 'open_tickets', 'closure_data']);
        $this->resetValidation();
    }

    /*
    |--------------------------------------------------------------------------
    | ACCIONES ADICIONALES
    |--------------------------------------------------------------------------
    */

    /**
     * Exportar a PDF (funcionalidad premium)
     */
    public function exportPdf(): void
    {
        $this->dispatch('notify', [
            'type' => 'info',
            'message' => '💎 Exportar a PDF es una función Premium. Próximamente disponible.'
        ]);
    }

    /**
     * Limpiar filtros
     */
    public function clearFilters(): void
    {
        $this->filter_user_id = null;
        $this->filter_period = 'today';
        $this->setDefaultDates();
    }

    /*
    |--------------------------------------------------------------------------
    | UTILIDADES
    |--------------------------------------------------------------------------
    */

    /**
     * Obtener nombre del período actual
     */
    public function getPeriodName(): string
    {
        switch ($this->filter_period) {
            case 'today':
                return 'Hoy';
            case 'week':
                return 'Esta Semana';
            case 'month':
                return 'Este Mes';
            case 'custom':
                if ($this->date_from && $this->date_to) {
                    return Carbon::parse($this->date_from)->format('d/m/Y') . ' - ' .
                        Carbon::parse($this->date_to)->format('d/m/Y');
                }
                return 'Personalizado';
            default:
                return 'Hoy';
        }
    }
}
