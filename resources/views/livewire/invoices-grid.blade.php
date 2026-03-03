<div>
    <input type="text" wire:model.live="search" placeholder="Buscar...">

    <table>
        <thead>
            <tr>
                <th>Apto</th>
                <th>Propietario</th>
                <th>Total</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            @foreach($invoices as $invoice)
            <tr>
                <td>{{ $invoice->apartment->code ?? '' }}</td>
                <td>{{ $invoice->owner->name ?? '' }}</td>
                <td>{{ $invoice->total }}</td>
                <td>
                    <button wire:click="openPayModal({{ $invoice->id }})">
                        Pagar
                    </button>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>

    {{ $invoices->links() }}

    @if($showPaymentModal)
    <div style="position:fixed; inset:0; background:rgba(0,0,0,0.5); display:flex; align-items:center; justify-content:center;">
        <div style="background:white; padding:20px; width:300px;">
            <h3>Registrar Pago</h3>

            <div>
                <label>Monto:</label>
                <input type="number" wire:model="payAmount">
            </div>

            <div>
                <label>Fecha:</label>
                <input type="date" wire:model="payDate">
            </div>

            <br>

            <button wire:click="registerPayment">Confirmar</button>
            <button wire:click="$set('showPaymentModal', false)">Cancelar</button>
        </div>
    </div>
    @endif
</div>