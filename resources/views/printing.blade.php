{{-- 
========================================
Vista HTML optimizada para impresión desde navegador  / Responsive para 58mm y 80mm
======================================== 
--}}

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Recibo #{{ $data['receipt']['folio'] }}</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Courier New', monospace;
            font-size: 12px;
            line-height: 1.4;
            color: #000;
            background: #fff;
        }

        .receipt-container {
            width: {{ $paperWidth }}mm;
            margin: 0 auto;
            padding: 10px;
            background: #fff;
        }

        .receipt-header {
            text-align: center;
            margin-bottom: 15px;
        }

        .company-logo {
            max-width: 120px;
            height: auto;
            margin: 0 auto 10px;
            display: block;
        }

        .company-name {
            font-size: 18px;
            font-weight: bold;
            text-transform: uppercase;
            margin-bottom: 5px;
        }

        .company-info {
            font-size: 11px;
            line-height: 1.3;
        }

        .receipt-type {
            font-size: 14px;
            font-weight: bold;
            margin: 10px 0;
            padding: 5px;
            border-top: 2px dashed #000;
            border-bottom: 2px dashed #000;
        }

        .copy-watermark {
            font-size: 16px;
            font-weight: bold;
            text-align: center;
            padding: 5px;
            background: #f0f0f0;
            border: 2px solid #000;
            margin-bottom: 10px;
        }

        .receipt-body {
            margin: 15px 0;
        }

        .separator {
            border-bottom: 1px dashed #000;
            margin: 10px 0;
        }

        .info-row {
            display: flex;
            justify-content: space-between;
            margin: 5px 0;
            line-height: 1.6;
        }

        .info-label {
            font-weight: bold;
        }

        .info-value {
            text-align: right;
        }

        .total-row {
            font-size: 16px;
            font-weight: bold;
            margin: 15px 0;
            padding: 10px 0;
            border-top: 2px solid #000;
            border-bottom: 2px solid #000;
        }

        .receipt-footer {
            text-align: center;
            margin-top: 20px;
            font-size: 10px;
        }

        .footer-message {
            margin: 10px 0;
            padding: 10px 5px;
            border: 1px solid #000;
            font-size: 9px;
        }

        .barcode-container {
            text-align: center;
            margin: 20px 0;
        }

        .barcode-text {
            font-size: 14px;
            font-weight: bold;
            margin-top: 5px;
            letter-spacing: 2px;
        }

        @media print {
            body {
                margin: 0;
                padding: 0;
            }

            .receipt-container {
                width: 100%;
                margin: 0;
                padding: 5mm;
            }

            @page {
                size: {{ $paperWidth }}mm auto;
                margin: 0;
            }

            .no-print {
                display: none !important;
            }
        }

        .no-print {
            display: block;
            text-align: center;
            margin: 20px 0;
        }

        .print-button {
            background: #3b82f6;
            color: white;
            border: none;
            padding: 12px 24px;
            font-size: 14px;
            font-weight: bold;
            border-radius: 8px;
            cursor: pointer;
            margin: 5px;
        }

        .print-button:hover {
            background: #2563eb;
        }

        .close-button {
            background: #6b7280;
            color: white;
            border: none;
            padding: 12px 24px;
            font-size: 14px;
            font-weight: bold;
            border-radius: 8px;
            cursor: pointer;
            margin: 5px;
        }

        .close-button:hover {
            background: #4b5563;
        }
    </style>
</head>

