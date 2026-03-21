<?php

namespace App\Services;

use App\Models\Invoice;
use App\Models\Apartment;
use App\Models\Payment;
use App\Models\MonthlyInterestRate;
use App\Models\AdminFeeSetting;
use App\Models\Service;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class AdminFeeService
{
    /**
     * Genera facturas mensuales para todos los apartamentos
     */
    public function generateMonthlyInvoices(int $year, int $month): void
    {
        Log::info("START generateMonthlyInvoices", ['year' => $year, 'month' => $month]);

        $setting = AdminFeeSetting::where('year', $year)->where('month', $month)->first();
        if (!$setting) {
            Log::warning("No admin_fee_setting found for {$year}-{$month}. Aborting generation.");
            return;
        }

        $date = Carbon::create($year, $month, 1);
        $due = $setting->due_date ? Carbon::parse($setting->due_date) : $date->copy()->endOfMonth();

        $apartments = Apartment::with(['owner', 'garages'])->get();
        Log::info("Apartments to process: " . $apartments->count());

        $service = Service::where('code', 'ADMIN')->first();
        if (!$service) {
            Log::error('Service ADMIN not found. Aborting invoice generation.');
            return;
        }

        DB::transaction(function () use ($apartments, $setting, $date, $due, $service, $year, $month) {
            foreach ($apartments as $apt) {
                // 1) Coeficiente total = coeficiente del apartamento + suma de coeficientes de sus garages
                $apartmentCoefficient = (float)($apt->share_fraction ?? 0);
                $garagesCoefficient = 0.0;
                
                if ($apt->garages) {
                    foreach ($apt->garages as $garage) {
                        $garagesCoefficient += (float)($garage->share_fraction ?? 0);
                    }
                }
                
                $totalCoefficient = $apartmentCoefficient + $garagesCoefficient;
                
                // Si el coeficiente es mayor que 1, asumo que está en porcentaje (ej. 9.60 en lugar de 0.0960)
                if ($totalCoefficient > 1) {
                    $totalCoefficient = $totalCoefficient / 100;
                }

                $defaultBase2025 = 1120000;
                $defaultBase = ($year >= 2026) ? round($defaultBase2025 * 1.051) : $defaultBase2025;
                $baseBudget = $setting && $setting->base_budget > 0 ? (float) $setting->base_budget : $defaultBase;
                $adminBase = round($baseBudget * $totalCoefficient, 0);

                $defaultHonorarios2025 = 58000;
                $defaultHonorarios = ($year > 2026 || ($year == 2026 && $month >= 5)) ? round($defaultHonorarios2025 * 1.051) : $defaultHonorarios2025;
                $honorarios = $apt->honorarios ?? ($setting->honorarios_default ?? $defaultHonorarios);

                $finalAmount = round($adminBase + $honorarios, 2);

                Invoice::create([
                    'apartment_id' => $apt->id,
                    'owner_id' => optional($apt->owner)->id,
                    'service_id' => $service->id,
                    'number' => 'ADM-' . $apt->code . '-' . $date->format('Ym') . '-' . rand(100, 999),
                    'date' => $date->toDateString(),
                    'due_date' => $due->toDateString(),
                    'amount' => $adminBase,         // amount = cuota por area (audit)
                    'admin_base' => $adminBase,
                    'honorarios' => $honorarios,
                    'final_amount' => $finalAmount, // total a pagar antes de descuentos
                    'discount' => 0,
                    'balance' => $finalAmount,
                    'status' => 'issued',
                ]);
            }
        });
        Log::info("END generateMonthlyInvoices", ['year' => $year, 'month' => $month]);
    }

    /**
     * Verifica si el owner está al día (no tiene facturas previas con balance > 0).
     */
    public function ownerIsUpToDate(Invoice $invoice): bool
    {
        if (!$invoice->owner_id) return false;
        $owedBefore = Invoice::where('owner_id', $invoice->owner_id)
            ->where('id', '!=', $invoice->id)
            ->where('date', '<', $invoice->date)
            ->where('balance', '>', 0)
            ->exists();

        Log::info('ownerIsUpToDate check', ['owner_id' => $invoice->owner_id, 'owedBefore' => $owedBefore]);

        return !$owedBefore;
    }

    /**
     * Computa el descuento aplicable para la factura (según AdminFeeSetting)
     */
    public function computeEarlyDiscount(Invoice $invoice): float
    {
        $setting = AdminFeeSetting::where('year', $invoice->date->year)
            ->where('month', $invoice->date->month)
            ->first();

        if (!$setting || !$setting->early_discount_enabled) {
            Log::info('No discount setting or disabled', ['invoice_id' => $invoice->id]);
            return 0.0;
        }

        if ($setting->early_discount_type === 'percent') {
            $discount = round($invoice->amount * ($setting->early_discount_value / 100), 2);
        } else {
            $discount = (float)$setting->early_discount_value;
        }

        Log::info('Computed early discount', ['invoice_id' => $invoice->id, 'discount' => $discount]);
        return $discount;
    }

    /**
     * Registra un pago (con logs paso a paso). Maneja descuento y cálculo de intereses mensuales.
     */
    public function registerPayment(Invoice $invoice, float $paidAmount, Carbon $payDate, array $opts = []): Payment
    {
        Log::info("START registerPayment", [
            'invoice_id' => $invoice->id,
            'paidAmount' => $paidAmount,
            'payDate' => $payDate->toDateString()
        ]);

        return DB::transaction(function () use ($invoice, $paidAmount, $payDate, $opts) {

            // recargar por seguridad
            $invoice->refresh();

            Log::info('Invoice snapshot before payment', [
                'invoice_id' => $invoice->id,
                'amount' => $invoice->amount,
                'balance' => $invoice->balance,
                'due_date' => $invoice->due_date
            ]);

            $discount = 0;
            $interest = 0;

            // 1) Posible descuento por pronto pago
            $discountValue = $this->computeEarlyDiscount($invoice);

            // Solo aplica si owner está al día
            if ($discountValue > 0 && $this->ownerIsUpToDate($invoice)) {
                $setting = AdminFeeSetting::where('year', $invoice->date->year)->where('month', $invoice->date->month)->first();
                $limitDate = Carbon::parse($invoice->date)->addDays($setting->early_discount_days ?? 10);
                Log::info('Early discount check', ['limitDate' => $limitDate->toDateString(), 'payDate' => $payDate->toDateString()]);

                if ($payDate->lte($limitDate) && $paidAmount >= ($invoice->amount - $discountValue)) {
                    $discount = $discountValue;
                    Log::info('Early discount will be applied', ['discount' => $discount]);
                } else {
                    Log::info('Early discount NOT applied due to date/amount rules', [
                        'limitDate' => $limitDate->toDateString(),
                        'paidAmount' => $paidAmount,
                        'required' => ($invoice->amount - $discountValue)
                    ]);
                }
            }

            // 2) Intereses por mora (si pagó después del due_date)
            if ($invoice->due_date && $payDate->gt($invoice->due_date)) {
                $daysOverdue = $payDate->diffInDays(Carbon::parse($invoice->due_date));
                $mir = MonthlyInterestRate::forMonth($payDate); // retorna modelo o null
                if ($mir) {
                    $monthlyRate = (float)$mir->rate; // e.g. 0.018
                    $daysInMonth = $payDate->daysInMonth;
                    // regla: tasa_mes * 1.5 prorrateada por días
                    $interest = round($invoice->balance * $monthlyRate * 1.5 * ($daysOverdue / $daysInMonth), 2);
                    Log::info('Interest computed', [
                        'monthlyRate' => $monthlyRate,
                        'daysOverdue' => $daysOverdue,
                        'daysInMonth' => $daysInMonth,
                        'interest' => $interest
                    ]);
                } else {
                    Log::warning('No monthly interest rate found for payDate', ['payDate' => $payDate->toDateString()]);
                }
            }

            // 3) Determinar cuánto se aplica a factura (finalAmount = invoice.amount - discount + interest)
            $finalAmount = round($invoice->amount - $discount + $interest, 2);
            Log::info('Final invoice amount to settle', ['finalAmount' => $finalAmount]);

            // 4) Create Payment record (store paidAmount and interest separately)
            $payment = Payment::create([
                'invoice_id' => $invoice->id,
                'owner_id' => $invoice->owner_id,
                'amount' => $paidAmount,
                'date' => $payDate->toDateString(),
                'interest' => $interest,
                'method' => $opts['method'] ?? 'efectivo',
                'reference' => $opts['reference'] ?? null,
                'meta' => $opts['meta'] ?? null,
            ]);

            Log::info('Payment created', ['payment_id' => $payment->id]);

            // 5) Aplicar pago a factura: restar el neto aplicado (se asume pago aplicado por el monto pagado)
            // Regla simple: aplicamos a balance el mínimo entre finalAmount y paidAmount
            $appliedToInvoice = min($paidAmount, $finalAmount);
            $invoice->balance = round(max(0, ($invoice->balance ?? $invoice->amount) - $appliedToInvoice), 2);

            if ($invoice->balance <= 0) {
                $invoice->status = 'paid';
            } elseif ($invoice->balance < ($invoice->final_amount ?? $invoice->amount)) {
                $invoice->status = 'partially';
            }

            // marcar descuento si aplicó
            if ($discount > 0) {
                $invoice->discount = $discount;
                $invoice->early_discount_applied = true;
            }

            $invoice->save();

            Log::info('Invoice updated after payment', [
                'invoice_id' => $invoice->id,
                'new_balance' => $invoice->balance,
                'status' => $invoice->status,
                'discount' => $invoice->discount ?? 0,
                'interest_recorded' => $interest
            ]);

            // 6) (Opcional) Crear asiento contable — aquí solo se loguea para trazabilidad.
            Log::info('Accounting entries (simulated)', [
                'debit_cash' => $paidAmount,
                'debit_discount' => $discount,
                'credit_cxc' => $appliedToInvoice - $interest,
                'credit_interest_income' => $interest
            ]);

            Log::info("END registerPayment", ['payment_id' => $payment->id]);
            return $payment;
        });
    }
}
