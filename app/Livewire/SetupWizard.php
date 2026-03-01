<?php

namespace App\Livewire;

use PDO;
use PDOException;
use App\Models\User;
use Livewire\Component;
use Livewire\Attributes\Layout;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Artisan;

/**
 * ========================================
 * SETUP WIZARD - PARKY
 * ========================================
 * 
 * Instalador universal para Parky que funciona en:
 * - PC local (localhost, 127.0.0.1, etc)
 * - Hosting con dominio (example.com)
 * - Hosting con subdominio (parky.example.com)
 * 

 */
class SetupWizard extends Component
{
    // ========================================
    // PROPIEDADES PÚBLICAS
    // ========================================

    // Paso actual del wizard (1-5)
    public int $currentStep = 1;
    public int $totalSteps = 5;

    // Configuración de base de datos
    public string $dbHost = '127.0.0.1';
    public string $dbPort = '3306';
    public string $dbName = '';
    public string $dbUsername = 'root';
    public string $dbPassword = '';

    // Configuración de aplicación
    public string $appName = 'Parky';
    public string $appUrl = '';

    // Usuario administrador
    public string $adminName = '';
    public string $adminEmail = '';
    public string $adminPassword = '';
    public string $adminPasswordConfirmation = '';

    // Control del proceso
    public bool $isProcessing = false;
    public int $progress = 0;
    public string $currentProcess = '';
    public array $processLogs = [];
    public bool $setupComplete = false;

    // Verificación de requisitos
    public array $requirements = [];
    public bool $requirementsMet = false;

    // ========================================
    // MOUNT
    // ========================================

    public function mount()
    {
        // Redireccionar si ya está instalado
        if ($this->isAlreadyInstalled()) {
            return redirect()->route('login');
        }

        // Auto-detectar URL del sistema
        $this->appUrl = request()->getSchemeAndHttpHost();

        // Verificar requisitos del servidor
        $this->checkRequirements();
    }

    // ========================================
    // VERIFICACIÓN DE REQUISITOS
    // ========================================

    public function checkRequirements()
    {
        $this->requirements = [
            [
                'name' => 'PHP Version >= 8.2',
                'met' => version_compare(PHP_VERSION, '8.2.0', '>='),
                'current' => PHP_VERSION
            ],
            [
                'name' => 'PDO MySQL Extension',
                'met' => extension_loaded('pdo_mysql'),
                'current' => extension_loaded('pdo_mysql') ? 'Installed' : 'Missing'
            ],
            [
                'name' => 'OpenSSL Extension',
                'met' => extension_loaded('openssl'),
                'current' => extension_loaded('openssl') ? 'Installed' : 'Missing'
            ],
            [
                'name' => 'Mbstring Extension',
                'met' => extension_loaded('mbstring'),
                'current' => extension_loaded('mbstring') ? 'Installed' : 'Missing'
            ],
            [
                'name' => 'Tokenizer Extension',
                'met' => extension_loaded('tokenizer'),
                'current' => extension_loaded('tokenizer') ? 'Installed' : 'Missing'
            ],
            [
                'name' => 'GD Extension',
                'met' => extension_loaded('gd'),
                'current' => extension_loaded('gd') ? 'Installed' : 'Missing'
            ],
            [
                'name' => '.env File Writable',
                'met' => is_writable(base_path('.env')),
                'current' => is_writable(base_path('.env')) ? 'Yes' : 'No'
            ],
            [
                'name' => 'Storage Directory Writable',
                'met' => is_writable(storage_path()),
                'current' => is_writable(storage_path()) ? 'Yes' : 'No'
            ],
        ];

        // Verificar si todos los requisitos se cumplen
        $this->requirementsMet = collect($this->requirements)->every(fn($req) => $req['met']);
    }

    // ========================================
    // NAVEGACIÓN ENTRE PASOS
    // ========================================

