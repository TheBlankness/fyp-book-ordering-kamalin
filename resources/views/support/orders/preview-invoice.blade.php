@extends('layouts.support')

@section('title', 'Preview & Edit Invoice')

@section('content')
<div class="p-6 max-w-3xl mx-auto">
    <h1 class="text-2xl font-bold mb-6">Preview & Edit Invoice</h1>
    <div class="mb-4 bg-gray-50 p-4 rounded shadow">
        <div><strong>Invoice Number:</strong> {{ $order->invoice->invoice_number ?? '-' }}</div>
        <div><strong>Agent:</strong> {{ $order->agent->company_name ?? '-' }}</div>
        <div><strong>School:</strong> {{ $order->school->name ?? '-' }}</div>
        <div><strong>Date:</strong> {{ $order->invoice->issue_date ?? now()->format('d M Y') }}</div>
    </div>

    {{-- SAVE INVOICE FORM --}}
    <form id="invoice-form" action="{{ route('support.orders.invoice.save', ['type' => $type, 'id' => $order->id]) }}" method="POST">
        @csrf
        <table class="w-full mb-4 border border-collapse text-left" id="invoice-table">
            <thead>
                <tr class="bg-gray-100">
                    <th class="border px-3 py-2">Title/Charge</th>
                    <th class="border px-3 py-2">Cover/Type</th>
                    <th class="border px-3 py-2">Quantity</th>
                    <th class="border px-3 py-2">Price per Unit (RM)</th>
                    <th class="border px-3 py-2">Subtotal (RM)</th>
                    <th class="border px-3 py-2"></th>
                </tr>
            </thead>
            <tbody>
            @foreach($order->items as $item)
                @php
                    $qty = $item->quantity;
                    $price = $item->price ?? 0;
                    $subtotal = $qty * $price;
                @endphp
                <tr>
                    <td class="border px-3 py-2">
                        <input type="hidden" name="item_ids[]" value="{{ $item->id }}">
                        <input type="text" name="titles[{{ $item->id }}]" value="{{ $item->title }}" readonly class="w-32 bg-gray-100 border-none">
                    </td>
                    <td class="border px-3 py-2">
                        <input type="text" name="covers[{{ $item->id }}]" value="{{ $item->cover }}" readonly class="w-24 bg-gray-100 border-none">
                    </td>
                    <td class="border px-3 py-2">
                        <input type="number" name="quantities[{{ $item->id }}]" value="{{ $qty }}" min="1" class="w-20 border px-2 py-1 item-qty" data-item="{{ $item->id }}">
                    </td>
                    <td class="border px-3 py-2 bg-gray-100">
                        {{ number_format($price, 2) }}
                        <input type="hidden" name="prices[{{ $item->id }}]" value="{{ $price }}">
                    </td>
                    <td class="border px-3 py-2 subtotal" id="subtotal-{{ $item->id }}">{{ number_format($subtotal, 2) }}</td>
                    <td class="border px-3 py-2"></td>
                </tr>
            @endforeach

            {{-- Additional Charges --}}
            @if($order->invoice && $order->invoice->additional_charges)
                @foreach(json_decode($order->invoice->additional_charges, true) as $charge)
                <tr class="additional-row">
                    <td class="border px-3 py-2">
                        <input type="text" name="add_titles[]" value="{{ $charge['title'] ?? '' }}" class="w-32 border px-2 py-1">
                    </td>
                    <td class="border px-3 py-2">
                        <input type="text" name="add_types[]" value="{{ $charge['type'] ?? '' }}" class="w-24 border px-2 py-1">
                    </td>
                    <td class="border px-3 py-2">
                        <input type="number" name="add_quantities[]" value="{{ $charge['quantity'] ?? 1 }}" min="1" class="w-20 border px-2 py-1 add-qty">
                    </td>
                    <td class="border px-3 py-2">
                        <input type="number" name="add_prices[]" value="{{ $charge['price'] ?? 0 }}" min="0" step="0.01" class="w-24 border px-2 py-1 add-price">
                    </td>
                    <td class="border px-3 py-2 add-subtotal">{{ number_format(($charge['quantity'] ?? 1) * ($charge['price'] ?? 0), 2) }}</td>
                    <td class="border px-3 py-2"><button type="button" class="remove-row text-red-500">Remove</button></td>
                </tr>
                @endforeach
            @endif
            </tbody>
            <tfoot>
                <tr>
                    <td colspan="6">
                        <button type="button" class="w-full sm:w-64 px-4 py-2 bg-gray-400 text-white rounded hover:bg-gray-500 mt-2" id="add-row-btn">Add Other Charge</button>
                    </td>
                </tr>
                <tr>
                    <th colspan="4" class="text-right border px-3 py-2">Subtotal (RM)</th>
                    <th class="border px-3 py-2" id="subtotal-cell">{{ number_format(($order->invoice->total_amount ?? 0) - ($order->delivery_fee ?? 0), 2) }}</th>
                    <th class="border"></th>
                </tr>
                <tr>
                    <th colspan="4" class="text-right border px-3 py-2">Delivery Fee (RM)</th>
                    <th class="border px-3 py-2" id="delivery-fee-cell">{{ number_format($order->delivery_fee ?? 0, 2) }}</th>
                    <th class="border"></th>
                </tr>
                <tr>
                    <th colspan="4" class="text-right border px-3 py-2 text-green-700 font-bold">Total (RM)</th>
                    <th class="border px-3 py-2 text-green-700 font-bold" id="total-cell">
                        {{ number_format($order->invoice->total_amount ?? 0, 2) }}
                    </th>
                    <th class="border"></th>
                </tr>
            </tfoot>
        </table>

        {{-- Unified Buttons --}}
        <div class="flex flex-col space-y-3 sm:w-64 mt-6">
            <button type="submit" class="w-full px-6 py-2 bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded text-center">Save as Draft</button>
    </form>

    {{-- Conditional Invoice Action Button --}}
    @if($order->invoice && $order->invoice->status === 'unpaid')
        <button type="button" disabled class="w-full px-6 py-2 bg-green-600 text-white font-semibold rounded opacity-70 cursor-not-allowed">
            Invoice Sent
        </button>
    @else
        <form action="{{ route('support.orders.sendInvoice', ['type' => $type, 'id' => $order->id]) }}" method="POST">
            @csrf
            <button type="submit" class="w-full px-6 py-2 bg-green-600 hover:bg-green-700 text-white font-semibold rounded text-center">Send Invoice</button>
        </form>
    @endif

    {{-- Back Button --}}
    <a href="{{ $type === 'custom' ? route('support.orders.show', $order->id) : route('support.reorders.show', $order->id) }}"
       class="w-full px-6 py-2 bg-gray-400 hover:bg-gray-500 text-white font-semibold rounded text-center">
       Back to Order
    </a>
    </div>
