<?php


namespace App\Livewire;

use App\Models\Rate;
use App\Models\Type;
use App\Models\Space;
use App\Models\Rental;
use Livewire\Component;
use Livewire\Attributes\On;
use Livewire\WithPagination;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Validate;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Services\Printing\PrinterService;

/**
 * Rentals Component
 * 
 * Componente estilo Kiosko Táctil para gestión de rentas de estacionamiento.
 * Optimizado para operadores con pantallas táctiles y scanners de código de barras.
 */
class RentalsManager extends Component
{
    use WithPagination;
    /*
    |--------------------------------------------------------------------------
    | PROPIEDADES PÚBLICAS
    |--------------------------------------------------------------------------
    */

    // Navegación y vistas
    public string $currentView = 'dashboard'; // dashboard, check-in, check-out, quick-ticket

    // Scanner de código de barras
    public string $barcode = '';

    // Check-In (Entrada)
    #[Validate('nullable|integer|exists:parking_spaces,id')]
    public ?int $selected_space_id = null;

    #[Validate('nullable|string|max:200')]
    public string $vehicle_description = '';

    // Check-Out (Salida)
    public ?Rental $current_rental = null;
    public float $calculated_amount = 0;
    public float $paid_amount = 0;
    public float $change_amount = 0;

    // Filtros de espacios
    public string $filter_type = 'all'; // all, auto, moto, etc.
    public string $filter_status = 'all'; // all, available, occupied

    // Modal de confirmación
    public bool $showConfirmModal = false;
    public string $confirmAction = '';
    public array $confirmData = [];

    public bool $autoRefresh = true;


    // Propiedades para impresión
    public $showPrintModal = false;
    public $printingRentalId = null;
    public $printPreviewHtml = '';
    public $printType = 'entry'; // 'entry' o 'exit'
    public $printerConfigured = false;

    protected PrinterService $printerService;
    /*
    |--------------------------------------------------------------------------
    | CICLO DE VIDA
    |--------------------------------------------------------------------------
    */

    //  DEPENDENCY INJECTION
    // Puedes emplear la injection cuando necesites utilizar más de una ocación los métodos del servicio,
    // en caso de utilizar solo una vez, puedes crear una instancia del servicio local dentro del método
    public function boot(PrinterService $printerService)
    {
        $this->printerService = $printerService;
    }

    public function mount(): void
    {
        // Inicialización
        $this->resetCheckOut();
        // Verificar si hay impresora configurada
        $this->checkPrinterStatus();
    }

    public function render()
    {
        return view('livewire.rentals.rentals-manager', [
            'spaces' => $this->spaces,
            'stats' => $this->stats,
            'types' => $this->types,
            'recentRentals' => $this->recentRentals,
        ]);
    }

    /*
    |--------------------------------------------------------------------------
    | COMPUTED PROPERTIES
    |--------------------------------------------------------------------------
    */

    /**
     * Obtener espacios con filtros y estado de ocupación
     */
    #[Computed]
    public function spaces()
    {
        $query = Space::query()
            ->with(['type', 'currentRental.rate'])
            ->ordered();

        // Filtrar por tipo
        if ($this->filter_type !== 'all') {
            $query->whereHas('type', function ($q) {
                $q->where('name', 'like', '%' . $this->filter_type . '%');
            });
        }

        // Filtrar por estado
        if ($this->filter_status === 'available') {
            $query->available();
        } elseif ($this->filter_status === 'occupied') {
            $query->occupied();
        }

        return $query->get()->map(function ($space) {
            // Obtener renta activa si existe
            $activeRental = Rental::where('space_id', $space->id)
                ->where('status', Rental::STATUS_OPEN)
                ->with('rate')
                ->first();

            $space->active_rental = $activeRental;
            $space->rental_time = $activeRental ? $activeRental->formatted_time : null;
            $space->rental_barcode = $activeRental ? $activeRental->barcode : null;

            return $space;
        });
    }

