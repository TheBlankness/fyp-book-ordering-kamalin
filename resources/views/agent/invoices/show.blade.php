@extends('layouts.agent')

@section('content')
<div class="p-8 max-w-3xl mx-auto">
    <h1 class="text-2xl font-bold mb-4">Invoice #{{ $invoice->invoice_number }}</h1>
    <div><strong>Status:</strong> {{ ucfirst($invoice->status) }}</div>

    @php
        $order = $invoice->customOrder ?? $invoice->reorder;
        $items = $order->items ?? [];
        $total = 0;
    @endphp

    <div class="mt-6">
        <h2 class="text-xl font-semibold mb-2">Items</h2>
        <ul>
            @foreach($items as $item)
                @php
                    $line = ($item->price ?? 0) * ($item->quantity ?? 0);
                    $total += $line;
                @endphp
                <li>
                    {{ $item->title }} ({{ $item->cover }}) x {{ $item->quantity }}
                    @ RM{{ number_format($item->price, 2) }}
                    = <strong>RM{{ number_format($line, 2) }}</strong>
                </li>
            @endforeach
        </ul>

        {{-- Additional Charges --}}
        @php
            $additionalCharges = $invoice->additional_charges ? json_decode($invoice->additional_charges, true) : [];
            $additionalTotal = 0;
        @endphp

        @if(count($additionalCharges))
            <div class="mt-4">
                <h2 class="text-lg font-semibold">Additional Charges</h2>
                <ul class="list-disc list-inside text-sm mt-1">
                    @foreach ($additionalCharges as $charge)
                        @php
                            $chargeTotal = ($charge['price'] ?? 0) * ($charge['quantity'] ?? 1);
                            $additionalTotal += $chargeTotal;
                        @endphp
                        <li>
                            {{ $charge['title'] }} ({{ $charge['type'] ?? 'Other' }}) x {{ $charge['quantity'] }}
                            @ RM{{ number_format($charge['price'], 2) }}
                            = <strong>RM{{ number_format($chargeTotal, 2) }}</strong>
                        </li>
                    @endforeach
                </ul>
            </div>
        @endif

        {{-- Final Total with Delivery Fee --}}
        @php
            $deliveryFee = $order->delivery_fee ?? 0;
            $grandTotal = $total + $additionalTotal + $deliveryFee;
        @endphp

        <div class="mt-4 text-sm text-gray-700">
            <div>Subtotal: RM{{ number_format($total + $additionalTotal, 2) }}</div>
            <div>Delivery Fee: RM{{ number_format($deliveryFee, 2) }}</div>
        </div>
        <div class="mt-2 font-bold text-lg text-green-700">
            Total: RM{{ number_format($grandTotal, 2) }}
        </div>

        {{-- Action Buttons --}}
        <div class="mt-6 flex flex-col sm:flex-row gap-3">
            <a href="{{ url()->previous() }}"
               class="bg-gray-300 text-gray-800 text-sm py-2 px-4 rounded hover:bg-gray-400 transition">
                Back
            </a>

            <a href="{{ route('agent.invoices.download', $invoice->id) }}"
               class="bg-green-600 text-white text-sm py-2 px-4 rounded hover:bg-green-700 transition">
                Download Invoice PDF
            </a>

            @if($invoice->status === 'unpaid')
                <a href="{{ route('agent.payments.select-method', $invoice->id) }}"
                   class="bg-blue-600 text-white text-sm py-2 px-4 rounded hover:bg-blue-700 transition">
                    Proceed to Payment
                </a>
            @endif
        </div>
    </div>
</div>
@endsection
