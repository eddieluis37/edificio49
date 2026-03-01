<?php


namespace App\Models;

use App\Models\Rate;
use App\Models\Type;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;

/**
 * Vehicle Model
 * 
 * Gestiona los vehículos registrados en el sistema.
 */
class Vehicle extends Model
{
    use HasFactory, SoftDeletes;

    /*
    |--------------------------------------------------------------------------
    | TABLE CONFIGURATION
    |--------------------------------------------------------------------------
    */

    protected $table = 'vehicles';
    protected $primaryKey = 'id';
    public $incrementing = true;
    protected $keyType = 'int';
    public $timestamps = true;

    /*
    |--------------------------------------------------------------------------
    | MASS ASSIGNMENT
    |--------------------------------------------------------------------------
    */

    protected $fillable = [
        'plate',
        'brand',
        'model',
        'year',
        'color',
        'type_id',
        'customer_id',
        'notes',
        'is_active',
    ];

    protected $guarded = ['id'];

    /*
    |--------------------------------------------------------------------------
    | CASTS & ATTRIBUTES
    |--------------------------------------------------------------------------
    */

    protected $casts = [
        'type_id' => 'integer',
        'customer_id' => 'integer',
        'year' => 'integer',
        'is_active' => 'boolean',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime',
    ];

    protected $appends = [
        'full_description',
        'plate_formatted',
    ];

    /*
    |--------------------------------------------------------------------------
    | RELATIONSHIPS
    |--------------------------------------------------------------------------
    */

    /**
     * Tipo de vehículo
     */
    public function type(): BelongsTo
    {
        return $this->belongsTo(Type::class, 'type_id');
    }

