<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Casts\Attribute;

/**
 * ParkingSpace Model
 * 
 * Representa los espacios físicos del estacionamiento donde se colocan los vehículos.
 * Cada espacio tiene un tipo específico (para auto, camioneta, moto, etc.) y un estado
 * que indica si está disponible, ocupado o en mantenimiento.
 * 
 */

class Space extends Model
{
    use HasFactory, SoftDeletes;

    /*
    |--------------------------------------------------------------------------
    | CONFIGURACIÓN DE LA TABLA
    |--------------------------------------------------------------------------
    */

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'parking_spaces';

    /**
     * The primary key for the model.
     *
     * @var string
     */
    protected $primaryKey = 'id';

    /**
     * Indicates if the model's ID is auto-incrementing.
     *
     * @var bool
     */
    public $incrementing = true;

    /**
     * The data type of the auto-incrementing ID.
     *
     * @var string
     */
    protected $keyType = 'int';

    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = true;

    /*
    |--------------------------------------------------------------------------
    | CONSTANTES
    |--------------------------------------------------------------------------
    */

    /**
     * Estados disponibles para los espacios de estacionamiento
     */
    public const STATUS_AVAILABLE = 'available';
    public const STATUS_OCCUPIED = 'occupied';
    public const STATUS_MAINTENANCE = 'maintenance';
    public const STATUS_RESERVED = 'reserved';

    /**
     * Longitud máxima del código del espacio
     */
    public const CODE_MAX_LENGTH = 20;

