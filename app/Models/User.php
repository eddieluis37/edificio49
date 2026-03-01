<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Support\Facades\Storage;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'active',
        'profile_photo',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'active' => 'boolean',
        ];
    }

    // =====================================
    // COMPUTED PROPERTIES
    // =====================================

    /**
     * URL completa de la foto de perfil
     */
    protected function profilePhotoUrl(): Attribute
    {
        return Attribute::make(
            get: fn() => $this->profile_photo
                ? asset('storage/' . $this->profile_photo)
                : asset('images/noimage.jpg')
        );
    }

    /**
     * Nombre del rol formateado
     */
    protected function roleName(): Attribute
    {
        return Attribute::make(
            get: fn() => match ($this->role) {
                'admin' => 'Administrador',
                'cashier' => 'Cajero',
                'viewer' => 'Visor',
                default => 'Sin Rol',
            }
        );
    }

    /**
     * Color del badge según el rol
     */
    protected function roleColor(): Attribute
    {
        return Attribute::make(
            get: fn() => match ($this->role) {
                'admin' => 'red',
                'cashier' => 'blue',
                'viewer' => 'green',
                default => 'gray',
            }
        );
    }

    /**
     * Icono según el rol
     */
    protected function roleIcon(): Attribute
    {
        return Attribute::make(
            get: fn() => match ($this->role) {
                'admin' => '👑',
                'cashier' => '💰',
                'viewer' => '👁️',
                default => '👤',
            }
        );
    }

    /**
     * Estado formateado
     */
    protected function statusLabel(): Attribute
    {
        return Attribute::make(
            get: fn() => $this->active ? 'Activo' : 'Inactivo'
        );
    }

    /**
     * Iniciales del nombre para avatar
     */
    protected function initials(): Attribute
    {
        return Attribute::make(
            get: function () {
                $words = explode(' ', $this->name);
                if (count($words) >= 2) {
                    return strtoupper(substr($words[0], 0, 1) . substr($words[1], 0, 1));
                }
                return strtoupper(substr($this->name, 0, 2));
            }
        );
    }

    // =====================================
    // SCOPES
    // =====================================

    /**
     * Scope para usuarios activos
     */
    public function scopeActive($query)
    {
        return $query->where('active', true);
    }

    /**
     * Scope para usuarios inactivos
     */
    public function scopeInactive($query)
    {
        return $query->where('active', false);
    }

    /**
     * Scope para filtrar por rol
     */
    public function scopeRole($query, string $role)
    {
        return $query->where('role', $role);
    }

    /**
     * Scope para administradores
     */
    public function scopeAdmins($query)
    {
        return $query->where('role', 'admin');
    }

    /**
     * Scope para cajeros
     */
    public function scopeCashiers($query)
    {
        return $query->where('role', 'cashier');
    }

    /**
     * Scope para visores
     */
    public function scopeViewers($query)
    {
        return $query->where('role', 'viewer');
    }

    /**
     * Scope para búsqueda general
     */
    public function scopeSearch($query, ?string $search)
    {
        if (!$search) {
            return $query;
        }

        return $query->where(function ($q) use ($search) {
            $q->where('name', 'like', "%{$search}%")
                ->orWhere('email', 'like', "%{$search}%");
        });
    }

    // =====================================
    // MÉTODOS AUXILIARES
    // =====================================

    /**
     * Verificar si el usuario es administrador
     */
    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    /**
     * Verificar si el usuario es cajero
     */
    public function isCashier(): bool
    {
        return $this->role === 'cashier';
    }

    /**
     * Verificar si el usuario es visor
     */
    public function isViewer(): bool
    {
        return $this->role === 'viewer';
    }

    /**
     * Verificar si el usuario está activo
     */
    public function isActive(): bool
    {
        return $this->active === true;
    }

    // =====================================
    // RELACIONES (para futuras expansiones)
    // =====================================

    /**
     * Rentas creadas por el usuario
     */
    public function rentals()
    {
        return $this->hasMany(\App\Models\Rental::class);
    }

    /**
     * Cierres de caja realizados por el usuario
     */
    public function cashClosures()
    {
        return $this->hasMany(\App\Models\CashClosure::class);
    }
}
