<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Symfony\Component\HttpFoundation\Response;

class PreventInstalledAccess
{

    public function handle(Request $request, Closure $next): Response
    {
        // Si la aplicación ya está instalada, redirigir al login
        if ($this->isInstalled()) {
            return redirect()->route('login');
        }

        return $next($request);
    }

    /**
     * Verificar si la aplicación está instalada
     */
    private function isInstalled(): bool
    {
        // Verificar archivo de instalación
        if (!File::exists(storage_path('installed'))) {
            return false;
        }

        // Verificar conexión a base de datos y tabla users
        try {
            DB::connection()->getPdo();

            if (!DB::getSchemaBuilder()->hasTable('users')) {
                return false;
            }

            return true;
        } catch (\Exception $e) {
            return false;
        }
    }
}
