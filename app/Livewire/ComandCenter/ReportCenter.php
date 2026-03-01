<?php


namespace App\Livewire\ComandCenter;

use App\Models\Rental;
use App\Models\CashClosure;
use App\Models\User;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Computed;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

/**
 * Command Center - Reporte Híbrido
 * 
 * Reporte unificado de ventas por usuario y cortes de caja
 * con vistas de resumen y detalle intercambiables.
 */
class ReportCenter extends Component
{
    use WithPagination;

    /*
    |--------------------------------------------------------------------------
    | PROPIEDADES PÚBLICAS
    |--------------------------------------------------------------------------
    */

    // Tabs principales
    public string $active_tab = 'sales'; // sales, closures, unified

    // Modo de visualización
    public string $view_mode = 'summary'; // summary, detail

    // Filtros
    public ?int $filter_user_id = null;
    public string $filter_period = 'month'; // today, week, month, year, custom
    public ?string $date_from = null;
    public ?string $date_to = null;
    public string $search = '';

    // Paginación
    public int $per_page = 10;

    /*
    |--------------------------------------------------------------------------
    | CICLO DE VIDA
    |--------------------------------------------------------------------------
    */

    public function mount(): void
    {
        $this->setDefaultDates();
    }

    public function render()
    {
        return view('livewire.comand-center.report-center', [
            'users' => $this->users,
            'kpis' => $this->kpis,
            'salesByUser' => $this->salesByUser,
            'closuresByUser' => $this->closuresByUser,
            'salesDetails' => $this->salesDetails,
            'closuresDetails' => $this->closuresDetails,
            'chartData' => $this->chartData,
            'insights' => $this->insights,
        ]);
    }

    /*
    |--------------------------------------------------------------------------
    | COMPUTED PROPERTIES
    |--------------------------------------------------------------------------
    */

    /**
     * Lista de usuarios
     */
    #[Computed]
    public function users()
    {
        return User::orderBy('name')->get();
    }

    /**
     * KPIs principales según tab activo
     */
    #[Computed]
    public function kpis(): array
    {
        switch ($this->active_tab) {
            case 'sales':
                return $this->getSalesKpis();
            case 'closures':
                return $this->getClosuresKpis();
            case 'unified':
                return $this->getUnifiedKpis();
            default:
                return [];
        }
    }

    /**
     * KPIs de ventas
     */
    protected function getSalesKpis(): array
    {
        $rentals = $this->getRentalsQuery()->get();

        return [
            'total_revenue' => $rentals->sum('total_amount'),
            'total_rentals' => $rentals->count(),
            'avg_ticket' => $rentals->count() > 0 ? $rentals->avg('total_amount') : 0,
            'unique_users' => $rentals->pluck('user_id')->unique()->count(),
        ];
    }

    /**
     * KPIs de cortes
     */
    protected function getClosuresKpis(): array
    {
        $closures = $this->getClosuresQuery()->get();

        return [
            'total_closures' => $closures->count(),
            'total_expected' => $closures->sum('expected_cash'),
            'total_real' => $closures->sum('real_cash'),
            'total_difference' => $closures->sum('difference'),
            'avg_difference' => $closures->count() > 0 ? $closures->avg('difference') : 0,
        ];
    }

    /**
     * KPIs unificados
     */
    protected function getUnifiedKpis(): array
    {
        $sales = $this->getSalesKpis();
        $closures = $this->getClosuresKpis();

        return [
            'total_revenue' => $sales['total_revenue'],
            'total_rentals' => $sales['total_rentals'],
            'total_closures' => $closures['total_closures'],
            'total_difference' => $closures['total_difference'],
            'accuracy' => $closures['total_expected'] > 0
                ? (($closures['total_expected'] - abs($closures['total_difference'])) / $closures['total_expected']) * 100
                : 0,
        ];
    }

    /**
     * Ventas agrupadas por usuario (RESUMEN)
     */
    #[Computed]
    public function salesByUser(): array
    {
        if ($this->view_mode !== 'summary' || $this->active_tab === 'closures') {
            return [];
        }

        return $this->getRentalsQuery()
            ->with('user')
            ->get()
            ->groupBy('user_id')
            ->map(function ($group) {
                return [
                    'user' => $group->first()->user,
                    'total_revenue' => $group->sum('total_amount'),
                    'total_rentals' => $group->count(),
                    'avg_ticket' => $group->avg('total_amount'),
                ];
            })
            ->sortByDesc('total_revenue')
            ->values()
            ->toArray();
    }

