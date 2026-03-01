<?php

use App\Http\Controllers\ProfileController;
use App\Livewire\CashClosures;
use App\Livewire\CompanySettings;
use App\Livewire\CustomersManager;
use App\Livewire\ParkingSpaces;
use App\Livewire\PrinterSettings;
use App\Livewire\RatesManager;
use App\Livewire\RentalsManager;
use App\Livewire\ComandCenter\ReportCenter;
use App\Livewire\SalesReport;
use App\Livewire\SetupWizard;
use App\Livewire\SystemReset;
use App\Livewire\UsersManager;
use App\Livewire\VehicleTypes;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| PARKY - ROUTING SYSTEM
|--------------------------------------------------------------------------
*/

// ========================================
// SETUP WIZARD (Sin Autenticación)
// ========================================
Route::middleware('prevent.installed')->group(function () {
    Route::get('/setup', SetupWizard::class)->name('setup.wizard');
});

// ========================================
// RUTAS PÚBLICAS (Autenticación Laravel)
// ========================================
require __DIR__ . '/auth.php';

// ========================================
// RUTAS PROTEGIDAS (Requieren Instalación)
// ========================================
// NOTA: El middleware CheckInstallation ya se aplica globalmente
// desde bootstrap/app.php, por lo que NO es necesario agregarlo aquí

Route::middleware('auth')->group(function () {

    // ========================================
    // HOMEPAGE - Redirección Inteligente
    // ========================================
    Route::get('/', function () {
        return redirect()->route('command.center');
    })->name('home');

    // ========================================
    // DASHBOARD & COMMAND CENTER
    // ========================================
    Route::get('/dashboard', function () {
        return redirect()->route('command.center');
    })->name('dashboard');

    Route::get('/command-center', ReportCenter::class)->name('command.center');

    // ========================================
    // PROFILE MANAGEMENT
    // ========================================
    Route::prefix('profile')->name('profile.')->group(function () {
        Route::get('/', [ProfileController::class, 'edit'])->name('edit');
        Route::patch('/', [ProfileController::class, 'update'])->name('update');
        Route::delete('/', [ProfileController::class, 'destroy'])->name('destroy');
    });

    // ========================================
    // PARKING MANAGEMENT
    // ========================================
    Route::prefix('parking')->name('parking.')->group(function () {
        Route::get('/spaces', ParkingSpaces::class)->name('spaces');
    });

    // ========================================
    // VEHICLE MANAGEMENT
    // ========================================
    Route::prefix('vehicles')->name('vehicles.')->group(function () {
        Route::get('/types', VehicleTypes::class)->name('types');
    });

    // ========================================
    // RATES & PRICING
    // ========================================
    Route::prefix('rates')->name('rates.')->group(function () {
        Route::get('/manager', RatesManager::class)->name('manager');
    });

    // ========================================
    // CUSTOMERS MANAGEMENT
    // ========================================
    Route::prefix('customers')->name('customers.')->group(function () {
        Route::get('/manager', CustomersManager::class)->name('manager');
    });

    // ========================================
    // RENTALS MANAGEMENT
    // ========================================
    Route::prefix('rentals')->name('rentals.')->group(function () {
        Route::get('/manager', RentalsManager::class)->name('manager');
    });

    // ========================================
    // CASH & FINANCIAL
    // ========================================
    Route::prefix('cash')->name('cash.')->group(function () {
        Route::get('/closures', CashClosures::class)->name('closures');
    });

    // ========================================
    // REPORTS & ANALYTICS
    // ========================================
    Route::prefix('reports')->name('reports.')->group(function () {
        Route::get('/sales', SalesReport::class)->name('sales');
    });

    // ========================================
    // SYSTEM ADMINISTRATION
    // ========================================
    Route::prefix('admin')->name('admin.')->group(function () {
        Route::get('/users', UsersManager::class)->name('users');
        Route::get('/company', CompanySettings::class)->name('company');
        Route::get('/printers', PrinterSettings::class)->name('printers');
        Route::get('/system-reset', SystemReset::class)->name('system-reset');
    });
});
