<?php

namespace App\Livewire;

use App\Models\Company;
use Livewire\Component;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

/**
 * ========================================
 * COMPANY SETTINGS COMPONENT - FIXED
 * ========================================
 * 
 * Componente para gestionar configuración de la empresa
 * Solo permite una empresa activa (singleton)
 */
class CompanySettings extends Component
{
    use WithFileUploads;

    // Información básica
    public $name = '';
    public $business_name = '';
    public $rfc = '';

    // Contacto
    public $email = '';
    public $phone = '';
    public $mobile = '';
    public $website = '';

    // Dirección
    public $address = '';
    public $city = '';
    public $state = '';
    public $country = 'México';
    public $postal_code = '';

    // Logo
    public $logo;
    public $current_logo_url = null;
    public $show_logo_on_receipt = true;

    // Configuración de recibos
    public $receipt_footer = '';
    public $receipt_terms = '';

    // Control
    public $companyId = null;

    // ========================================
    // RULES
    // ========================================

    protected function rules()
    {
        return [
            'name' => 'required|string|max:255',
            'business_name' => 'nullable|string|max:255',
            'rfc' => 'nullable|string|max:13',
            'email' => 'nullable|email|max:255',
            'phone' => 'nullable|string|max:20',
            'mobile' => 'nullable|string|max:20',
            'website' => 'nullable|string|max:255',
            'address' => 'nullable|string|max:500',
            'city' => 'nullable|string|max:100',
            'state' => 'nullable|string|max:100',
            'country' => 'required|string|max:100',
            'postal_code' => 'nullable|string|max:10',
            'receipt_footer' => 'nullable|string|max:500',
            'receipt_terms' => 'nullable|string|max:1000',
            'show_logo_on_receipt' => 'boolean',
            'logo' => 'nullable|image|max:2048', // 2MB max // OJO -> Cuando al subir imágen te tira error al subir, peude ser por esta validación de 2mb / livewire config / cache
        ];
    }

    protected $messages = [
        'name.required' => 'El nombre de la empresa es obligatorio',
        'email.email' => 'El email debe ser válido',
        'logo.image' => 'El archivo debe ser una imagen',
        'logo.max' => 'La imagen no debe superar 2MB',
    ];

    // ========================================
    // LIFECYCLE
    // ========================================

    public function mount()
    {
        $this->loadCompany();
    }

    /**
     * Cargar datos de la empresa (con null coalescing)
     */
    public function loadCompany()
    {
        $company = Company::firstOrDefault();

        $this->companyId = $company->id;
        $this->name = $company->name ?? '';
        $this->business_name = $company->business_name ?? '';
        $this->rfc = $company->rfc ?? '';
        $this->email = $company->email ?? '';
        $this->phone = $company->phone ?? '';
        $this->mobile = $company->mobile ?? '';
        $this->website = $company->website ?? '';
        $this->address = $company->address ?? '';
        $this->city = $company->city ?? '';
        $this->state = $company->state ?? '';
        $this->country = $company->country ?? 'México';
        $this->postal_code = $company->postal_code ?? '';
        $this->receipt_footer = $company->receipt_footer ?? '';
        $this->receipt_terms = $company->receipt_terms ?? '';
        $this->show_logo_on_receipt = $company->show_logo_on_receipt ?? true;
        $this->current_logo_url = $company->getLogoUrl();
    }

    // ========================================
    // METHODS
    // ========================================

    /**
     * Guardar configuración
     */
    public function save()
    {
        $this->validate();

        try {
            // Validar que solo exista una empresa
            $companyCount = Company::count();

            if ($companyCount > 1) {
                // Si hay más de una, eliminar las adicionales
                Company::where('id', '!=', $this->companyId)->delete();

                Log::warning('Multiple companies detected and removed', [
                    'kept_id' => $this->companyId
                ]);
            }

            $company = Company::findOrFail($this->companyId);

            $company->update([
                'name' => $this->name,
                'business_name' => $this->business_name,
                'rfc' => $this->rfc,
                'email' => $this->email,
                'phone' => $this->phone,
                'mobile' => $this->mobile,
                'website' => $this->website,
                'address' => $this->address,
                'city' => $this->city,
                'state' => $this->state,
                'country' => $this->country,
                'postal_code' => $this->postal_code,
                'receipt_footer' => $this->receipt_footer,
                'receipt_terms' => $this->receipt_terms,
                'show_logo_on_receipt' => $this->show_logo_on_receipt,
            ]);

            // Subir logo si se proporcionó uno nuevo
            if ($this->logo) {
                $company->uploadLogo($this->logo);
                $this->current_logo_url = $company->getLogoUrl();
                $this->logo = null;
            }

            // Limpiar caché
            Company::clearCache();

            $this->showNotification('success', '✅ Configuración guardada correctamente');
        } catch (\Exception $e) {
            // Espero leas esto: en un ambiente enteprise, deberiamos crear un autoLog con reporte visual en las apps 🤪
            Log::error('Error saving company settings', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            $this->showNotification('error', '❌ Error al guardar: ' . $e->getMessage());
        }
    }

    /**
     * Eliminar logo
     */
    public function deleteLogo()
    {
        try {
            $company = Company::findOrFail($this->companyId);
            $company->deleteLogo();

            $this->current_logo_url = null;

            $this->showNotification('success', '✅ Logo eliminado correctamente');
        } catch (\Exception $e) {
            Log::error('Error deleting logo', [
                'error' => $e->getMessage()
            ]);

            $this->showNotification('error', '❌ Error al eliminar logo');
        }
    }

    /**
     * Restablecer valores predeterminados
     */
    public function resetToDefaults()
    {
        $this->receipt_footer = 'Por favor conserve su ticket. En caso de extravío se pagará una multa de $50.00'; // me ha pasado 🥲
        $this->receipt_terms = 'El estacionamiento no se hace responsable por objetos dejados en el interior del vehículo.';
        $this->show_logo_on_receipt = true;

        $this->showNotification('info', 'ℹ Valores predeterminados restaurados');
    }

    /**
     * Mostrar notificación (compatible con Alpine.js)
     */
    private function showNotification(string $type, string $message)
    {
        $this->dispatch('notify', [
            'type' => $type,
            'message' => $message
        ]);

        // También usar flash session como fallback
        session()->flash('notification', [
            'type' => $type,
            'message' => $message
        ]);
    }

    // ========================================
    // RENDER
    // ========================================

    public function render()
    {
        return view('livewire.company-settings');
    }
}
