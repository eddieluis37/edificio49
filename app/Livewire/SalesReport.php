<?php

namespace App\Livewire;

use App\Models\Rental;
use App\Models\Customer;
use App\Models\Space;
use App\Models\User;
use Livewire\Component;
use Livewire\Attributes\Computed;
use Carbon\Carbon;


/**
 * Sales Report Component - Metaverse Dashboard
 * 
 * Reporte de ventas espectacular con visualizaciones 3D,
 * animaciones cinematográficas y análisis predictivo.
 */
class SalesReport extends Component
{
    /*
    |--------------------------------------------------------------------------
    | PROPIEDADES PÚBLICAS
    |--------------------------------------------------------------------------
    */

    // Filtros
    public string $filter_period = 'month'; // today, week, month, year, custom
    public ?int $filter_user_id = null;
    public ?string $date_from = null;
    public ?string $date_to = null;

    // Comparativa
    public bool $show_comparison = true;
    public string $comparison_period = 'previous'; // previous, last_year

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
        return view('livewire.reports.sales-report', [
            'stats' => $this->stats,
            'comparison' => $this->comparison,
            'chartData' => $this->chartData,
            'vehicleBreakdown' => $this->vehicleBreakdown,
            'topSpaces' => $this->topSpaces,
            'peakHours' => $this->peakHours,
            'topCustomers' => $this->topCustomers,
            'predictions' => $this->predictions,
            'users' => $this->users,
        ]);
    }

    /*
    |--------------------------------------------------------------------------
    | COMPUTED PROPERTIES
    |--------------------------------------------------------------------------
    */

    /**
     * Estadísticas principales del período
     */
    #[Computed]
    public function stats(): array
    {
        $rentals = $this->getRentalsQuery()->get();

        $totalRevenue = $rentals->sum('total_amount');
        $totalRentals = $rentals->count();
        $avgRevenue = $totalRentals > 0 ? $totalRevenue / $totalRentals : 0;

        // Ocupación promedio
        $totalSpaces = Space::count();
        $avgOccupation = $totalSpaces > 0 ? ($totalRentals / $totalSpaces) * 100 : 0;

        // Tiempo promedio de estadía (en horas)
        $avgStayTime = $rentals->filter(fn($r) => $r->check_out)
            ->avg(fn($r) => $r->check_out->diffInHours($r->check_in));

        return [
            'total_revenue' => $totalRevenue,
            'total_rentals' => $totalRentals,
            'avg_revenue' => $avgRevenue,
            'avg_occupation' => min($avgOccupation, 100),
            'avg_stay_time' => round($avgStayTime ?? 0, 1),
            'unique_customers' => $rentals->pluck('customer_id')->unique()->count(),
        ];
    }

    /**
     * Comparativa con período anterior
     */
    #[Computed]
    public function comparison(): array
    {
        if (!$this->show_comparison) {
            return [];
        }

        // Calcular período anterior
        list($prevStart, $prevEnd) = $this->getPreviousPeriod();

        $prevRentals = Rental::query()
            ->where('status', Rental::STATUS_CLOSED)
            ->whereBetween('check_out', [$prevStart, $prevEnd])
            ->when($this->filter_user_id, fn($q) => $q->where('user_id', $this->filter_user_id))
            ->get();

        $prevRevenue = $prevRentals->sum('total_amount');
        $prevCount = $prevRentals->count();

        $currentRevenue = $this->stats['total_revenue'];
        $currentCount = $this->stats['total_rentals'];

        return [
            'prev_revenue' => $prevRevenue,
            'prev_count' => $prevCount,
            'revenue_change' => $prevRevenue > 0 ? (($currentRevenue - $prevRevenue) / $prevRevenue) * 100 : 0,
            'count_change' => $prevCount > 0 ? (($currentCount - $prevCount) / $prevCount) * 100 : 0,
            'revenue_trend' => $currentRevenue >= $prevRevenue ? 'up' : 'down',
            'count_trend' => $currentCount >= $prevCount ? 'up' : 'down',
        ];
    }

    /**
     * Datos para gráfica de ventas por día
     */
    #[Computed]
    public function chartData(): array
    {
        $rentals = $this->getRentalsQuery()->get();

        $grouped = $rentals->groupBy(function ($rental) {
            return $rental->check_out->format('Y-m-d');
        })->map(function ($group) {
            return [
                'revenue' => $group->sum('total_amount'),
                'count' => $group->count(),
            ];
        })->sortKeys();

        return [
            'labels' => $grouped->keys()->map(fn($date) => Carbon::parse($date)->format('d/m'))->toArray(),
            'revenue' => $grouped->pluck('revenue')->toArray(),
            'count' => $grouped->pluck('count')->toArray(),
        ];
    }

    /**
     * Desglose por tipo de vehículo
     */
    #[Computed]
    public function vehicleBreakdown(): array
    {
        $rentals = $this->getRentalsQuery()
            ->with('vehicle')
            ->get()
            ->filter(fn($r) => $r->vehicle !== null); //  Filtrar nulls

        if ($rentals->isEmpty()) {  // Validar vacío
            return [];
        }

        return $rentals->groupBy(fn($r) => $r->vehicle->type->value)
            ->map(function ($group, $type) {
                return [
                    'type' => $type,
                    'label' => ucfirst($type),
                    'count' => $group->count(),
                    'revenue' => $group->sum('total_amount'),
                    'percentage' => 0,
                ];
            })
            ->values()
            ->sortByDesc('revenue')
            ->values()
            ->toArray();
    }

    /**
     * Top 5 espacios más rentables
     */
    #[Computed]
    public function topSpaces(): array
    {
        return $this->getRentalsQuery()
            ->with('space')
            ->get()
            ->groupBy('space_id')
            ->map(function ($group) {
                return [
                    'space' => $group->first()->space,
                    'count' => $group->count(),
                    'revenue' => $group->sum('total_amount'),
                ];
            })
            ->sortByDesc('revenue')
            ->take(5)
            ->values()
            ->toArray();
    }

    /**
     * Horarios pico (análisis por hora)
     */
    #[Computed]
    public function peakHours(): array
    {
        $rentals = $this->getRentalsQuery()->get();

        $byHour = $rentals->groupBy(function ($rental) {
            return $rental->check_in->format('H');
        })->map(function ($group) {
            return [
                'count' => $group->count(),
                'revenue' => $group->sum('total_amount'),
            ];
        })->sortKeys();

        return [
            'labels' => $byHour->keys()->map(fn($h) => $h . ':00')->toArray(),
            'count' => $byHour->pluck('count')->toArray(),
            'revenue' => $byHour->pluck('revenue')->toArray(),
        ];
    }

    /**
     * Top 10 clientes frecuentes
     */
    #[Computed]
    public function topCustomers(): array
    {
        return $this->getRentalsQuery()
            ->with('customer')
            ->get()
            ->groupBy('customer_id')
            ->map(function ($group) {
                return [
                    'customer' => $group->first()->customer,
                    'visits' => $group->count(),
                    'revenue' => $group->sum('total_amount'),
                    'avg_ticket' => $group->avg('total_amount'),
                ];
            })
            ->sortByDesc('revenue')
            ->take(10)
            ->values()
            ->toArray();
    }

    /**
     * Predicciones simples (basadas en tendencia)
     */
    #[Computed]
    public function predictions(): array
    {
        $rentals = $this->getRentalsQuery()->get();

        if ($rentals->count() < 2) {
            return [
                'next_7_days' => 0,
                'trend' => 'insufficient_data',
            ];
        }

        $dailyAvg = $rentals->sum('total_amount') / max(1, $rentals->groupBy(fn($r) => $r->check_out->format('Y-m-d'))->count());

        return [
            'next_7_days' => $dailyAvg * 7,
            'daily_avg' => $dailyAvg,
            'trend' => $dailyAvg > 0 ? 'growing' : 'stable',
        ];
    }

    /**
     * Lista de usuarios
     */
    #[Computed]
    public function users()
    {
        return User::orderBy('name')->get();
    }

    /*
    |--------------------------------------------------------------------------
    | UTILIDADES
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

        return $query;
    }

    /**
     * Establecer fechas por defecto según período
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
     * Calcular período anterior para comparación
     */
    protected function getPreviousPeriod(): array
    {
        $start = Carbon::parse($this->date_from);
        $end = Carbon::parse($this->date_to);
        $diff = $end->diffInDays($start);

        $prevEnd = $start->copy()->subDay();
        $prevStart = $prevEnd->copy()->subDays($diff);

        return [$prevStart, $prevEnd];
    }

    /*
    |--------------------------------------------------------------------------
    | ACCIONES
    |--------------------------------------------------------------------------
    */

    /**
     * Actualizar período
     */
    public function updatedFilterPeriod(): void
    {
        $this->setDefaultDates();
    }

    /**
     * Exportar (Premium)
     */
    public function exportPdf(): void
    {
        $this->dispatch('show-premium-modal', type: 'pdf');
    }

    public function exportExcel(): void
    {
        $this->dispatch('show-premium-modal', type: 'excel');
    }

    /**
     * Limpiar filtros
     */
    public function clearFilters(): void
    {
        $this->filter_user_id = null;
        $this->filter_period = 'month';
        $this->setDefaultDates();
    }
}
