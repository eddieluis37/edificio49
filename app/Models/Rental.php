<?php


namespace App\Models;

use Carbon\Carbon;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * Rental Model
 * 
 * Gestiona las rentas de espacios de estacionamiento.
 * Incluye cálculo automático de tiempo, cobro y gestión de estados.
 */
class Rental extends Model
{
    use HasFactory, SoftDeletes;


    /*
    |--------------------------------------------------------------------------
    | TABLE CONFIGURATION
    |--------------------------------------------------------------------------
    */

    protected $table = 'rentals';
    protected $primaryKey = 'id';
    public $incrementing = true;
    protected $keyType = 'int';
    public $timestamps = true;

    /*
    |--------------------------------------------------------------------------
    | CONSTANTS
    |--------------------------------------------------------------------------
    */

    /**
     * Estados de la renta
     */
    public const STATUS_OPEN = 'open';        // Vehículo dentro del estacionamiento
    public const STATUS_CLOSED = 'closed';    // Vehículo salió y cobrado
    public const STATUS_CANCELLED = 'cancelled'; // Renta cancelada

    /**
     * Tipos de renta
     */
    public const TYPE_HOURLY = 'hourly';      // Por horas/fracciones
    public const TYPE_MONTHLY = 'monthly';    // Mensualidad (para después)

    /*
    |--------------------------------------------------------------------------
    | MASS ASSIGNMENT
    |--------------------------------------------------------------------------
    */

    protected $fillable = [
        'barcode',
        'space_id',
        'rate_id',
        'vehicle_id',
        'customer_id',
        'user_id',
        'check_in',
        'check_out',
        'total_time',
        'total_amount',
        'paid_amount',
        'change_amount',
        'status',
        'rental_type',
        'description',
        'notes',
        // Campos legacy para migración gradual
        'plate',
        'model',
        'brand',
        'color',
    ];

    protected $guarded = ['id'];

    /*
    |--------------------------------------------------------------------------
    | CASTS & ATTRIBUTES
    |--------------------------------------------------------------------------
    */

    protected $casts = [
        'space_id' => 'integer',
        'rate_id' => 'integer',
        'vehicle_id' => 'integer',
        'customer_id' => 'integer',
        'user_id' => 'integer',
        'check_in' => 'datetime',
        'check_out' => 'datetime',
        'total_amount' => 'decimal:2',
        'paid_amount' => 'decimal:2',
        'change_amount' => 'decimal:2',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime',
    ];

    protected $appends = [
        'formatted_barcode',
        'formatted_time',
        'formatted_amount',
        'is_open',
        'is_closed',
        'duration_minutes',
    ];

    /*
    |--------------------------------------------------------------------------
    | RELATIONSHIPS
    |--------------------------------------------------------------------------
    */

    /**
     * Espacio de estacionamiento ocupado
     */
    public function space(): BelongsTo
    {
        return $this->belongsTo(Space::class, 'space_id');
    }

    /**
     * Tarifa aplicada
     */
    public function rate(): BelongsTo
    {
        return $this->belongsTo(Rate::class, 'rate_id');
    }

    /**
     * Vehículo rentado
     */
    public function vehicle(): BelongsTo
    {
        return $this->belongsTo(Vehicle::class, 'vehicle_id');
    }

