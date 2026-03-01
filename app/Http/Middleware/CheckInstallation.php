<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Symfony\Component\HttpFoundation\Response;

class CheckInstallation
{
    /**
     * Rutas que NO requieren instalación completa
     */
    protected array $exceptRoutes = [
        'setup.wizard',
    ];
    /*
     'login',
        'register',
        'password.*',
    */

    public function handle(Request $request, Closure $next): Response
    {
        //  CRÍTICO: Permitir TODAS las peticiones a /setup (incluidas Livewire)
        if ($request->is('setup') || $request->is('setup/*') || $request->is('livewire/*')) {
            return $next($request);
        }

        // Si es ruta de setup, permitir
        if ($request->routeIs('setup.wizard')) {
            return $next($request);
        }

        try {
            // Verificar si la aplicación ya está instalada
            if (!$this->isInstalled()) {
                // Si no está instalado, redirigir al wizard
                return redirect()->route('setup.wizard');
            }
        } catch (\Exception $e) {
            // Si hay cualquier error al verificar la instalación,
            // redirigir al wizard para que el usuario corrija la configuración
            return redirect()->route('setup.wizard');
        }

        return $next($request);
    }

    /**
     * Determinar si el middleware debe omitirse para esta solicitud
     */
    protected function shouldBypass(Request $request): bool
    {
        // Si es la ruta de setup, siempre permitir
        if ($request->routeIs('setup.wizard')) {
            return true;
        }

        // Verificar rutas exceptuadas
        foreach ($this->exceptRoutes as $route) {
            if ($request->routeIs($route)) {
                return true;
            }
        }

        return false;
    }

    /**
     * Verificar si la aplicación está instalada
     */
    private function isInstalled(): bool
    {
        // Método 1: Verificar archivo de instalación
        if (!File::exists(storage_path('installed'))) {
            return false;
        }

        // Método 2: Verificar configuración de base de datos
        if (!$this->isDatabaseConfigured()) {
            return false;
        }

        // Método 3: Verificar conexión a base de datos
        try {
            // Usar un timeout corto para evitar bloqueos
            config(['database.connections.mysql.options' => [
                \PDO::ATTR_TIMEOUT => 2,
                \PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION,
            ]]);

            DB::connection()->getPdo();

            // Verificar que exista la tabla users (tabla principal)
            if (!DB::getSchemaBuilder()->hasTable('users')) {
                // Si no existe la tabla users, no está instalado
                return false;
            }

            return true;
        } catch (\Exception $e) {
            // Si hay cualquier error de conexión, considerar como NO instalado
            // Esto incluye credenciales incorrectas, servidor caído, etc.
            return false;
        }
    }

    /**
     * Verificar si la base de datos está configurada en .env
     */
    private function isDatabaseConfigured(): bool
    {
        $dbHost = config('database.connections.mysql.host');
        $dbName = config('database.connections.mysql.database');
        $dbUser = config('database.connections.mysql.username');

        // Si alguno está vacío o es el valor por defecto, no está configurado
        if (empty($dbHost) || empty($dbName) || empty($dbUser)) {
            return false;
        }

        // Si la base de datos es 'laravel' (valor por defecto), probablemente no está configurado
        if ($dbName === 'laravel') {
            return false;
        }

        return true;
    }
}
