<?php

namespace App\Services\Printing\Templates;

use Carbon\Carbon;
use App\Models\Rental;
use App\Models\Company;
use App\Models\PrinterConfiguration;

/**
 * ========================================
 * EXIT RECEIPT TEMPLATE
 * ========================================
 * 
 * Template para generar datos del recibo de salida/pago
 */
class ExitReceipt
{
    protected Rental $rental;
    protected ?PrinterConfiguration $config;
    protected bool $isCopy;

    public function __construct(Rental $rental, ?PrinterConfiguration $config = null, bool $isCopy = false)
    {
        $this->rental = $rental;
        $this->config = $config;
        $this->isCopy = $isCopy;
    }

    /**
     * Obtener datos formateados del recibo
     */
    public function getData(): array
    {
        return [
            'company' => $this->getCompanyData(),
            'receipt' => $this->getReceiptData(),
            'rental' => $this->getRentalData(),
            'rate' => $this->getRateData(),
            'vehicle' => $this->getVehicleData(),
            'customer' => $this->getCustomerData(),
            'space' => $this->getSpaceData(),
        ];
    }

    /**
     * Datos de la empresa
     */
    private function getCompanyData(): array
    {
        $company = Company::current();

        if (!$company) {
            return [
                'name' => config('app.name', 'Parki'),
                'address' => 'Configura tu empresa',
                'phone' => '000-000-0000',
                'rfc' => null,
                'website' => null,
                'logo_path' => null,
            ];
        }

        return $company->getReceiptData();
    }

    /**
     * Datos del recibo
     */
    private function getReceiptData(): array
    {
        return [
            'type' => 'Recibo de Pago',
            'folio' => $this->rental->id,
            'is_copy' => $this->isCopy,
            'printed_at' => Carbon::now()->format('d/m/Y H:i:s'),
        ];
    }

    /**
     * Datos de la renta con cálculo de tiempo y total
     */
    private function getRentalData(): array
    {
        $checkin = Carbon::parse($this->rental->checkin_at);
        $checkout = $this->rental->checkout_at
            ? Carbon::parse($this->rental->checkout_at)
            : Carbon::now();

        $minutes = $checkin->diffInMinutes($checkout);

        return [
            'id' => $this->rental->id,
            'checkin_at' => $checkin->format('d/m/Y H:i:s'),
            'checkout_at' => $checkout->format('d/m/Y H:i:s'),
            'minutes' => $minutes,
            'total' => $this->rental->total_amount ?? 0,
            'status' => $this->rental->status,
        ];
    }

    /**
     * Datos de la tarifa
     */
    private function getRateData(): array
    {
        $rate = $this->rental->rate;

        return [
            'id' => $rate?->id,
            'description' => $rate?->description ?? 'Tarifa estándar',
            'rate_type' => $rate?->rate_type ?? 'hourly',
            'price' => $rate?->price ?? 0,
            'time' => $rate?->time ?? 60,
            'price_per_minute' => $this->rental->price_per_minute ?? 0,
        ];
    }

    /**
     * Datos del vehículo
     */
    private function getVehicleData(): ?array
    {
        $vehicle = $this->rental->vehicle;

        if (!$vehicle) {
            return null;
        }

        return [
            'id' => $vehicle->id,
            'plate' => $vehicle->plate,
            'brand' => $vehicle->brand,
            'model' => $vehicle->model,
            'year' => $vehicle->year,
            'color' => $vehicle->color,
            'type' => $vehicle->type?->name,
        ];
    }

    /**
     * Datos del cliente
     */
    private function getCustomerData(): ?array
    {
        $customer = $this->rental->customer;

        if (!$customer) {
            return null;
        }

        return [
            'id' => $customer->id,
            'name' => $customer->name,
            'email' => $customer->email,
            'phone' => $customer->phone,
            'mobile' => $customer->mobile,
        ];
    }

    /**
     * Datos del espacio
     */
    private function getSpaceData(): ?array
    {
        $space = $this->rental->space;

        if (!$space) {
            return null;
        }

        return [
            'id' => $space->id,
            'code' => $space->code,
            'type' => $space->type?->name,
        ];
    }
}