    /**
     * Cliente dueño del vehículo
     */
    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class, 'customer_id');
    }

    /**
     * Usuario que registró la entrada
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /*
    |--------------------------------------------------------------------------
    | ACCESSORS & MUTATORS
    |--------------------------------------------------------------------------
    */

    /**
     * Código de barras formateado (7 dígitos)
     */
    protected function formattedBarcode(): Attribute
    {
        return Attribute::make(
            get: fn(): string => sprintf('%07d', $this->id),
        );
    }

    /**
     * Tiempo total formateado (HH:MM:SS)
     */
    protected function formattedTime(): Attribute
    {
        return Attribute::make(
            get: function (): string {
                if (!$this->check_in) {
                    return '00:00:00';
                }

                $end = $this->check_out ?? now();
                $start = Carbon::parse($this->check_in);

                $hours = $start->diffInHours($end);
                $minutes = $start->diff($end)->format('%I');
                $seconds = $start->diff($end)->format('%S');

                return sprintf('%02d:%02d:%02d', $hours, $minutes, $seconds);
            }
        );
    }

    /**
     * Monto total formateado
     */
    protected function formattedAmount(): Attribute
    {
        return Attribute::make(
            get: fn(): string => '$' . number_format((float)$this->total_amount, 2, '.', ','),
        );
    }

    /**
     * Duración en minutos
     */
    protected function durationMinutes(): Attribute
    {
        return Attribute::make(
            get: function (): int {
                if (!$this->check_in) {
                    return 0;
                }

                $end = $this->check_out ?? now();
                return Carbon::parse($this->check_in)->diffInMinutes($end);
            }
        );
    }

    /**
     * Verificar si está abierta
     */
    protected function isOpen(): Attribute
    {
        return Attribute::make(
            get: fn(): bool => $this->status === self::STATUS_OPEN,
        );
    }

    /**
     * Verificar si está cerrada
     */
    protected function isClosed(): Attribute
    {
        return Attribute::make(
            get: fn(): bool => $this->status === self::STATUS_CLOSED,
        );
    }

    /*
    |--------------------------------------------------------------------------
    | QUERY SCOPES
    |--------------------------------------------------------------------------
    */

    /**
     * Scope: Rentas abiertas
     */
    public function scopeOpen(Builder $query): Builder
    {
        return $query->where('status', self::STATUS_OPEN);
    }

    /**
     * Scope: Rentas cerradas
     */
    public function scopeClosed(Builder $query): Builder
    {
        return $query->where('status', self::STATUS_CLOSED);
    }

    /**
     * Scope: Rentas canceladas
     */
    public function scopeCancelled(Builder $query): Builder
    {
        return $query->where('status', self::STATUS_CANCELLED);
    }

    /**
     * Scope: Por tipo de renta
     */
    public function scopeByType(Builder $query, string $type): Builder
    {
        return $query->where('rental_type', $type);
    }

    /**
     * Scope: Rentas por horas
     */
    public function scopeHourly(Builder $query): Builder
    {
        return $query->byType(self::TYPE_HOURLY);
    }

    /**
     * Scope: Rentas mensuales
     */
    public function scopeMonthly(Builder $query): Builder
    {
        return $query->byType(self::TYPE_MONTHLY);
    }

    /**
     * Scope: Por código de barras
     */
    public function scopeByBarcode(Builder $query, string $barcode): Builder
    {
        return $query->where('barcode', $barcode);
    }

    /**
     * Scope: Por espacio
     */
    public function scopeBySpace(Builder $query, int $spaceId): Builder
    {
        return $query->where('space_id', $spaceId);
    }

    /**
     * Scope: Rentas de hoy
     */
    public function scopeToday(Builder $query): Builder
    {
        return $query->whereDate('check_in', today());
    }

    /**
     * Scope: Rentas del mes actual
     */
    public function scopeThisMonth(Builder $query): Builder
    {
        return $query->whereMonth('check_in', now()->month)
            ->whereYear('check_in', now()->year);
    }

    /**
     * Scope: Buscar por placa
     */
    public function scopeByPlate(Builder $query, string $plate): Builder
    {
        return $query->where('plate', 'like', '%' . $plate . '%');
    }

    /*
    |--------------------------------------------------------------------------
    | HELPER METHODS
    |--------------------------------------------------------------------------
    */

    /**
     * Calcular tiempo transcurrido desde check-in
     * 
     * @return array ['hours' => int, 'minutes' => int, 'seconds' => int, 'total_minutes' => int]
     */
    public function calculateElapsedTime(): array
    {
        if (!$this->check_in) {
            return ['hours' => 0, 'minutes' => 0, 'seconds' => 0, 'total_minutes' => 0];
        }

        $start = Carbon::parse($this->check_in);
        $end = $this->check_out ?? now();

        return [
            'hours' => $start->diffInHours($end),
            'minutes' => $start->diff($end)->format('%I'),
            'seconds' => $start->diff($end)->format('%S'),
            'total_minutes' => $start->diffInMinutes($end),
        ];
    }

    /**
     * Calcular monto total basado en tarifa y tiempo
     * 
     * Estrategia de cobro por fracciones:
     * - Primera hora: Tarifa completa
     * - Después de 1 hora:
     *   - 0-5 min: Tolerancia (no se cobra)
     *   - 6-30 min: 50% de la tarifa
     *   - 31-60 min: 100% de la tarifa
     * 
     * @param Carbon|null $endTime
     * @return float
     */
    public function calculateTotalAmount(?Carbon $endTime = null): float
    {
        if (!$this->rate || !$this->check_in) {
            return 0;
        }

        $rate = $this->rate;
        $start = Carbon::parse($this->check_in);
        $end = $endTime ?? now();

        $totalMinutes = $start->diffInMinutes($end);
        $completeHours = (int) floor($totalMinutes / 60);
        $remainingMinutes = $totalMinutes % 60;

        // Primera hora completa
        if ($totalMinutes <= 65) { // 60 min + 5 min tolerancia
            return (float) $rate->price;
        }

        // Calcular fracción adicional
        $fraction = 0;
        if ($remainingMinutes >= 6 && $remainingMinutes <= 30) {
            $fraction = (float) $rate->price * 0.5; // 50%
        } elseif ($remainingMinutes >= 31) {
            $fraction = (float) $rate->price; // 100%
        }

        return (float) ($completeHours * $rate->price) + $fraction;
    }

    /**
     * Registrar salida (check-out)
     * 
     * @param float|null $customAmount Monto personalizado (opcional)
     * @param float|null $paidAmount Monto pagado
     * @return bool
     */
    public function checkOut(?float $customAmount = null, ?float $paidAmount = null): bool
    {
        if ($this->status !== self::STATUS_OPEN) {
            return false;
        }

        $this->check_out = now();
        $this->total_time = $this->formatted_time;

        // Calcular monto total
        $this->total_amount = $customAmount ?? $this->calculateTotalAmount();

        // Registrar pago
        if ($paidAmount !== null) {
            $this->paid_amount = $paidAmount;
            $this->change_amount = max(0, $paidAmount - $this->total_amount);
        }

        $this->status = self::STATUS_CLOSED;

        // Liberar espacio
        if ($this->space) {
            $this->space->update(['status' => Space::STATUS_AVAILABLE]);
        }

        return $this->save();
    }

    /**
     * Cancelar renta
     * 
     * @param string $reason
     * @return bool
     */
    public function cancel(string $reason = ''): bool
    {
        $this->status = self::STATUS_CANCELLED;
        $this->notes = $reason;

        // Liberar espacio
        if ($this->space) {
            $this->space->update(['status' => Space::STATUS_AVAILABLE]);
        }

        return $this->save();
    }

    /**
     * Obtener información completa del vehículo
     */
    public function getVehicleInfo(): string
    {
        if ($this->vehicle) {
            return "{$this->vehicle->plate} - {$this->vehicle->brand} {$this->vehicle->model} ({$this->vehicle->color})";
        }

        // Legacy: usar campos directos
        if ($this->plate) {
            return "{$this->plate} - {$this->brand} {$this->model} ({$this->color})";
        }

        return 'N/A';
    }

    /*
    |--------------------------------------------------------------------------
    | STATIC HELPERS
    |--------------------------------------------------------------------------
    */

    /**
     * Obtener estados disponibles
     */
    public static function getStatuses(): array
    {
        return [
            self::STATUS_OPEN => 'Abierta',
            self::STATUS_CLOSED => 'Cerrada',
            self::STATUS_CANCELLED => 'Cancelada',
        ];
    }

    /**
     * Obtener tipos de renta
     */
    public static function getTypes(): array
    {
        return [
            self::TYPE_HOURLY => 'Por Hora',
            self::TYPE_MONTHLY => 'Mensual',
        ];
    }

    /**
     * Generar siguiente código de barras
     */
    public static function generateBarcode(): string
    {
        $lastRental = self::orderBy('id', 'desc')->first();
        $nextId = $lastRental ? $lastRental->id + 1 : 1;
        return sprintf('%07d', $nextId);
    }

    /*
    |--------------------------------------------------------------------------
    | MODEL EVENTS
    |--------------------------------------------------------------------------
    */

    protected static function booted(): void
    {
        // Generar barcode al crear
        static::creating(function ($rental) {
            if (!$rental->barcode) {
                $rental->barcode = self::generateBarcode();
            }

            // Establecer valores por defecto
            if (!$rental->status) {
                $rental->status = self::STATUS_OPEN;
            }

            if (!$rental->rental_type) {
                $rental->rental_type = self::TYPE_HOURLY;
            }

            if (!$rental->check_in) {
                $rental->check_in = now();
            }

            // Ocupar espacio
            if ($rental->space) {
                $rental->space->update(['status' => Space::STATUS_OCCUPIED]);
            }
        });

        // Actualizar espacio al eliminar (soft delete)
        static::deleted(function ($rental) {
            if ($rental->space && $rental->status === self::STATUS_OPEN) {
                $rental->space->update(['status' => Space::STATUS_AVAILABLE]);
            }
        });
    }

    /*
    |--------------------------------------------------------------------------
    | VALIDATION RULES
    |--------------------------------------------------------------------------
    */

    public static function rules(?int $id = null): array
    {
        return [
            'space_id' => ['required', 'integer', 'exists:spaces,id'],
            'rate_id' => ['required', 'integer', 'exists:rates,id'],
            'vehicle_id' => ['nullable', 'integer', 'exists:vehicles,id'],
            'customer_id' => ['nullable', 'integer', 'exists:customers,id'],
            'check_in' => ['nullable', 'date'],
            'check_out' => ['nullable', 'date', 'after:check_in'],
            'status' => ['required', 'string', 'in:' . implode(',', array_keys(self::getStatuses()))],
            'rental_type' => ['required', 'string', 'in:' . implode(',', array_keys(self::getTypes()))],
        ];
    }
}