    /*
    |--------------------------------------------------------------------------
    | MASS ASSIGNMENT
    |--------------------------------------------------------------------------
    */

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'code',
        'description',
        'type_id',
        'status',
        'notes',
    ];

    /**
     * The attributes that aren't mass assignable.
     *
     * @var array<int, string>
     */
    protected $guarded = [
        'id',
    ];

    /*
    |--------------------------------------------------------------------------
    | CASTS Y ATRIBUTOS
    |--------------------------------------------------------------------------
    */

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'type_id' => 'integer',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [];

    /**
     * The accessors to append to the model's array form.
     *
     * @var array<int, string>
     */
    protected $appends = [
        'status_label',
        'status_color',
    ];

    /*
    |--------------------------------------------------------------------------
    | RELACIONES
    |--------------------------------------------------------------------------
    */

    /**
     * Get the type that owns the parking space.
     * 
     * Relación: Un espacio pertenece a un tipo de vehículo.
     * Ejemplo: Espacio "A-101" es para tipo "Auto"
     *
     * @return BelongsTo<Type, ParkingSpace>
     */
    public function type(): BelongsTo
    {
        return $this->belongsTo(Type::class, 'type_id');
    }

    /**
     * Get the rentals for the parking space.
     * 
     * Relación: Un espacio puede tener múltiples rentas (historial).
     * Útil para ver el historial de uso del espacio.
     *
     * @return HasMany<Rental>
     */
    public function rentals(): HasMany
    {
        return $this->hasMany(self::class)->whereRaw('1 = 0');
    }

    /**
     * Get the current active rental.
     * 
     * Obtiene la renta activa actual del espacio (si está ocupado).
     *
     * @return HasMany<Rental>
     */
    public function activeRental(): HasMany
    {
        return $this->rentals()->whereNull('checkout_at');
    }

    /*
    |--------------------------------------------------------------------------
    | ACCESSORS & MUTATORS
    |--------------------------------------------------------------------------
    */

    /**
     * Get the status label attribute.
     * 
     * Convierte el estado a un label legible en español.
     */
    protected function statusLabel(): Attribute
    {
        return Attribute::make(
            get: fn(): string => match ($this->status) {
                self::STATUS_AVAILABLE => 'Disponible',
                self::STATUS_OCCUPIED => 'Ocupado',
                self::STATUS_MAINTENANCE => 'Mantenimiento',
                self::STATUS_RESERVED => 'Reservado',
                default => 'Desconocido',
            }
        );
    }

    /**
     * Get the status color attribute.
     * 
     * Retorna el color asociado al estado (útil para UI).
     * Verde = Disponible, Rojo = Ocupado, Amarillo = Mantenimiento
     */
    protected function statusColor(): Attribute
    {
        return Attribute::make(
            get: fn(): string => match ($this->status) {
                self::STATUS_AVAILABLE => 'green',
                self::STATUS_OCCUPIED => 'red',
                self::STATUS_MAINTENANCE => 'yellow',
                self::STATUS_RESERVED => 'blue',
                default => 'gray',
            }
        );
    }

    /**
     * Determine if the parking space is available.
     */
    protected function isAvailable(): Attribute
    {
        return Attribute::make(
            get: fn(): bool => $this->status === self::STATUS_AVAILABLE
        );
    }

    /**
     * Determine if the parking space is occupied.
     */
    protected function isOccupied(): Attribute
    {
        return Attribute::make(
            get: fn(): bool => $this->status === self::STATUS_OCCUPIED
        );
    }

    /**
     * Ensure code is always uppercase.
     * 
     * Normaliza el código del espacio a mayúsculas.
     * Ejemplo: "a-101" se guarda como "A-101"
     */
    protected function code(): Attribute
    {
        return Attribute::make(
            set: fn(string $value): string => strtoupper(trim($value))
        );
    }

    /*
    |--------------------------------------------------------------------------
    | QUERY SCOPES
    |--------------------------------------------------------------------------
    */

    /**
     * Scope: Espacios disponibles.
     * 
     * Útil para mostrar solo espacios que pueden ser rentados.
     *
     * @param Builder<ParkingSpace> $query
     * @return Builder<ParkingSpace>
     */
    public function scopeAvailable(Builder $query): Builder
    {
        return $query->where('status', self::STATUS_AVAILABLE);
    }

    /**
     * Scope: Espacios ocupados.
     *
     * @param Builder<ParkingSpace> $query
     * @return Builder<ParkingSpace>
     */
    public function scopeOccupied(Builder $query): Builder
    {
        return $query->where('status', self::STATUS_OCCUPIED);
    }

    /**
     * Scope: Espacios en mantenimiento.
     *
     * @param Builder<ParkingSpace> $query
     * @return Builder<ParkingSpace>
     */
    public function scopeMaintenance(Builder $query): Builder
    {
        return $query->where('status', self::STATUS_MAINTENANCE);
    }

    /**
     * Scope: Espacios reservados.
     *
     * @param Builder<ParkingSpace> $query
     * @return Builder<ParkingSpace>
     */
    public function scopeReserved(Builder $query): Builder
    {
        return $query->where('status', self::STATUS_RESERVED);
    }

    /**
     * Scope: Filtrar por tipo de vehículo.
     * 
     * Permite buscar espacios para un tipo específico.
     * Ejemplo: Buscar todos los espacios para "Camioneta"
     *
     * @param Builder<ParkingSpace> $query
     * @param int $typeId
     * @return Builder<ParkingSpace>
     */
    public function scopeByType(Builder $query, int $typeId): Builder
    {
        return $query->where('type_id', $typeId);
    }

    /**
     * Scope: Filtrar por estado.
     *
     * @param Builder<ParkingSpace> $query
     * @param string $status
     * @return Builder<ParkingSpace>
     */
    public function scopeByStatus(Builder $query, string $status): Builder
    {
        return $query->where('status', $status);
    }

    /**
     * Scope: Buscar por código o descripción.
     * 
     * Búsqueda flexible que permite encontrar espacios por:
     * - Código (ej: "A-101")
     * - Descripción (ej: "Planta baja zona norte")
     *
     * @param Builder<ParkingSpace> $query
     * @param string $search
     * @return Builder<ParkingSpace>
     */
    public function scopeSearch(Builder $query, string $search): Builder
    {
        if (empty($search)) {
            return $query;
        }

        return $query->where(function ($q) use ($search) {
            $q->where('code', 'like', '%' . $search . '%')
                ->orWhere('description', 'like', '%' . $search . '%');
        });
    }

    /**
     * Scope: Ordenar por código.
     * 
     * Ordena alfabéticamente por código del espacio.
     *
     * @param Builder<ParkingSpace> $query
     * @return Builder<ParkingSpace>
     */
    public function scopeOrdered(Builder $query): Builder
    {
        return $query->orderBy('code', 'asc');
    }

    /*
    |--------------------------------------------------------------------------
    | MÉTODOS HELPER
    |--------------------------------------------------------------------------
    */

    /**
     * Mark the parking space as available.
     * 
     * Libera el espacio para que pueda ser rentado nuevamente.
     *
     * @return bool
     */
    public function markAsAvailable(): bool
    {
        return $this->update(['status' => self::STATUS_AVAILABLE]);
    }

    /**
     * Mark the parking space as occupied.
     * 
     * Marca el espacio como ocupado cuando se realiza una renta.
     *
     * @return bool
     */
    public function markAsOccupied(): bool
    {
        return $this->update(['status' => self::STATUS_OCCUPIED]);
    }

    /**
     * Mark the parking space as maintenance.
     * 
     * Pone el espacio en mantenimiento (no disponible para rentar).
     *
     * @return bool
     */
    public function markAsMaintenance(): bool
    {
        return $this->update(['status' => self::STATUS_MAINTENANCE]);
    }

    /**
     * Mark the parking space as reserved.
     * 
     * Reserva el espacio (útil para apartados).
     *
     * @return bool
     */
    public function markAsReserved(): bool
    {
        return $this->update(['status' => self::STATUS_RESERVED]);
    }

    /**
     * Check if the parking space can be rented.
     * 
     * Verifica si el espacio está disponible para ser rentado.
     * Un espacio solo puede rentarse si está en estado "available".
     *
     * @return bool
     */
    public function canBeRented(): bool
    {
        return $this->status === self::STATUS_AVAILABLE;
    }

    /**
     * Get the array of available statuses.
     *
     * @return array<string, string>
     */
    public static function getStatuses(): array
    {
        return [
            self::STATUS_AVAILABLE => 'Disponible',
            self::STATUS_OCCUPIED => 'Ocupado',
            self::STATUS_MAINTENANCE => 'Mantenimiento',
            self::STATUS_RESERVED => 'Reservado',
        ];
    }

    /**
     * Get the status badge HTML.
     * 
     * Genera el HTML para mostrar el badge del estado.
     * Útil para vistas Blade.
     *
     * @return string
     */
    public function getStatusBadge(): string
    {
        $colors = [
            self::STATUS_AVAILABLE => 'bg-green-100 text-green-800',
            self::STATUS_OCCUPIED => 'bg-red-100 text-red-800',
            self::STATUS_MAINTENANCE => 'bg-yellow-100 text-yellow-800',
            self::STATUS_RESERVED => 'bg-blue-100 text-blue-800',
        ];

        $color = $colors[$this->status] ?? 'bg-gray-100 text-gray-800';

        return '<span class="px-2 py-1 text-xs font-semibold rounded-full ' . $color . '">'
            . $this->status_label .
            '</span>';
    }

    /*
    |--------------------------------------------------------------------------
    | VALIDACIÓN
    |--------------------------------------------------------------------------
    */

    /**
     * Validation rules for the model.
     *
     * @param int|null $id
     * @return array<string, mixed>
     */
    public static function rules(?int $id = null): array
    {
        return [
            'code' => ['required', 'string', 'max:' . self::CODE_MAX_LENGTH, 'unique:parking_spaces,code,' . $id],
            'description' => ['required', 'string', 'min:3', 'max:200'],
            'type_id' => ['required', 'integer', 'exists:types,id'],
            'status' => ['required', 'string', 'in:' . implode(',', array_keys(self::getStatuses()))],
            'notes' => ['nullable', 'string', 'max:500'],
        ];
    }

    /**
     * Custom validation messages.
     *
     * @return array<string, string>
     */
    public static function messages(): array
    {
        return [
            'code.required' => 'El código del espacio es obligatorio.',
            'code.unique' => 'Ya existe un espacio con este código.',
            'code.max' => 'El código no puede exceder :max caracteres.',
            'description.required' => 'La descripción es obligatoria.',
            'description.min' => 'La descripción debe tener al menos :min caracteres.',
            'type_id.required' => 'El tipo de vehículo es obligatorio.',
            'type_id.exists' => 'El tipo de vehículo seleccionado no existe.',
            'status.required' => 'El estado es obligatorio.',
            'status.in' => 'El estado seleccionado no es válido.',
        ];
    }

    /*
    |--------------------------------------------------------------------------
    | EVENTOS DEL MODELO
    |--------------------------------------------------------------------------
    */

    /**
     * The "booted" method of the model.
     */
    protected static function booted(): void
    {
        // // Evento: Antes de crear
        // static::creating(function (ParkingSpace $space) {
        //     // Por defecto, los espacios se crean como disponibles
        //     if (empty($space->status)) {
        //         $space->status = self::STATUS_AVAILABLE;
        //     }
        // });

        // // Evento: Antes de eliminar
        // static::deleting(function (ParkingSpace $space) {
        //     // Prevenir eliminación si tiene rentas activas
        //     if ($space->activeRental()->exists()) {
        //         throw new \Exception('No se puede eliminar un espacio con rentas activas');
        //     }
        // });
    }

    /*
    |--------------------------------------------------------------------------
    | SERIALIZACIÓN
    |--------------------------------------------------------------------------
    */

    /**
     * Get the model's attributes as an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(): array
    {
        $array = parent::toArray();

        // Agregar información del tipo para APIs
        if (request()->wantsJson()) {
            $array['type_info'] = $this->type?->only(['id', 'name']);
        }

        return $array;
    }
    /**
     * Renta activa en este espacio
     */
    public function currentRental(): BelongsTo
    {
        return $this->belongsTo(Rental::class, 'current_rental_id');
    }

    /**
     * Todas las rentas de este espacio
     */
}