    /**
     * Cortes agrupados por usuario (RESUMEN)
     */
    #[Computed]
    public function closuresByUser(): array
    {
        if ($this->view_mode !== 'summary' || $this->active_tab === 'sales') {
            return [];
        }

        return $this->getClosuresQuery()
            ->with(['user', 'cashier'])
            ->get()
            ->groupBy('cashier_id')
            ->map(function ($group) {
                return [
                    'user' => $group->first()->cashier ?? $group->first()->user,
                    'total_closures' => $group->count(),
                    'total_expected' => $group->sum('expected_cash'),
                    'total_real' => $group->sum('real_cash'),
                    'total_difference' => $group->sum('difference'),
                ];
            })
            ->sortByDesc('total_closures')
            ->values()
            ->toArray();
    }

    /**
     * Detalles de ventas (DETALLE) - Paginado
     */
    #[Computed]
    public function salesDetails()
    {
        if ($this->view_mode !== 'detail' || $this->active_tab === 'closures') {
            return null;
        }

        return $this->getRentalsQuery()
            ->with(['user', 'customer', 'space', 'vehicle'])
            ->orderBy('check_out', 'desc')
            ->paginate($this->per_page);
    }

    /**
     * Detalles de cortes (DETALLE) - Paginado
     */
    #[Computed]
    public function closuresDetails()
    {
        if ($this->view_mode !== 'detail' || $this->active_tab === 'sales') {
            return null;
        }

        return $this->getClosuresQuery()
            ->with(['user', 'cashier'])
            ->orderBy('created_at', 'desc')
            ->paginate($this->per_page);
    }

    /**
     * Datos para gráficas
     */
    #[Computed]
    public function chartData(): array
    {
        if ($this->active_tab === 'sales') {
            return $this->getSalesChartData();
        } elseif ($this->active_tab === 'closures') {
            return $this->getClosuresChartData();
        } else {
            return $this->getUnifiedChartData();
        }
    }

    /**
     * Datos de gráfica de ventas
     */
    protected function getSalesChartData(): array
    {
        $rentals = $this->getRentalsQuery()->get();

        $grouped = $rentals->groupBy(function ($rental) {
            return $rental->check_out->format('Y-m-d');
        })->map(function ($group) {
            return $group->sum('total_amount');
        })->sortKeys();

        return [
            'labels' => $grouped->keys()->map(fn($d) => Carbon::parse($d)->format('d/m'))->toArray(),
            'data' => $grouped->values()->toArray(),
            'type' => 'sales',
        ];
    }

    /**
     * Datos de gráfica de cortes
     */
    protected function getClosuresChartData(): array
    {
        $closures = $this->getClosuresQuery()->get();

        $grouped = $closures->groupBy(function ($closure) {
            return $closure->created_at->format('Y-m-d');
        })->map(function ($group) {
            return [
                'expected' => $group->sum('expected_cash'),
                'real' => $group->sum('real_cash'),
            ];
        })->sortKeys();

        return [
            'labels' => $grouped->keys()->map(fn($d) => Carbon::parse($d)->format('d/m'))->toArray(),
            'expected' => $grouped->pluck('expected')->toArray(),
            'real' => $grouped->pluck('real')->toArray(),
            'type' => 'closures',
        ];
    }

    /**
     * Datos de gráfica unificada
     */
    protected function getUnifiedChartData(): array
    {
        // Combinar ambas fuentes
        return [
            'labels' => ['Lun', 'Mar', 'Mie', 'Jue', 'Vie', 'Sab', 'Dom'],
            'sales' => [],
            'closures' => [],
            'type' => 'unified',
        ];
    }

    /**
     * Insights AI automáticos
     */
    #[Computed]
    public function insights(): array
    {
        $insights = [];

        // Insight: Usuario top
        if (!empty($this->salesByUser)) {
            $top = $this->salesByUser[0] ?? null;
            if ($top) {
                $insights[] = [
                    'icon' => '🏆',
                    'text' => "{$top['user']->name} lidera con $" . number_format($top['total_revenue'], 2),
                    'type' => 'success',
                ];
            }
        }

        // Insight: Diferencia en cortes
        $closuresKpis = $this->getClosuresKpis();
        if ($closuresKpis['total_difference'] != 0) {
            $type = $closuresKpis['total_difference'] > 0 ? 'warning' : 'danger';
            $text = abs($closuresKpis['total_difference']);
            $insights[] = [
                'icon' => '⚠️',
                'text' => "Diferencia acumulada: $" . number_format($text, 2),
                'type' => $type,
            ];
        }

        // Insight: Rendimiento general
        if ($this->active_tab === 'unified') {
            $accuracy = $this->kpis['accuracy'] ?? 0;
            if ($accuracy >= 95) {
                $insights[] = [
                    'icon' => '✅',
                    'text' => "Precisión de caja: " . number_format($accuracy, 1) . "% - ¡Excelente!",
                    'type' => 'success',
                ];
            }
        }

        return $insights;
    }

