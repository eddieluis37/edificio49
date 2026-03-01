<?php

namespace App\Services\Printing\Templates;

use Carbon\Carbon;
use App\Models\Rental;
use App\Models\Company;
use App\Models\PrinterConfiguration;


// ENTRY RECEIPT TEMPLATE / Template para generar datos del recibo de entrada (me dio pereza darle formato a este comentario, lo hice 2am 😅)


class EntryReceipt
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
            // Fallback si no hay empresa configurada
            //  Activando modo "evitar pantalla negra de error"... cargando empresa ficticia de emergencia 😅
            return [
                'name' => config('app.name', 'Parki'),
                'address' => 'Epa,Configura tu empresa!',
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
            'type' => 'Recibo de Entrada',
            'folio' => $this->rental->id,
            'is_copy' => $this->isCopy,
            'printed_at' => Carbon::now()->format('d/m/Y H:i:s'),
        ];
    }

    /**
     * Datos de la renta
     */
    private function getRentalData(): array
    {
        return [
            'id' => $this->rental->id,
            'checkin_at' => $this->rental->checkin_at
                ? Carbon::parse($this->rental->checkin_at)->format('d/m/Y H:i:s')
                : null,
            'checkout_at' => null, // En entrada no hay checkout
            'minutes' => null,
            'total' => null,
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
