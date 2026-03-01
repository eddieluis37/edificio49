<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Storage;

class Type extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'image',
        'order',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'order' => 'integer',
    ];

    /**
     * Get the rates for the vehicle type.
     */
    public function rates(): HasMany
    {
        return $this->hasMany(Rate::class, 'type_id');
    }

    /**
     * Get the image URL attribute.
     */
    public function getImgAttribute(): string
    {
        if ($this->image) {
            return asset('storage/types/' . $this->image);
            //return Storage::disk('public')->url('types/' . $this->image); // no funciona en localhost,
        }

        return 'https://ui-avatars.com/api/?name=' . urlencode($this->name) . '&background=ef4444&color=fff&size=400';
    }


    /**
     * Boot the model.
     */
    protected static function boot(): void
    {
        parent::boot();

        static::creating(function ($vehicleType) {
            if (is_null($vehicleType->order)) {
                $vehicleType->order = static::max('order') + 1;
            }
        });

        static::deleting(function ($vehicleType) {
            // Eliminar imagen al borrar el registro
            if ($vehicleType->image) {
                Storage::disk('public')->delete('types/' . $vehicleType->image);
            }
        });
    }

    // app/Models/Type.php

    /**
     * Get icon emoji for type
     */
    public function getIconEmoji(): string
    {
        $name = strtolower($this->name ?? '');

        if (str_contains($name, 'moto')) {
            return '🏍️';
        } elseif (str_contains($name, 'camion') || str_contains($name, 'truck')) {
            return '🚚';
        } elseif (str_contains($name, 'bici')) {
            return '🚲';
        }

        return '🚗';
    }
}