    /**
     * Estadísticas del dashboard
     */
    #[Computed]
    public function stats(): array
    {
        return [
            'total_spaces' => Space::count(),
            'available_spaces' => Space::available()->count(),
            'occupied_spaces' => Space::occupied()->count(),
            'today_rentals' => Rental::today()->count(),
            'today_income' => Rental::today()->closed()->sum('total_amount'),
            'active_rentals' => Rental::open()->count(),
        ];
    }

    /**
     * Tipos de vehículos
     */
    #[Computed]
    public function types()
    {
        return Type::orderBy('name')->get();
    }

    /**
     * Rentas recientes
     */
    #[Computed]
    public function recentRentals()
    {
        return Rental::with(['space', 'rate', 'user'])
            ->latest('check_in')
            ->take(5)
            ->get();
    }

    /*
    |--------------------------------------------------------------------------
    | NAVEGACIÓN ENTRE VISTAS
    |--------------------------------------------------------------------------
    */

    /**
     * Cambiar vista actual
     */
    public function changeView(string $view): void
    {
        $this->currentView = $view;
        $this->resetInputs();
    }

    /**
     * Volver al dashboard
     */
    public function backToDashboard(): void
    {
        $this->currentView = 'dashboard';
        $this->resetInputs();
    }

    /*
    |--------------------------------------------------------------------------
    | CHECK-IN (ENTRADA)
    |--------------------------------------------------------------------------
    */

    /**
     * Abrir modal de check-in para un espacio
     */
    public function openCheckIn(int $spaceId): void
    {
        $space = Space::with('type')->find($spaceId);

        if (!$space) {
            $this->dispatch('notify', [
                'type' => 'error',
                'message' => 'Espacio no encontrado'
            ]);
            return;
        }

        if ($space->status !== Space::STATUS_AVAILABLE) {
            $this->dispatch('notify', [
                'type' => 'error',
                'message' => 'El espacio no está disponible'
            ]);
            return;
        }

        $this->selected_space_id = $spaceId;
        $this->currentView = 'check-in';
    }

    /**
     * Registrar entrada (Check-In)
     * sin impresión
     */
    public function checkIn1(): void
    {
        $this->validate([
            'selected_space_id' => 'required|integer|exists:parking_spaces,id',
        ]);

        try {
            DB::beginTransaction();

            $space = Space::with('type')->findOrFail($this->selected_space_id);

            // Validar que el espacio esté disponible
            if ($space->status !== Space::STATUS_AVAILABLE) {
                throw new \Exception('El espacio no está disponible');
            }

            // Obtener la tarifa del tipo de vehículo
            $rate = Rate::where('type_id', $space->type_id)
                ->where('active', true)
                ->first();

            if (!$rate) {
                throw new \Exception('No hay tarifa configurada para este tipo de vehículo');
            }

            // Crear la renta
            $rental = Rental::create([
                'space_id' => $space->id,
                'rate_id' => $rate->id,
                'user_id' => auth()->id(),
                'check_in' => now(),
                'status' => Rental::STATUS_OPEN,
                'rental_type' => Rental::TYPE_HOURLY,
                'description' => $this->vehicle_description,
            ]);

            // Actualizar espacio
            $space->update(['status' => Space::STATUS_OCCUPIED]);

            DB::commit();

            $this->dispatch('notify', [
                'type' => 'success',
                'message' => 'Entrada registrada exitosamente'
            ]);

            // Opcional: Imprimir ticket
            $this->dispatch('print-ticket', ['rentalId' => $rental->id]);

            $this->backToDashboard();
        } catch (\Exception $e) {
            DB::rollBack();

            $this->dispatch('notify', [
                'type' => 'error',
                'message' => 'Error: ' . $e->getMessage()
            ]);
        }
    }