    /**
     * Cliente dueño del vehículo
     */
    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class, 'customer_id');
    }

    /**
     * Rentas del vehículo
     */
    public function rentals(): HasMany
    {
        return $this->hasMany(Rental::class, 'vehicle_id');
    }


    /**
     * Tarifas disponibles para este tipo de vehículo
     */
    public function vehicleRates(): HasManyThrough
    {
        return $this->hasManyThrough(
            Rate::class,
            Type::class,
            'id',
            'type_id',
            'type_id',
            'id'
        );
    }
    /**
     * Obtener tarifa activa por defecto
     */
    public function defaultRate(): ?Rate
    {
        return $this->vehicleRates()->where('active', true)->first();
    }

    /**
     * Obtener tarifa por tipo específico
     */
    public function getRateByType(string $rateType): ?Rate
    {
        return $this->vehicleRates()
            ->where('rate_type', $rateType)
            ->where('active', true)
            ->first();
    }

    /**
     * Tarifa por defecto del tipo de vehículo
     */
    public function vehicleRate()
    {
        return $this->hasOneThrough(
            Rate::class,
            Type::class,
            'id',           // Foreign key en Type
            'type_id',      // Foreign key en Rate  
            'type_id',      // Local key en Vehicle
            'id'            // Local key en Type
        )->where('rates.active', true)
            ->where('rates.rate_type', 'hourly')
            ->orderBy('rates.id');
    }
    /*
    |--------------------------------------------------------------------------
    | ACCESSORS & MUTATORS
    |--------------------------------------------------------------------------
    */

    /**
     * Descripción completa del vehículo
     */
    protected function fullDescription(): Attribute
    {
        return Attribute::make(
            get: function (): string {
                $parts = array_filter([
                    $this->brand,
                    $this->model,
                    $this->year,
                    $this->color ? "({$this->color})" : null,
                ]);

                return implode(' ', $parts) ?: 'N/A';
            }
        );
    }

    /**
     * Placa formateada en mayúsculas
     */
    protected function plateFormatted(): Attribute
    {
        return Attribute::make(
            get: fn(): string => strtoupper($this->plate ?? ''),
            set: fn(string $value): string => strtoupper(trim($value)),
        );
    }

    /*
    |--------------------------------------------------------------------------
    | QUERY SCOPES
    |--------------------------------------------------------------------------
    */

    /**
     * Scope: Vehículos activos
     */
    public function scopeActive(Builder $query): Builder
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope: Vehículos inactivos
     */
    public function scopeInactive(Builder $query): Builder
    {
        return $query->where('is_active', false);
    }

    /**
     * Scope: Por tipo de vehículo
     */
    public function scopeByType(Builder $query, int $typeId): Builder
    {
        return $query->where('type_id', $typeId);
    }

    /**
     * Scope: Por cliente
     */
    public function scopeByCustomer(Builder $query, int $customerId): Builder
    {
        return $query->where('customer_id', $customerId);
    }

    /**
     * Scope: Buscar por placa, marca o modelo
     */
    public function scopeSearch(Builder $query, string $search): Builder
    {
        if (empty($search)) {
            return $query;
        }

        return $query->where(function ($q) use ($search) {
            $q->where('plate', 'like', '%' . $search . '%')
                ->orWhere('brand', 'like', '%' . $search . '%')
                ->orWhere('model', 'like', '%' . $search . '%')
                ->orWhere('color', 'like', '%' . $search . '%');
        });
    }

    /**
     * Scope: Con rentas activas
     */
    public function scopeWithActiveRentals(Builder $query): Builder
    {
        return $query->whereHas('rentals', function ($q) {
            $q->where('status', Rental::STATUS_OPEN);
        });
    }

    /**
     * Scope: Ordenar por placa
     */
    public function scopeOrderByPlate(Builder $query, string $direction = 'asc'): Builder
    {
        return $query->orderBy('plate', $direction);
    }

    /*
    |--------------------------------------------------------------------------
    | HELPER METHODS
    |--------------------------------------------------------------------------
    */

    /**
     * Activar vehículo
     */
    public function activate(): bool
    {
        return $this->update(['is_active' => true]);
    }

    /**
     * Desactivar vehículo
     */
    public function deactivate(): bool
    {
        return $this->update(['is_active' => false]);
    }

    /**
     * Tiene rentas abiertas?
     */
    public function hasOpenRentals(): bool
    {
        return $this->rentals()->open()->exists();
    }

    /**
     * Obtener renta abierta actual
     */
    public function getCurrentRental(): ?Rental
    {
        return $this->rentals()->open()->first();
    }

    /**
     * Total de rentas
     */
    public function getTotalRentals(): int
    {
        return $this->rentals()->count();
    }

    /**
     * Obtener emoji del tipo de vehículo
     */
    public function getTypeEmoji(): string
    {
        if (!$this->type) {
            return '🚗';
        }

        $name = strtolower($this->type->name ?? '');

        if (str_contains($name, 'moto')) {
            return '🏍️';
        } elseif (str_contains($name, 'camion') || str_contains($name, 'truck')) {
            return '🚚';
        } elseif (str_contains($name, 'bici')) {
            return '🚲';
        }

        return '🚗';
    }

    /*
    |--------------------------------------------------------------------------
    | VALIDATION RULES
    |--------------------------------------------------------------------------
    */

    public static function rules(?int $id = null): array
    {
        return [
            'plate' => ['required', 'string', 'max:20', $id ? 'unique:vehicles,plate,' . $id : 'unique:vehicles,plate'],
            'brand' => ['required', 'string', 'max:100'],
            'model' => ['required', 'string', 'max:100'],
            'year' => ['nullable', 'integer', 'min:1900', 'max:' . (date('Y') + 1)],
            'color' => ['nullable', 'string', 'max:50'],
            'type_id' => ['required', 'integer', 'exists:types,id'],
            'customer_id' => ['nullable', 'integer', 'exists:customers,id'],
            'is_active' => ['boolean'],
        ];
    }

    public static function messages(): array
    {
        return [
            'plate.required' => 'La placa es obligatoria',
            'plate.unique' => 'Esta placa ya está registrada',
            'brand.required' => 'La marca es obligatoria',
            'model.required' => 'El modelo es obligatorio',
            'type_id.required' => 'El tipo de vehículo es obligatorio',
            'type_id.exists' => 'El tipo de vehículo no existe',
        ];
    }

    /*
    |--------------------------------------------------------------------------
    | MODEL EVENTS
    |--------------------------------------------------------------------------
    */

    protected static function booted(): void
    {
        static::creating(function (Vehicle $vehicle) {
            // Convertir placa a mayúsculas
            $vehicle->plate = strtoupper(trim($vehicle->plate));

            if (is_null($vehicle->is_active)) {
                $vehicle->is_active = true;
            }
        });

        static::updating(function (Vehicle $vehicle) {
            // Mantener placa en mayúsculas
            if ($vehicle->isDirty('plate')) {
                $vehicle->plate = strtoupper(trim($vehicle->plate));
            }
        });
    }
}
