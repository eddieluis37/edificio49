<?php


namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Casts\Attribute;

/**
 * Customer Model
 * 
 * Gestiona los clientes del estacionamiento y sus vehículos.
 */
class Customer extends Model
{
    use HasFactory, SoftDeletes;

    /*
    |--------------------------------------------------------------------------
    | TABLE CONFIGURATION
    |--------------------------------------------------------------------------
    */

    protected $table = 'customers';
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
        'name',
        'email',
        'phone',
        'mobile',
        'address',
        'city',
        'state',
        'zip_code',
        'country',
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
        'is_active' => 'boolean',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime',
    ];

    protected $appends = [
        'full_address',
        'contact_info',
    ];

    /*
    |--------------------------------------------------------------------------
    | RELATIONSHIPS
    |--------------------------------------------------------------------------
    */

    /**
     * Vehículos del cliente
     */
    public function vehicles(): HasMany
    {
        return $this->hasMany(Vehicle::class, 'customer_id');
    }

    /**
     * Rentas del cliente
     */
    public function rentals(): HasMany
    {
        return $this->hasMany(Rental::class, 'customer_id');
    }

    /*
    |--------------------------------------------------------------------------
    | ACCESSORS & MUTATORS
    |--------------------------------------------------------------------------
    */

    /**
     * Dirección completa formateada
     */
    protected function fullAddress(): Attribute
    {
        return Attribute::make(
            get: function (): string {
                $parts = array_filter([
                    $this->address,
                    $this->city,
                    $this->state,
                    $this->zip_code,
                    $this->country,
                ]);

                return implode(', ', $parts) ?: 'N/A';
            }
        );
    }

    /**
     * Información de contacto formateada
     */
    protected function contactInfo(): Attribute
    {
        return Attribute::make(
            get: function (): string {
                $contacts = [];

                if ($this->phone) {
                    $contacts[] = '📞 ' . $this->phone;
                }

                if ($this->mobile) {
                    $contacts[] = '📱 ' . $this->mobile;
                }

                if ($this->email) {
                    $contacts[] = '📧 ' . $this->email;
                }

                return implode(' | ', $contacts) ?: 'Sin contacto';
            }
        );
    }

    /*
    |--------------------------------------------------------------------------
    | QUERY SCOPES
    |--------------------------------------------------------------------------
    */

    /**
     * Scope: Clientes activos
     */
    public function scopeActive(Builder $query): Builder
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope: Clientes inactivos
     */
    public function scopeInactive(Builder $query): Builder
    {
        return $query->where('is_active', false);
    }

    /**
     * Scope: Buscar por nombre, email o teléfono
     */
    public function scopeSearch(Builder $query, string $search): Builder
    {
        if (empty($search)) {
            return $query;
        }

        return $query->where(function ($q) use ($search) {
            $q->where('name', 'like', '%' . $search . '%')
                ->orWhere('email', 'like', '%' . $search . '%')
                ->orWhere('phone', 'like', '%' . $search . '%')
                ->orWhere('mobile', 'like', '%' . $search . '%');
        });
    }

    /**
     * Scope: Con vehículos
     */
    public function scopeWithVehicles(Builder $query): Builder
    {
        return $query->has('vehicles');
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

    /*
    |--------------------------------------------------------------------------
    | HELPER METHODS
    |--------------------------------------------------------------------------
    */

    /**
     * Activar cliente
     */
    public function activate(): bool
    {
        return $this->update(['is_active' => true]);
    }

    /**
     * Desactivar cliente
     */
    public function deactivate(): bool
    {
        return $this->update(['is_active' => false]);
    }

    /**
     * Obtener total de rentas
     */
    public function getTotalRentals(): int
    {
        return $this->rentals()->count();
    }

    /**
     * Obtener rentas abiertas
     */
    public function getOpenRentals()
    {
        return $this->rentals()->open()->get();
    }

    /**
     * Tiene rentas abiertas?
     */
    public function hasOpenRentals(): bool
    {
        return $this->rentals()->open()->exists();
    }

    /**
     * Total gastado en rentas
     */
    public function getTotalSpent(): float
    {
        return (float) $this->rentals()
            ->where('status', Rental::STATUS_CLOSED)
            ->sum('total_amount');
    }

    /*
    |--------------------------------------------------------------------------
    | VALIDATION RULES
    |--------------------------------------------------------------------------
    */

    public static function rules(?int $id = null): array
    {
        return [
            'name' => ['required', 'string', 'min:3', 'max:200'],
            'email' => ['nullable', 'email', 'max:200', $id ? 'unique:customers,email,' . $id : 'unique:customers,email'],
            'phone' => ['nullable', 'string', 'max:20'],
            'mobile' => ['nullable', 'string', 'max:20'],
            'address' => ['nullable', 'string', 'max:500'],
            'city' => ['nullable', 'string', 'max:100'],
            'state' => ['nullable', 'string', 'max:100'],
            'zip_code' => ['nullable', 'string', 'max:20'],
            'country' => ['nullable', 'string', 'max:100'],
            'is_active' => ['boolean'],
        ];
    }

    public static function messages(): array
    {
        return [
            'name.required' => 'El nombre es obligatorio',
            'name.min' => 'El nombre debe tener al menos 3 caracteres',
            'email.email' => 'El email no es válido',
            'email.unique' => 'Este email ya está registrado',
        ];
    }

    /*
    |--------------------------------------------------------------------------
    | MODEL EVENTS
    |--------------------------------------------------------------------------
    */

    protected static function booted(): void
    {
        static::creating(function (Customer $customer) {
            if (is_null($customer->is_active)) {
                $customer->is_active = true;
            }
        });
    }
}