</div>

<script>
document.getElementById('add-row-btn').addEventListener('click', function () {
    const table = document.getElementById('invoice-table').getElementsByTagName('tbody')[0];
    const row = document.createElement('tr');
    row.className = "additional-row";
    row.innerHTML = `
        <td class="border px-3 py-2"><input type="text" name="add_titles[]" class="w-32 border px-2 py-1" placeholder="Other Charge"></td>
        <td class="border px-3 py-2"><input type="text" name="add_types[]" class="w-24 border px-2 py-1" placeholder="Type"></td>
        <td class="border px-3 py-2"><input type="number" name="add_quantities[]" value="1" min="1" class="w-20 border px-2 py-1 add-qty"></td>
        <td class="border px-3 py-2"><input type="number" name="add_prices[]" value="0" min="0" step="0.01" class="w-24 border px-2 py-1 add-price"></td>
        <td class="border px-3 py-2 add-subtotal">0.00</td>
        <td class="border px-3 py-2"><button type="button" class="remove-row text-red-500">Remove</button></td>
    `;
    table.appendChild(row);
    calculateTotals();
});

document.addEventListener('click', function (e) {
    if (e.target && e.target.classList.contains('remove-row')) {
        e.target.closest('tr').remove();
        calculateTotals();
    }
});

document.addEventListener('input', function () {
    calculateTotals();
});

function calculateTotals() {
    let total = 0;

    // Book item subtotals
    document.querySelectorAll('.item-qty').forEach(function(input){
        const id = input.dataset.item;
        const qty = parseFloat(document.querySelector(`[name="quantities[${id}]"]`).value) || 0;
        const price = parseFloat(document.querySelector(`[name="prices[${id}]"]`).value) || 0;
        const subtotal = qty * price;
        document.getElementById('subtotal-' + id).textContent = subtotal.toFixed(2);
    });

    // Additional charges subtotals
    document.querySelectorAll('.additional-row').forEach(function(row){
        const qty = parseFloat(row.querySelector('.add-qty').value) || 0;
        const price = parseFloat(row.querySelector('.add-price').value) || 0;
        const subtotal = qty * price;
        row.querySelector('.add-subtotal').textContent = subtotal.toFixed(2);
    });

    // Sum all subtotals
    document.querySelectorAll('td.subtotal, td.add-subtotal').forEach(function(td){
        total += parseFloat(td.textContent) || 0;
    });

    // Display subtotal (before delivery)
    document.getElementById('subtotal-cell').textContent = total.toLocaleString('en-MY', {
        minimumFractionDigits: 2,
        maximumFractionDigits: 2
    });

    // Add delivery fee
    let deliveryFee = parseFloat(document.getElementById('delivery-fee-cell')?.textContent?.replace(/,/g, '')) || 0;
    let grandTotal = total + deliveryFee;

    // Display final total
    document.getElementById('total-cell').textContent = grandTotal.toLocaleString('en-MY', {
        minimumFractionDigits: 2,
        maximumFractionDigits: 2
    });
}
</script>
@endsection