    /*
    |--------------------------------------------------------------------------
    | QUERIES
    |--------------------------------------------------------------------------
    */

    /**
     * Query base de rentas con filtros
     */
    protected function getRentalsQuery()
    {
        $query = Rental::query()
            ->where('status', Rental::STATUS_CLOSED);

        // Filtro por usuario
        if ($this->filter_user_id) {
            $query->where('user_id', $this->filter_user_id);
        }

        // Filtro por fecha
        if ($this->date_from && $this->date_to) {
            $query->whereBetween('check_out', [
                Carbon::parse($this->date_from),
                Carbon::parse($this->date_to)
            ]);
        }

        // Búsqueda
        if ($this->search) {
            $query->where(function ($q) {
                $q->where('barcode', 'like', "%{$this->search}%")
                    ->orWhereHas('customer', fn($q) => $q->where('name', 'like', "%{$this->search}%"));
            });
        }

        return $query;
    }

    /**
     * Query base de cortes con filtros
     */
    protected function getClosuresQuery()
    {
        $query = CashClosure::query();

        // Filtro por usuario (cashier)
        if ($this->filter_user_id) {
            $query->where('cashier_id', $this->filter_user_id);
        }

        // Filtro por fecha
        if ($this->date_from && $this->date_to) {
            $query->whereBetween('created_at', [
                Carbon::parse($this->date_from),
                Carbon::parse($this->date_to)
            ]);
        }

        return $query;
    }

    /*
    |--------------------------------------------------------------------------
    | ACCIONES
    |--------------------------------------------------------------------------
    */

    /**
     * Cambiar tab activo
     */
    public function setTab(string $tab): void
    {
        $this->active_tab = $tab;
        $this->resetPage();
    }

    /**
     * Cambiar modo de vista
     */
    public function setViewMode(string $mode): void
    {
        $this->view_mode = $mode;
        $this->resetPage();
        $this->dispatch('chartDataUpdated');
    }

    /**
     * Actualizar período
     */
    public function updatedFilterPeriod(): void
    {
        $this->setDefaultDates();
        $this->resetPage();
    }

    /**
     * Establecer fechas por defecto
     */
    protected function setDefaultDates(): void
    {
        switch ($this->filter_period) {
            case 'today':
                $this->date_from = now()->startOfDay()->toDateTimeString();
                $this->date_to = now()->endOfDay()->toDateTimeString();
                break;
            case 'week':
                $this->date_from = now()->startOfWeek()->toDateTimeString();
                $this->date_to = now()->endOfWeek()->toDateTimeString();
                break;
            case 'month':
                $this->date_from = now()->startOfMonth()->toDateTimeString();
                $this->date_to = now()->endOfMonth()->toDateTimeString();
                break;
            case 'year':
                $this->date_from = now()->startOfYear()->toDateTimeString();
                $this->date_to = now()->endOfYear()->toDateTimeString();
                break;
        }
    }

    /**
     * Acciones de exportación (Premium)
     */
    public function exportPdf(): void
    {
        $this->dispatch('show-premium-toast', message: 'Exportar PDF disponible en versión Premium 💎');
    }

    public function exportExcel(): void
    {
        $this->dispatch('show-premium-toast', message: 'Exportar Excel disponible en versión Premium 💎');
    }

    public function printReport(): void
    {
        $this->dispatch('show-premium-toast', message: 'Imprimir disponible en versión Premium 💎');
    }

    public function shareWhatsapp(): void
    {
        $this->dispatch('show-premium-toast', message: 'Compartir por WhatsApp disponible en versión Premium 💎');
    }

    /**
     * Limpiar filtros
     */
    public function clearFilters(): void
    {
        $this->filter_user_id = null;
        $this->filter_period = 'month';
        $this->search = '';
        $this->setDefaultDates();
        $this->resetPage();
        $this->dispatch('chartDataUpdated');
    }
}
