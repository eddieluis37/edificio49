<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Carbon\Carbon;


class Rate extends Model
{
    use HasFactory, SoftDeletes;

    /*
    |--------------------------------------------------------------------------
    | TABLE CONFIGURATION
    |--------------------------------------------------------------------------
    */

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'rates';

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
    | CONSTANTS
    |--------------------------------------------------------------------------
    */

    /**
     * Available rate types
     */
    public const TYPE_HOURLY = 'hourly';
    public const TYPE_DAILY = 'daily';
    public const TYPE_MONTHLY = 'monthly';
    public const TYPE_FRACTIONAL = 'fractional';

    /**
     * Activation states
     */
    public const STATE_ACTIVE = true;
    public const STATE_INACTIVE = false;

    /**
     * Price limits
     */
    public const PRICE_MINIMUM = 0.01;
    public const PRICE_MAXIMUM = 999999.99;

    /**
     * Time limits (in minutes)
     */
    public const TIME_MINIMUM = 1;
    public const TIME_MAXIMUM = 525600; // 1 year in minutes

    /**
     * Time conversions
     */
    public const MINUTES_PER_HOUR = 60;
    public const MINUTES_PER_DAY = 1440;
    public const MINUTES_PER_MONTH = 43200; // 30 days

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
        'type_id',
        'description',
        'price',
        'time',
        'rate_type',
        'active',
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
    | CASTS & ATTRIBUTES
    |--------------------------------------------------------------------------
    */

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'type_id' => 'integer',
        'price' => 'decimal:2',
        'time' => 'integer',
        'active' => 'boolean',
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
        'formatted_price',
        'formatted_time',
        'rate_type_label',
    ];

    /*
    |--------------------------------------------------------------------------
    | RELATIONSHIPS
    |--------------------------------------------------------------------------
    */

    /**
     * Get the type that owns the rate.
     * 
     * Inverse relationship: A rate belongs to a vehicle type.
     *
     * @return BelongsTo<Type, Rate>
     */
    public function type(): BelongsTo
    {
        return $this->belongsTo(Type::class, 'type_id');
    }

    /*
    |--------------------------------------------------------------------------
    | ACCESSORS & MUTATORS (Attribute Pattern - Laravel 9+)
    |--------------------------------------------------------------------------
    */

    /**
     * Get the formatted price attribute.
     * 
     * Example: 150.50 → "$150.50"
     */
    protected function formattedPrice(): Attribute
    {
        return Attribute::make(
            get: fn(): string => '$' . number_format((float)$this->price, 2, '.', ','),
        );
    }

    /**
     * Get the formatted time attribute.
     * 
     * Examples:
     * - 30 minutes → "30 min"
     * - 60 minutes → "1h"
     * - 90 minutes → "1h 30min"
     * - 1440 minutes → "1 day"
     */
    protected function formattedTime(): Attribute
    {
        return Attribute::make(
            get: function (): string {
                if (is_null($this->time)) {
                    return 'N/A';
                }

                $minutes = $this->time;

                // Months (30 days)
                if ($minutes >= self::MINUTES_PER_MONTH) {
                    $months = floor($minutes / self::MINUTES_PER_MONTH);
                    $remainder = $minutes % self::MINUTES_PER_MONTH;

                    if ($remainder === 0) {
                        return $months . ($months === 1 ? ' month' : ' months');
                    }

                    $days = floor($remainder / self::MINUTES_PER_DAY);
                    if ($days > 0) {
                        return $months . ($months === 1 ? ' month' : ' months') . ' ' . $days . ($days === 1 ? ' day' : ' days');
                    }
                }

                // Days
                if ($minutes >= self::MINUTES_PER_DAY) {
                    $days = floor($minutes / self::MINUTES_PER_DAY);
                    $remainder = $minutes % self::MINUTES_PER_DAY;

                    if ($remainder === 0) {
                        return $days . ($days === 1 ? ' day' : ' days');
                    }

                    $hours = floor($remainder / self::MINUTES_PER_HOUR);
                    if ($hours > 0) {
                        return $days . 'd ' . $hours . 'h';
                    }
                }

                // Hours
                if ($minutes >= self::MINUTES_PER_HOUR) {
                    $hours = floor($minutes / self::MINUTES_PER_HOUR);
                    $mins = $minutes % self::MINUTES_PER_HOUR;

                    if ($mins > 0) {
                        return $hours . 'h ' . $mins . 'min';
                    }

                    return $hours . 'h';
                }

                // Minutes only
                return $minutes . ' min';
            }
        );
    }

    /**
     * Get the rate type label attribute.
     * 
     * Converts rate type to a readable label.
     */
    protected function rateTypeLabel(): Attribute
    {
        return Attribute::make(
            get: fn(): string => match ($this->rate_type) {
                self::TYPE_HOURLY => 'Hourly',
                self::TYPE_DAILY => 'Daily',
                self::TYPE_MONTHLY => 'Monthly',
                self::TYPE_FRACTIONAL => 'Fractional',
                default => 'Unknown',
            }
        );
    }

    /**
     * Get the price per minute.
     * 
     * Calculates the price per minute based on total time.
     */
    protected function pricePerMinute(): Attribute
    {
        return Attribute::make(
            get: function (): float {
                if (is_null($this->time) || $this->time === 0) {
                    return 0;
                }

                //return round($this->price / $this->time, 4);
                return round((float)$this->price / $this->time, 4);
            }
        );
    }

    /**
     * Determine if the rate is special (monthly or fractional).
     */
    protected function isSpecialRate(): Attribute
    {
        return Attribute::make(
            get: fn(): bool => in_array($this->rate_type, [self::TYPE_MONTHLY, self::TYPE_FRACTIONAL])
        );
    }

    /**
     * Ensure price is always positive.
     */
    protected function price(): Attribute
    {
        return Attribute::make(
            set: fn(float $value): float => max(self::PRICE_MINIMUM, min(self::PRICE_MAXIMUM, $value))
        );
    }

    /*
    |--------------------------------------------------------------------------
    | QUERY SCOPES
    |--------------------------------------------------------------------------
    */

    /**
     * Scope: Active rates.
     *
     * @param Builder<Rate> $query
     * @return Builder<Rate>
     */
    public function scopeActive(Builder $query): Builder
    {
        return $query->where('active', self::STATE_ACTIVE);
    }

    /**
     * Scope: Inactive rates.
     *
     * @param Builder<Rate> $query
     * @return Builder<Rate>
     */
    public function scopeInactive(Builder $query): Builder
    {
        return $query->where('active', self::STATE_INACTIVE);
    }

    /**
     * Scope: Filter by vehicle type.
     *
     * @param Builder<Rate> $query
     * @param int $typeId
     * @return Builder<Rate>
     */
    public function scopeByType(Builder $query, int $typeId): Builder
    {
        return $query->where('type_id', $typeId);
    }

    /**
     * Scope: Filter by rate type.
     *
     * @param Builder<Rate> $query
     * @param string $rateType
     * @return Builder<Rate>
     */
    public function scopeByRateType(Builder $query, string $rateType): Builder
    {
        return $query->where('rate_type', $rateType);
    }

    /**
     * Scope: Hourly rates.
     *
     * @param Builder<Rate> $query
     * @return Builder<Rate>
     */
    public function scopeHourly(Builder $query): Builder
    {
        return $query->byRateType(self::TYPE_HOURLY);
    }

    /**
     * Scope: Daily rates.
     *
     * @param Builder<Rate> $query
     * @return Builder<Rate>
     */
    public function scopeDaily(Builder $query): Builder
    {
        return $query->byRateType(self::TYPE_DAILY);
    }

    /**
     * Scope: Monthly rates.
     *
     * @param Builder<Rate> $query
     * @return Builder<Rate>
     */
    public function scopeMonthly(Builder $query): Builder
    {
        return $query->byRateType(self::TYPE_MONTHLY);
    }

    /**
     * Scope: Fractional rates.
     *
     * @param Builder<Rate> $query
     * @return Builder<Rate>
     */
    public function scopeFractional(Builder $query): Builder
    {
        return $query->byRateType(self::TYPE_FRACTIONAL);
    }

    /**
     * Scope: Order by price.
     *
     * @param Builder<Rate> $query
     * @param string $direction
     * @return Builder<Rate>
     */
    public function scopeOrderByPrice(Builder $query, string $direction = 'asc'): Builder
    {
        return $query->orderBy('price', $direction);
    }

    /**
     * Scope: Order by time.
     *
     * @param Builder<Rate> $query
     * @param string $direction
     * @return Builder<Rate>
     */
    public function scopeOrderByTime(Builder $query, string $direction = 'asc'): Builder
    {
        return $query->orderBy('time', $direction);
    }

    /**
     * Scope: Search by description.
     *
     * @param Builder<Rate> $query
     * @param string $search
     * @return Builder<Rate>
     */
    public function scopeSearch(Builder $query, string $search): Builder
    {
        if (empty($search)) {
            return $query;
        }

        return $query->where('description', 'like', '%' . $search . '%');
    }

    /**
     * Scope: Cheapest rate.
     *
     * @param Builder<Rate> $query
     * @return Builder<Rate>
     */
    public function scopeCheapest(Builder $query): Builder
    {
        return $query->active()->orderByPrice('asc');
    }

    /**
     * Scope: Most expensive rate.
     *
     * @param Builder<Rate> $query
     * @return Builder<Rate>
     */
    public function scopeMostExpensive(Builder $query): Builder
    {
        return $query->active()->orderByPrice('desc');
    }

    /*
    |--------------------------------------------------------------------------
    | HELPER METHODS
    |--------------------------------------------------------------------------
    */

    /**
     * Activate the rate.
     *
     * @return bool
     */
    public function activate(): bool
    {
        return $this->update(['active' => self::STATE_ACTIVE]);
    }

    /**
     * Deactivate the rate.
     *
     * @return bool
     */
    public function deactivate(): bool
    {
        return $this->update(['active' => self::STATE_INACTIVE]);
    }

    /**
     * Toggle activation status.
     *
     * @return bool
     */
    public function toggleActive(): bool
    {
        return $this->update(['active' => !$this->active]);
    }

    /**
     * Determine if the rate is active.
     *
     * @return bool
     */
    public function isActive(): bool
    {
        return $this->active === self::STATE_ACTIVE;
    }

    /**
     * Determine if the rate is inactive.
     *
     * @return bool
     */
    public function isInactive(): bool
    {
        return !$this->isActive();
    }

    /**
     * Calculate total price for given minutes.
     *
     * @param int $minutes
     * @return float
     */
    public function calculatePrice(int $minutes): float
    {
        if (is_null($this->time) || $this->time === 0) {
            return $this->price;
        }

        $fractions = ceil($minutes / $this->time);

        return round($this->price * $fractions, 2);
    }

    /**
     * Get the array of available rate types.
     *
     * @return array<string, string>
     */
    public static function getRateTypes(): array
    {
        return [
            self::TYPE_HOURLY => 'Hourly',
            self::TYPE_DAILY => 'Daily',
            self::TYPE_MONTHLY => 'Monthly',
            self::TYPE_FRACTIONAL => 'Fractional',
        ];
    }

    /**
     * Get the default time for a given rate type (in minutes).
     *
     * @param string $rateType
     * @return int
     */
    public static function getDefaultTime(string $rateType): int
    {
        return match ($rateType) {
            self::TYPE_HOURLY => self::MINUTES_PER_HOUR,
            self::TYPE_DAILY => self::MINUTES_PER_DAY,
            self::TYPE_MONTHLY => self::MINUTES_PER_MONTH,
            self::TYPE_FRACTIONAL => 15, // 15 minutes by default
            default => self::MINUTES_PER_HOUR,
        };
    }

    /*
    |--------------------------------------------------------------------------
    | VALIDATION
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
            'type_id' => ['required', 'integer', 'exists:types,id'],
            'description' => ['required', 'string', 'min:3', 'max:100'],
            'price' => ['required', 'numeric', 'min:' . self::PRICE_MINIMUM, 'max:' . self::PRICE_MAXIMUM],
            'time' => ['nullable', 'integer', 'min:' . self::TIME_MINIMUM, 'max:' . self::TIME_MAXIMUM],
            'rate_type' => ['required', 'string', 'in:' . implode(',', array_keys(self::getRateTypes()))],
            'active' => ['boolean'],
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
            'type_id.required' => 'The vehicle type is required.',
            'type_id.exists' => 'The selected vehicle type does not exist.',
            'description.required' => 'The description is required.',
            'description.min' => 'The description must be at least :min characters.',
            'description.max' => 'The description may not exceed :max characters.',
            'price.required' => 'The price is required.',
            'price.numeric' => 'The price must be a valid number.',
            'price.min' => 'The price must be greater than $:min.',
            'price.max' => 'The price may not exceed $:max.',
            'time.integer' => 'The time must be an integer.',
            'time.min' => 'The time must be at least :min minutes.',
            'time.max' => 'The time may not exceed :max minutes.',
            'rate_type.required' => 'The rate type is required.',
            'rate_type.in' => 'The selected rate type is invalid.',
        ];
    }

    /*
    |--------------------------------------------------------------------------
    | MODEL EVENTS
    |--------------------------------------------------------------------------
    */

    /**
     * The "booted" method of the model.
     */
    protected static function booted(): void
    {
        // Event: Before creating
        static::creating(function (Rate $rate) {
            // If time is not specified and rate type requires it, assign default time
            if (is_null($rate->time) && $rate->rate_type !== self::TYPE_FRACTIONAL) {
                $rate->time = self::getDefaultTime($rate->rate_type);
            }

            // By default, rates are created as active
            if (is_null($rate->active)) {
                $rate->active = self::STATE_ACTIVE;
            }
        });

        // Event: Before updating
        static::updating(function (Rate $rate) {
            // Validate that price is positive
            if ($rate->isDirty('price') && $rate->price <= 0) {
                $rate->price = self::PRICE_MINIMUM;
            }
        });

        // Event: After deleting (soft delete)
        static::deleted(function (Rate $rate) {
            // Here you could add additional logic
            // For example, notify administrators
        });
    }

    /*
    |--------------------------------------------------------------------------
    | SERIALIZATION
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

        // Add additional data for APIs
        if (request()->wantsJson()) {
            $array['type_info'] = $this->type?->only(['id', 'description']);
        }

        return $array;
    }
}
