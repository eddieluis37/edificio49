<?php

namespace App\Models;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Cache;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * ========================================
 * COMPANY MODEL
 * ========================================
 */
class Company extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'name',
        'business_name',
        'rfc',
        'email',
        'phone',
        'mobile',
        'website',
        'address',
        'city',
        'state',
        'country',
        'postal_code',
        'logo_path',
        'logo_url',
        'receipt_footer',
        'receipt_terms',
        'show_logo_on_receipt',
        'timezone',
        'currency',
        'currency_symbol',
        'is_active',
    ];

    protected $casts = [
        'show_logo_on_receipt' => 'boolean',
        'is_active' => 'boolean',
    ];

    /**
     * Obtener la empresa activa / podemos usar caché para mejor performance (esto es valioso en entornos multitenant)
     */
    public static function current(): ?self
    {
        return Cache::remember('company_current', 3600, function () {
            return static::where('is_active', true)->first();
        });
    }

    /**
     * Obtener o crear empresa predeterminada
     */
    public static function firstOrDefault(): self
    {
        $company = static::current();

        if (!$company) {
            $company = static::create([
                'name' => config('app.name', 'Parki'),
                'address' => 'Configura tu dirección',
                'phone' => '000-000-0000',
                'receipt_footer' => 'Gracias por su preferencia',
                'is_active' => true,
            ]);
        }

        return $company;
    }

    // ========================================
    // MÉTODOS PARA RECIBOS
    // ========================================

    /**
     * Obtener datos formateados para recibos
     */
    public function getReceiptData(): array
    {
        return [
            'name' => $this->name,
            'business_name' => $this->business_name,
            'rfc' => $this->rfc,
            'address' => $this->getFullAddress(),
            'phone' => $this->phone,
            'mobile' => $this->mobile,
            'email' => $this->email,
            'website' => $this->website,
            'logo_path' => $this->getLogoPath(),
            'logo_url' => $this->getLogoUrl(),
            'footer' => $this->receipt_footer ?? 'Gracias por su preferencia',
            'terms' => $this->receipt_terms,
            'currency_symbol' => $this->currency_symbol ?? '$',
        ];
    }

    /**
     * Obtener dirección completa formateada
     */
    public function getFullAddress(): string
    {
        $parts = array_filter([
            $this->address,
            $this->city,
            $this->state,
            $this->postal_code,
        ]);

        return implode(', ', $parts);
    }

    /**
     * Obtener ruta del logo para impresión
     */
    public function getLogoPath(): ?string
    {
        if (!$this->logo_path || !$this->show_logo_on_receipt) {
            return null;
        }

        // Verificar si el archivo existe
        if (Storage::disk('public')->exists($this->logo_path)) {
            return storage_path('app/public/' . $this->logo_path);
        }

        return null;
    }

    /**
     * Obtener URL del logo
     */
    public function getLogoUrl(): ?string
    {
        if (!$this->logo_path) {
            return null;
        }

        if ($this->logo_url) {
            return $this->logo_url;
        }

        if (Storage::disk('public')->exists($this->logo_path)) {
            return asset('storage/' . $this->logo_path);
        }

        return null;
    }

    // ========================================
    // ACCESSORS & MUTATORS
    // ========================================

    /**
     * Formatear RFC / En mi país es RFC, quizá en el tuyo sea RUT/RUC,etc.
     */
    public function setRfcAttribute($value): void
    {
        $this->attributes['rfc'] = strtoupper($value);
    }

    /**
     * Formatear teléfono
     */
    public function setPhoneAttribute($value): void
    {
        $this->attributes['phone'] = preg_replace('/[^0-9]/', '', $value);
    }

    /**
     * Formatear móvil
     */
    public function setMobileAttribute($value): void
    {
        $this->attributes['mobile'] = preg_replace('/[^0-9]/', '', $value);
    }

    /**
     * Obtener teléfono formateado
     */
    public function getFormattedPhoneAttribute(): ?string
    {
        if (!$this->phone) return null;

        $phone = $this->phone;

        // Formato: XXX-XXX-XXXX
        if (strlen($phone) === 10) {
            return substr($phone, 0, 3) . '-' . substr($phone, 3, 3) . '-' . substr($phone, 6);
        }

        return $phone;
    }

    // ========================================
    // SCOPES
    // ========================================

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    // ========================================
    // MÉTODOS ESTÁTICOS
    // ========================================

    /**
     * Limpiar caché de empresa
     */
    public static function clearCache(): void
    {
        Cache::forget('company_current');
    }

    /**
     * Subir logo
     */
    public function uploadLogo($file): bool
    {
        try {
            // Eliminar logo anterior si existe
            if ($this->logo_path && Storage::disk('public')->exists($this->logo_path)) {
                Storage::disk('public')->delete($this->logo_path);
            }

            // Guardar nuevo logo
            $path = $file->store('logos', 'public');

            $this->logo_path = $path;
            $this->logo_url = asset('storage/' . $path);
            $this->save();

            // Limpiar caché
            static::clearCache();

            return true;
        } catch (\Exception $e) {
            Log::error('Error uploading logo', [
                'error' => $e->getMessage()
            ]);
            return false;
        }
    }

    /**
     * Eliminar logo
     */
    public function deleteLogo(): bool
    {
        try {
            if ($this->logo_path && Storage::disk('public')->exists($this->logo_path)) {
                Storage::disk('public')->delete($this->logo_path);
            }

            $this->logo_path = null;
            $this->logo_url = null;
            $this->save();

            static::clearCache();

            return true;
        } catch (\Exception $e) {
            Log::error('Error deleting logo', [
                'error' => $e->getMessage()
            ]);
            return false;
        }
    }
}