    /**
     * Registrar entrada de vehículo con impresión automática
     * 
     * @param bool $withPrint Forzar impresión (sobrescribe auto_print)
     */
    public function checkIn($withPrint = true): void
    {
        // Validación
        $this->validate([
            'selected_space_id' => 'required|integer|exists:parking_spaces,id',
        ]);

        try {
            DB::beginTransaction();

            // Obtener espacio con relaciones
            $space = Space::with('type')->findOrFail($this->selected_space_id);

            // Validar disponibilidad
            if ($space->status !== Space::STATUS_AVAILABLE) {
                throw new \Exception('El espacio no está disponible');
            }

            // Obtener tarifa activa
            $rate = Rate::where('type_id', $space->type_id)
                ->where('active', true)
                ->first();

            if (!$rate) {
                throw new \Exception('No hay tarifa configurada para este tipo de vehículo');
            }

            // Crear renta
            $rental = Rental::create([
                'space_id' => $space->id,
                'rate_id' => $rate->id,
                'user_id' => auth()->id(),
                'check_in' => now(),
                'status' => Rental::STATUS_OPEN,
                'rental_type' => Rental::TYPE_HOURLY,
                'description' => $this->vehicle_description,
                'price_per_minute' => $rate->price_per_minute ?? ($rate->price / $rate->time),
            ]);

            // Actualizar estado del espacio
            $space->update(['status' => Space::STATUS_OCCUPIED]);

            DB::commit();

            // Log de éxito
            Log::info('Check-in registrado exitosamente', [
                'rental_id' => $rental->id,
                'space_id' => $space->id,
                'user_id' => auth()->id()
            ]);

            // ========================================
            // IMPRESIÓN AUTOMÁTICA
            // ========================================
            $shouldPrint = $withPrint ?? $this->auto_print;

            if ($shouldPrint) {
                $this->printEntryReceipt($rental->id);
            }

            // Notificación de éxito
            $message = $shouldPrint
                ? '✅ Entrada registrada e impresa correctamente'
                : '✅ Entrada registrada exitosamente';

            $this->dispatch('notify', [
                'type' => 'success',
                'message' => $message
            ]);

            // Limpiar formulario y regresar
            $this->reset(['selected_space_id', 'vehicle_description']);
            $this->backToDashboard();

            // Emitir evento para actualizar componentes
            $this->dispatch('rental-created', ['rental_id' => $rental->id]);
        } catch (\Exception $e) {
            DB::rollBack();

            Log::error('Error en check-in', [
                'space_id' => $this->selected_space_id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            $this->dispatch('notify', [
                'type' => 'error',
                'message' => '❌ Error: ' . $e->getMessage()
            ]);
        }
    }




    /*
    |--------------------------------------------------------------------------
    | TICKET RÁPIDO
    |--------------------------------------------------------------------------
    */

    /**
     * Generar ticket de entrada rápida
     * Asigna automáticamente el siguiente espacio disponible
     */
    public function quickTicket(): void
    {
        try {
            DB::beginTransaction();

            // Buscar primer espacio disponible (prioridad por jerarquía)
            $space = Space::with('type')
                ->available()
                ->orderBy('code')
                ->first();

            if (!$space) {
                throw new \Exception('No hay espacios disponibles');
            }

            // Obtener tarifa
            $rate = Rate::where('type_id', $space->type_id)
                ->where('active', true)
                ->first();

            if (!$rate) {
                throw new \Exception('No hay tarifa configurada');
            }

            // Crear renta
            $rental = Rental::create([
                'space_id' => $space->id,
                'rate_id' => $rate->id,
                'user_id' => auth()->id(),
                'check_in' => now(),
                'status' => Rental::STATUS_OPEN,
                'rental_type' => Rental::TYPE_HOURLY,
            ]);

            // Actualizar espacio
            $space->update(['status' => Space::STATUS_OCCUPIED]);

            DB::commit();

            $this->dispatch('notify', [
                'type' => 'success',
                'message' => "Ticket generado - Espacio: {$space->code}"
            ]);

            // Imprimir ticket
            $this->dispatch('print-ticket', ['rentalId' => $rental->id]);
        } catch (\Exception $e) {
            DB::rollBack();

            $this->dispatch('notify', [
                'type' => 'error',
                'message' => 'Error: ' . $e->getMessage()
            ]);
        }
    }

    /*
    |--------------------------------------------------------------------------
    | CHECK-OUT (SALIDA)
    |--------------------------------------------------------------------------
    */

    /**
     * Buscar renta por código de barras
     */
    public function searchByBarcode(): void
    {
        $this->validate([
            'barcode' => 'required|string',
        ]);

        $rental = Rental::with(['space', 'rate', 'vehicle', 'customer'])
            ->where('barcode', $this->barcode)
            ->first();

        if (!$rental) {
            $this->dispatch('notify', [
                'type' => 'error',
                'message' => 'Ticket no encontrado'
            ]);
            $this->barcode = '';
            return;
        }

        if ($rental->status === Rental::STATUS_CLOSED) {
            $this->dispatch('notify', [
                'type' => 'warning',
                'message' => 'Este ticket ya fue cerrado'
            ]);
            $this->barcode = '';
            return;
        }

        $this->loadRentalForCheckOut($rental);
    }

    /**
     * Cargar renta para proceso de salida
     */
    public function loadRentalForCheckOut(Rental $rental): void
    {
        $this->current_rental = $rental;
        $this->calculated_amount = $rental->calculateTotalAmount();
        $this->paid_amount = $this->calculated_amount;
        $this->change_amount = 0;
        $this->currentView = 'check-out';
        $this->barcode = '';
    }

    /**
     * Cargar renta desde espacio ocupado
     */
    public function checkOutFromSpace(int $spaceId): void
    {
        $rental = Rental::with(['space', 'rate'])
            ->where('space_id', $spaceId)
            ->where('status', Rental::STATUS_OPEN)
            ->first();

        if (!$rental) {
            $this->dispatch('notify', [
                'type' => 'error',
                'message' => 'No se encontró renta activa en este espacio'
            ]);
            return;
        }

        $this->loadRentalForCheckOut($rental);
    }

    /**
     * Calcular cambio cuando se ingresa monto pagado
     */
    public function updatedPaidAmount(): void
    {
        if ($this->paid_amount >= $this->calculated_amount) {
            $this->change_amount = $this->paid_amount - $this->calculated_amount;
        } else {
            $this->change_amount = 0;
        }
    }

    /**
     * Confirmar salida (Check-Out)
     */
    public function confirmCheckOut(): void
    {
        if (!$this->current_rental) {
            return;
        }

        if ($this->paid_amount < $this->calculated_amount) {
            $this->dispatch('notify', [
                'type' => 'error',
                'message' => 'El monto pagado es insuficiente'
            ]);
            return;
        }

        try {
            DB::beginTransaction();

            $this->current_rental->checkOut($this->calculated_amount, $this->paid_amount);

            DB::commit();

            $this->dispatch('notify', [
                'type' => 'success',
                'message' => 'Salida registrada exitosamente'
            ]);

            // Opcional: Imprimir ticket de salida
            $this->dispatch('print-checkout-ticket', ['rentalId' => $this->current_rental->id]);

            $this->backToDashboard();
        } catch (\Exception $e) {
            DB::rollBack();

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
     * Limpiar inputs
     */
    protected function resetInputs(): void
    {
        $this->barcode = '';
        $this->selected_space_id = null;
        $this->vehicle_description = '';
        $this->resetCheckOut();
        $this->resetValidation();
    }

    /**
     * Limpiar datos de check-out
     */
    protected function resetCheckOut(): void
    {
        $this->current_rental = null;
        $this->calculated_amount = 0;
        $this->paid_amount = 0;
        $this->change_amount = 0;
    }

    /**
     * Limpiar código de barras
     */
    public function clearBarcode(): void
    {
        $this->barcode = '';
    }

    /**
     * Cambiar filtro de tipo
     */
    public function updatingFilterType(): void
    {
        $this->resetPage();
    }

    /**
     * Cambiar filtro de estado
     */
    public function updatingFilterStatus(): void
    {
        $this->resetPage();
    }


    public function refreshRentals(): void
    {
        // Este método se llama automáticamente cada segundo desde el frontend
        // Livewire recalcula automáticamente las computed properties
    }

    /*
    |--------------------------------------------------------------------------
    | LISTENERS
    |--------------------------------------------------------------------------
    */

    /**
     * Manejar evento de escaneo de código de barras
     */
    #[On('barcode-scanned')]
    public function handleBarcodeScan(string $barcode): void
    {
        $this->barcode = $barcode;

        if ($this->currentView === 'check-out' || $this->currentView === 'dashboard') {
            $this->searchByBarcode();
        }
    }


        
// ============================================================================================================================
// MÉTODOS DE IMPRESIÓN / A diferencia de sysParking, acá estamos incluyendo js y preview (te he agregado un plus 😉)
// Me fascina incluir emojis en el código, disculpad 🤭
// ============================================================================================================================



    /**
     * Imprimir recibo de entrada
     */
    private function printEntryReceipt(int $rentalId): bool
    {
        try {
            // Verificar que exista impresora configurada
            if (!$this->printerConfigured) {
                Log::warning('Impresión solicitada pero no hay impresora configurada', [
                    'rental_id' => $rentalId
                ]);
                return false;
            }

            // Obtener renta con relaciones
            $rental = Rental::with(['space', 'rate', 'rate.type'])
                ->findOrFail($rentalId);

            // Imprimir usando el trait
            //$result = $rental->printEntryReceipt(false);
            $this->printerService->printEntryReceipt($rental, false);


            Log::info('Recibo de entrada impreso', [
                'rental_id' => $rentalId,
                'space_code' => $rental->space->code ?? 'N/A'
            ]);


            return true;
        } catch (\Exception $e) {
            Log::error('Error al imprimir recibo de entrada', [
                'rental_id' => $rentalId,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            // No lanzar excepción, solo retornar false
            // La impresión no debe bloquear el proceso de check-in
            return false;
        }
    }

    /**
     * Reimprimir último recibo / El negocio de los parking es cobrarte extravíos/reimpresiones,
     * En mi caso no lo utilizo pero te lo dejo listo 😉
     */
    public function reprintLastReceipt(int $rentalId): void
    {
        try {
            $rental = Rental::findOrFail($rentalId);

            // Servcio maneja la lógica
            $result = $this->printerService->reprint($rental);

            $this->dispatch('notify', [
                'type' => $result ? 'success' : 'error',
                'message' => $result
                    ? '✅ Recibo reimpreso (COPIA)'
                    : '❌ Error al reimprimir'
            ]);
        } catch (\Exception $e) {
            $this->dispatch('notify', [
                'type' => 'error',
                'message' => '❌ Error: ' . $e->getMessage()
            ]);
        }
    }

    /**
     * Verificar estado de la impresora
     */
    public function checkPrinterStatus(): void
    {
        try {
            $config = $this->printerService->getConfiguration();

            $this->printerConfigured = $config !== null && $this->printerService->checkPrinterStatus();
        } catch (\Exception $e) {
            Log::warning('Error al verificar estado de impresora', [
                'error' => $e->getMessage()
            ]);
            $this->printerConfigured = false;
        }
    }

   

// ========================================
// MÉTODO ALTERNATIVO: checkIn SIN auto-print
// ========================================

    /**
     * Registrar entrada sin impresión
     * (Útil para casos específicos)
     */
    public function checkInSilent(): void
    {
        $this->checkIn(false);
    }

    /**
     * Registrar entrada CON impresión forzada
     * (Útil cuando auto_print está desactivado pero se quiere imprimir)
     */
    public function checkInAndPrint(): void
    {
        $this->checkIn(true);
    }
}
