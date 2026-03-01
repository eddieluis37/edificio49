<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * ========================================
 * PRINTER CONFIGURATION MODEL
 * ========================================
 * 
 * Modelo para gestionar configuraciones de impresoras
 *  
 */
class PrinterConfiguration extends Model
{
    protected $fillable = [
        'name',
        'driver',
        'connection_type',
        'connection_string',
        'paper_width',
        'is_default',
        'settings',
        'is_active',
    ];

    protected $casts = [
        'settings' => 'array',
        'is_default' => 'boolean',
        'is_active' => 'boolean',
    ];

    // ========================================
    // SCOPES
    // ========================================

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeDefault($query)
    {
        return $query->where('is_default', true);
    }

    // ========================================
    // ACCESSORS
    // ========================================

    /**
     * Obtener configuración específica
     */
    public function getSetting(string $key, $default = null)
    {
        return data_get($this->settings, $key, $default);
    }

    /**
     * Establecer configuración específica
     */
    public function setSetting(string $key, $value): void
    {
        $settings = $this->settings ?? [];
        data_set($settings, $key, $value);
        $this->settings = $settings;
    }

    // ========================================
    // METHODS
    // ========================================

    /**
     * Establecer como impresora predeterminada
     */
    public function setAsDefault(): void
    {
        // Remover default de otras impresoras
        static::where('is_default', true)->update(['is_default' => false]);

        // Establecer esta como default
        $this->update(['is_default' => true]);
    }

    /**
     * Obtener configuración predeterminada
     */
    public static function getDefault(): ?self
    {
        return static::default()->active()->first();
    }
}