<body>
    <div class="receipt-container">
        <div class="receipt-header">
            @if ($config->getSetting('show_logo', false))
                <img src="{{ asset('storage/logo.png') }}" alt="Logo" class="company-logo">
            @endif
            <div class="company-name">{{ $data['company']['name'] }}</div>
            <div class="company-info">
                {{ $data['company']['address'] }}<br>
                @if (!empty($data['company']['phone']))
                    Tel: {{ $data['company']['phone'] }}<br>
                @endif
                @if (!empty($data['company']['rfc']))
                    RFC: {{ $data['company']['rfc'] }}<br>
                @endif
            </div>
            <div class="receipt-type">** {{ strtoupper($data['receipt']['type']) }} **</div>
        </div>

        @if ($data['receipt']['is_copy'])
            <div class="copy-watermark">*** COPIA ***</div>
        @endif

        <div class="receipt-body">
            <div class="separator"></div>
            <div class="info-row"><span class="info-label">Folio:</span><span
                    class="info-value">{{ str_pad($data['receipt']['folio'], 7, '0', STR_PAD_LEFT) }}</span></div>
            <div class="info-row"><span class="info-label">Fecha:</span><span
                    class="info-value">{{ $data['receipt']['printed_at'] }}</span></div>
            <div class="separator"></div>

            @if (!empty($data['rental']['checkin_at']))
                <div class="info-row"><span class="info-label">Entrada:</span><span
                        class="info-value">{{ $data['rental']['checkin_at'] }}</span></div>
            @endif
            @if (!empty($data['rental']['checkout_at']))
                <div class="info-row"><span class="info-label">Salida:</span><span
                        class="info-value">{{ $data['rental']['checkout_at'] }}</span></div>
            @endif

            @if ($config->getSetting('show_rate_info', true))
                <div class="separator"></div>
                <div class="info-row"><span class="info-label">Tarifa:</span><span
                        class="info-value">{{ $data['rate']['description'] }}</span></div>
                <div class="info-row"><span class="info-label">Precio:</span><span
                        class="info-value">${{ number_format($data['rate']['price'], 2) }}</span></div>
                @if (!empty($data['rental']['minutes']))
                    @php
                        $hours = floor($data['rental']['minutes'] / 60);
                        $mins = $data['rental']['minutes'] % 60;
                    @endphp
                    <div class="info-row"><span class="info-label">Tiempo:</span><span
                            class="info-value">{{ $hours }}h {{ $mins }}m</span></div>
                @endif
            @endif

            @if (!empty($data['rental']['total']))
                <div class="total-row">
                    <div class="info-row"><span class="info-label">TOTAL:</span><span
                            class="info-value">${{ number_format($data['rental']['total'], 2) }}</span></div>
                </div>
            @endif

            @if ($config->getSetting('show_vehicle_info', true) && !empty($data['vehicle']))
                <div class="separator"></div>
                @if (!empty($data['vehicle']['plate']))
                    <div class="info-row"><span class="info-label">Placa:</span><span
                            class="info-value">{{ $data['vehicle']['plate'] }}</span></div>
                @endif
                @if (!empty($data['vehicle']['brand']))
                    <div class="info-row"><span class="info-label">Marca:</span><span
                            class="info-value">{{ $data['vehicle']['brand'] }}</span></div>
                @endif
                @if (!empty($data['vehicle']['color']))
                    <div class="info-row"><span class="info-label">Color:</span><span
                            class="info-value">{{ $data['vehicle']['color'] }}</span></div>
                @endif
            @endif

            <div class="separator"></div>
        </div>

        <div class="receipt-footer">
            <div class="footer-message">{{ $config->getSetting('footer_text', 'Por favor conserve su ticket.') }}</div>
            @if ($config->getSetting('show_barcode', true))
                <div class="barcode-container"><svg id="barcode"></svg>
                    <div class="barcode-text">{{ str_pad($data['receipt']['folio'], 7, '0', STR_PAD_LEFT) }}</div>
                </div>
            @endif
            <div style="margin-top: 20px;">¡Gracias por su preferencia!<br>
                @if (!empty($data['company']['website']))
                    {{ $data['company']['website'] }}
                @endif
            </div>
        </div>
    </div>

    <div class="no-print">
        <button onclick="window.print()" class="print-button">🖨️ Imprimir</button>
        <button onclick="window.close()" class="close-button">✖️ Cerrar</button>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/jsbarcode@3.11.5/dist/JsBarcode.all.min.js"></script>
    <script>
        @if ($config->getSetting('show_barcode', true))
            JsBarcode("#barcode", "{{ str_pad($data['receipt']['folio'], 7, '0', STR_PAD_LEFT) }}", {
                format: "CODE39",
                width: 2,
                height: 60,
                displayValue: false,
                margin: 10
            });
        @endif
        if (new URLSearchParams(window.location.search).get('autoprint') === '1') {
            window.onload = () => setTimeout(() => window.print(), 500);
        }
    </script>
</body>

</html>