    public function nextStep()
    {
        // Validar datos del paso actual
        $rules = $this->getValidationRules();

        if (!empty($rules)) {
            $this->validate($rules);
        }

        // Avanzar al siguiente paso
        if ($this->currentStep < $this->totalSteps) {
            $this->currentStep++;
        }
    }

    public function previousStep()
    {
        if ($this->currentStep > 1) {
            $this->currentStep--;
        }
    }

    // ========================================
    // PROBAR CONEXIÓN A BASE DE DATOS
    // ========================================

    public function testConnection()
    {
        try {
            $pdo = new PDO(
                "mysql:host={$this->dbHost};port={$this->dbPort}",
                $this->dbUsername,
                $this->dbPassword,
                [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
            );

            $this->dispatch('notify', [
                'type' => 'success',
                'message' => '✅ Connection successful'
            ]);

            return true;
        } catch (PDOException $e) {
            $this->dispatch('notify', [
                'type' => 'error',
                'message' => '❌ Connection failed: ' . $e->getMessage()
            ]);

            return false;
        }
    }

    // ========================================
    // INICIAR INSTALACIÓN
    // ========================================

    public function startInstallation()
    {
        // Verificar conexión antes de comenzar
        if (!$this->testConnection()) {
            return;
        }

        $this->isProcessing = true;
        $this->progress = 0;
        $this->processLogs = [];

        try {
            // Paso 1: Verificar/Crear base de datos (20%)
            $this->updateProgress(5, 'Checking database...');
            $this->handleDatabase();

            // Paso 2: Actualizar archivo .env (40%)
            $this->updateProgress(25, 'Updating configuration...');
            $this->updateEnvironment();

            // Paso 3: Ejecutar migraciones (60%)
            $this->updateProgress(45, 'Running migrations...');
            $this->runMigrations();

            // Paso 4: Insertar datos iniciales (80%)
            $this->updateProgress(65, 'Seeding database...');
            $this->seedDatabase();

            // Paso 5: Crear usuario administrador (90%)
            $this->updateProgress(82, 'Creating admin user...');
            $this->createAdmin();

            // Paso 6: Generar APP_KEY (95%)
            $this->updateProgress(90, 'Generating app key...');
            $this->generateAppKey();

            // Paso 7: Finalizar (100%)
            $this->updateProgress(97, 'Finalizing installation...');
            $this->markAsInstalled();

            // Completado
            $this->updateProgress(100, '✅ Installation completed successfully');
            $this->setupComplete = true;

            $this->dispatch('notify', [
                'type' => 'success',
                'message' => '🎉 Installation completed!'
            ]);

            // Redireccionar automáticamente
            $this->dispatch('setupComplete');
        } catch (\Exception $e) {
            $this->addLog('error', '❌ Critical error: ' . $e->getMessage());

            Log::error('Setup Wizard Error', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            $this->dispatch('notify', [
                'type' => 'error',
                'message' => '❌ Installation failed: ' . $e->getMessage()
            ]);
        } finally {
            $this->isProcessing = false;
        }
    }

    // ========================================
    // MÉTODOS PRIVADOS DE INSTALACIÓN
    // ========================================

    /**
     * Verificar o crear la base de datos
     */
    private function handleDatabase()
    {
        try {
            $pdo = new PDO(
                "mysql:host={$this->dbHost};port={$this->dbPort}",
                $this->dbUsername,
                $this->dbPassword,
                [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
            );

            // Verificar si la base de datos existe
            $stmt = $pdo->query("SHOW DATABASES LIKE '{$this->dbName}'");
            $exists = $stmt->rowCount() > 0;

            if (!$exists) {
                // Crear base de datos con charset UTF-8
                $pdo->exec("CREATE DATABASE `{$this->dbName}` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");
                $this->addLog('success', "✅ Database '{$this->dbName}' created");
            } else {
                $this->addLog('info', "ℹ️ Database '{$this->dbName}' already exists");
            }

            // Actualizar configuración de Laravel en tiempo real
            Config::set('database.connections.mysql.host', $this->dbHost);
            Config::set('database.connections.mysql.port', $this->dbPort);
            Config::set('database.connections.mysql.database', $this->dbName);
            Config::set('database.connections.mysql.username', $this->dbUsername);
            Config::set('database.connections.mysql.password', $this->dbPassword);

            // Reconectar con nueva configuración
            DB::purge('mysql');
            DB::reconnect('mysql');
        } catch (PDOException $e) {
            throw new \Exception('Database error: ' . $e->getMessage());
        }
    }

    /**
     * Actualizar archivo .env con la configuración
     */
    private function updateEnvironment()
    {
        try {
            $envPath = base_path('.env');

            // Crear .env desde .env.example si no existe
            if (!File::exists($envPath)) {
                File::copy(base_path('.env.example'), $envPath);
                $this->addLog('info', 'ℹ️ .env file created from .env.example');
            }

            $env = File::get($envPath);

            // Reemplazar valores
            $replacements = [
                'APP_NAME' => $this->appName,
                'APP_URL' => $this->appUrl,
                'DB_HOST' => $this->dbHost,
                'DB_PORT' => $this->dbPort,
                'DB_DATABASE' => $this->dbName,
                'DB_USERNAME' => $this->dbUsername,
                'DB_PASSWORD' => $this->dbPassword,
            ];

            foreach ($replacements as $key => $value) {
                $env = preg_replace(
                    "/^{$key}=.*/m",
                    "{$key}=\"{$value}\"",
                    $env
                );
            }

            File::put($envPath, $env);
            $this->addLog('success', '✅ Environment file updated');
        } catch (\Exception $e) {
            throw new \Exception('Failed to update .env: ' . $e->getMessage());
        }
    }

    /**
     * Ejecutar migraciones para crear todas las tablas de Parky
     */
    private function runMigrations()
    {
        try {
            Artisan::call('migrate:fresh', ['--force' => true]);
            $this->addLog('success', '✅ All tables created successfully');
        } catch (\Exception $e) {
            throw new \Exception('Migration failed: ' . $e->getMessage());
        }
    }

    /**
     * Insertar datos de prueba específicos de Parky
     */
    private function seedDatabase()
    {
        try {
            // Capturar output de Artisan
            Artisan::call('db:seed', ['--force' => true]);
            $output = Artisan::output();

            // Verificar si hubo errores en el output
            if (str_contains($output, 'Error') || str_contains($output, 'Exception')) {
                throw new \Exception('Seeder output contains errors: ' . $output);
            }

            // Verificar que realmente se insertaron datos
            $userCount = DB::table('users')->count();
            $rateCount = DB::table('vehicle_rates')->count();

            if ($userCount === 0 || $rateCount === 0) {
                throw new \Exception('Seeders ran but no data was inserted. Users: ' . $userCount . ', Rates: ' . $rateCount);
            }

            $this->addLog('success', '✅ Sample data inserted successfully');
            $this->addLog('info', "Users: {$userCount}, Rates: {$rateCount}");
        } catch (\Exception $e) {
            $this->addLog('error', '❌ Seeder failed: ' . $e->getMessage());

            Log::error('Seeder Error in Setup Wizard', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            // NO detener la instalación por falta de datos de prueba
        }
    }

    /**
     * Crear usuario administrador principal
     */
    private function createAdmin()
    {
        try {
            DB::table('users')->updateOrInsert(
                ['email' => $this->adminEmail],
                [
                    'name' => $this->adminName,
                    'email' => $this->adminEmail,
                    'password' => Hash::make($this->adminPassword),
                    'created_at' => now(),
                    'updated_at' => now(),
                ]
            );

            $this->addLog('success', "✅ Admin user '{$this->adminName}' created");
        } catch (\Exception $e) {
            throw new \Exception('Failed to create admin: ' . $e->getMessage());
        }
    }

    /**
     * Generar clave de aplicación (APP_KEY)
     */
    private function generateAppKey()
    {
        try {
            if (empty(config('app.key'))) {
                Artisan::call('key:generate', ['--force' => true]);
                $this->addLog('success', '✅ Application key generated');
            } else {
                $this->addLog('info', 'ℹ️ Application key already exists');
            }
        } catch (\Exception $e) {
            $this->addLog('warning', '⚠️ Could not generate APP_KEY');
        }
    }

    /**
     * Marcar como instalado creando archivo de control
     */
    private function markAsInstalled()
    {
        try {
            $installedPath = storage_path('installed');

            File::put($installedPath, json_encode([
                'installed_at' => now()->toDateTimeString(),
                'version' => '1.0',
                'app_name' => $this->appName,
                'app_url' => $this->appUrl,
                'database' => $this->dbName,
                'admin_email' => $this->adminEmail,
            ], JSON_PRETTY_PRINT));

            $this->addLog('success', '✅ Installation marker created');
        } catch (\Exception $e) {
            $this->addLog('warning', '⚠️ Could not create installation marker');
        }
    }

    // ========================================
    // MÉTODOS AUXILIARES
    // ========================================

    /**
     * Verificar si Parky ya fue instalado
     */
    private function isAlreadyInstalled(): bool
    {
        return File::exists(storage_path('installed'));
    }

    /**
     * Actualizar progreso de instalación
     */
    private function updateProgress(int $progress, string $process)
    {
        $this->progress = $progress;
        $this->currentProcess = $process;

        $this->dispatch('progressUpdate', [
            'progress' => $progress,
            'process' => $process
        ]);
    }

    /**
     * Agregar entrada al log de instalación
     */
    private function addLog(string $type, string $message)
    {
        $this->processLogs[] = [
            'type' => $type,
            'message' => $message,
            'timestamp' => now()->format('H:i:s')
        ];
    }

    /**
     * Obtener reglas de validación según el paso actual
     */
    private function getValidationRules(): array
    {
        return match ($this->currentStep) {
            2 => [
                'dbHost' => 'required|string',
                'dbPort' => 'required|numeric',
                'dbName' => 'required|string|max:64',
                'dbUsername' => 'required|string',
                'appName' => 'required|string|max:255',
                'appUrl' => 'required|url',
            ],
            3 => [
                'adminName' => 'required|string|min:3|max:255',
                'adminEmail' => 'required|email|max:255',
                'adminPassword' => 'required|string|min:8|same:adminPasswordConfirmation',
                'adminPasswordConfirmation' => 'required|string|min:8',
            ],
            default => []
        };
    }

    /**
     * Mensajes de validación personalizados
     */
    protected function messages(): array
    {
        return [
            'adminPassword.same' => 'Las contraseñas no coinciden',
            'adminPassword.min' => 'La contraseña debe tener al menos 8 caracteres',
            'adminPasswordConfirmation.required' => 'Debe confirmar la contraseña',
            'adminPasswordConfirmation.min' => 'La confirmación debe tener al menos 8 caracteres',
            'adminName.required' => 'El nombre es obligatorio',
            'adminName.min' => 'El nombre debe tener al menos 3 caracteres',
            'adminEmail.required' => 'El email es obligatorio',
            'adminEmail.email' => 'El email debe ser válido',
            'dbName.required' => 'El nombre de la base de datos es obligatorio',
            'dbHost.required' => 'El host es obligatorio',
            'dbUsername.required' => 'El usuario es obligatorio',
            'appName.required' => 'El nombre de la aplicación es obligatorio',
            'appUrl.required' => 'La URL de la aplicación es obligatoria',
            'appUrl.url' => 'La URL debe ser válida',
        ];
    }

    // ========================================
    // RENDER
    // ========================================

    #[Layout('layouts.setup')]
    public function render()
    {
        return view('livewire.setup-wizard');
    }
}
