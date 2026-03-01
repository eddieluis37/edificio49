<?php


namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Carbon\Carbon;

/**
 * Cash Closure Model
 * 
 * Registro de cortes de caja realizados.
 */
class CashClosure extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'cash_closures';

    protected $fillable = [
        'user_id',
        'cashier_id',
        'period_start',
        'period_end',
        'expected_cash',
        'total_rentals',
        'average_per_rental',
        'real_cash',
        'difference',
        'open_tickets',
        'had_open_tickets',
        'notes',
        'status',
    ];

    protected $casts = [
        'user_id' => 'integer',
        'cashier_id' => 'integer',
        'period_start' => 'datetime',
        'period_end' => 'datetime',
        'expected_cash' => 'decimal:2',
        'total_rentals' => 'integer',
        'average_per_rental' => 'decimal:2',
        'real_cash' => 'decimal:2',
        'difference' => 'decimal:2',
        'open_tickets' => 'integer',
        'had_open_tickets' => 'boolean',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime',
    ];

    /*
    |--------------------------------------------------------------------------
    | RELATIONSHIPS
    |--------------------------------------------------------------------------
    */

    /**
     * Usuario que realizó el corte
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Usuario cajero (si es corte individual)
     */
    public function cashier(): BelongsTo
    {
        return $this->belongsTo(User::class, 'cashier_id');
    }

    /*
    |--------------------------------------------------------------------------
    | ACCESSORS
    |--------------------------------------------------------------------------
    */

    /**
     * Diferencia formateada
     */
    protected function formattedDifference(): Attribute
    {
        return Attribute::make(
            get: fn(): string => '$' . number_format(abs((float)$this->difference), 2),
        );
    }

    /**
     * Tipo de diferencia (sobrante/faltante)
     */
    protected function differenceType(): Attribute
    {
        return Attribute::make(
            get: fn(): string => $this->difference >= 0 ? 'sobrante' : 'faltante',
        );
    }

    /**
     * Nombre del período
     */
    protected function periodName(): Attribute
    {
        return Attribute::make(
            get: function (): string {
                if (!$this->period_start || !$this->period_end) {
                    return 'N/A';
                }

                return $this->period_start->format('d/m/Y H:i') . ' - ' .
                    $this->period_end->format('d/m/Y H:i');
            }
        );
    }

    /*
    |--------------------------------------------------------------------------
    | SCOPES
    |--------------------------------------------------------------------------
    */

    /**
     * Scope: Por usuario que realizó el corte
     */
    public function scopeByUser(Builder $query, int $userId): Builder
    {
        return $query->where('user_id', $userId);
    }

    /**
     * Scope: Por cajero
     */
    public function scopeByCashier(Builder $query, int $cashierId): Builder
    {
        return $query->where('cashier_id', $cashierId);
    }

    /**
     * Scope: Por período
     */
    public function scopeByPeriod(Builder $query, Carbon $start, Carbon $end): Builder
    {
        return $query->where('period_start', '>=', $start)
            ->where('period_end', '<=', $end);
    }

    /**
     * Scope: Cortes de hoy
     */
    public function scopeToday(Builder $query): Builder
    {
        return $query->whereDate('created_at', today());
    }

    /**
     * Scope: Cortes con tickets abiertos
     */
    public function scopeWithOpenTickets(Builder $query): Builder
    {
        return $query->where('had_open_tickets', true);
    }

    /**
     * Scope: Con diferencia (sobrante o faltante)
     */
    public function scopeWithDifference(Builder $query): Builder
    {
        return $query->where('difference', '!=', 0);
    }

    /*
    |--------------------------------------------------------------------------
    | MÉTODOS ESTÁTICOS
    |--------------------------------------------------------------------------
    */

    /**
     * Verificar si ya existe un corte para el período y cajero
     */
    public static function existsForPeriod(?int $cashierId, Carbon $start, Carbon $end): bool
    {
        $query = self::query()
            ->where('period_start', '<=', $end)
            ->where('period_end', '>=', $start);

        if ($cashierId) {
            $query->where('cashier_id', $cashierId);
        } else {
            $query->whereNull('cashier_id'); // Corte general
        }

        return $query->exists();
    }

    /**
     * Crear nuevo corte de caja
     */
    public static function createClosure(array $data): self
    {
        return self::create([
            'user_id' => auth()->id(),
            'cashier_id' => $data['cashier_id'] ?? null,
            'period_start' => $data['period_start'],
            'period_end' => $data['period_end'],
            'expected_cash' => $data['expected_cash'],
            'total_rentals' => $data['total_rentals'],
            'average_per_rental' => $data['total_rentals'] > 0
                ? $data['expected_cash'] / $data['total_rentals']
                : 0,
            'real_cash' => $data['real_cash'],
            'difference' => $data['real_cash'] - $data['expected_cash'],
            'open_tickets' => $data['open_tickets'] ?? 0,
            'had_open_tickets' => ($data['open_tickets'] ?? 0) > 0,
            'notes' => $data['notes'] ?? null,
            'status' => 'closed',
        ]);
    }
}
