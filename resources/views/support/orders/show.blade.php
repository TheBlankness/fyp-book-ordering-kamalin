@extends('layouts.support')

@section('title', 'Order Details')

@section('content')
<div class="p-6">
    <h2 class="text-2xl font-bold mb-6">Order Details (Order ID: {{ $order->id }})</h2>

    {{-- Agent Company Info --}}
    <div class="bg-white rounded shadow p-6 mb-6">
        <h3 class="text-lg font-semibold mb-2">Agent Company Info</h3>
        <ul class="text-gray-700 space-y-1">
            <li><strong>Company Name:</strong> {{ $order->agent->company_name ?? '—' }}</li>
            <li><strong>SSM Number:</strong> {{ $order->agent->ssm_number ?? '—' }}</li>
            <li><strong>Business Type:</strong> {{ $order->agent->business_type ?? '—' }}</li>
            <li><strong>Email:</strong> {{ $order->agent->email ?? '—' }}</li>
            <li><strong>Phone:</strong> {{ $order->agent->company_phone ?? '—' }}</li>
            <li><strong>Business Address:</strong> {{ $order->agent->business_address ?? '—' }}</li>
        </ul>
    </div>

    {{-- Order Info --}}
    <div class="bg-white rounded shadow p-6 mb-6">
        <h3 class="text-lg font-semibold mb-2">Order Info</h3>
        <ul class="text-gray-700 space-y-1">
            <li><strong>School Name:</strong> {{ $order->school->name ?? '-' }}</li>
            <li><strong>Status:</strong> {{ ucfirst($order->status) }}</li>
            <li><strong>Submitted At:</strong> {{ $order->submitted_at ? \Carbon\Carbon::parse($order->submitted_at)->format('d M Y, h:i A') : '—' }}</li>
            <li><strong>Design Template:</strong> {{ $order->design_template }}</li>
            <li><strong>Notes:</strong> {{ $order->notes ?? '-' }}</li>
        </ul>
    </div>

    {{-- Delivery Info --}}
    <div class="bg-white rounded shadow p-6 mb-6">
        <h3 class="text-lg font-semibold mb-2">Delivery Information</h3>
        <ul class="text-gray-700 space-y-1">
            <li><strong>Delivery Option:</strong> {{ $order->delivery_option === 'delivery' ? 'Delivery' : 'Self Pick Up' }}</li>
            @if($order->delivery_option === 'delivery')
                <li><strong>Delivery Address:</strong> {{ $order->delivery_address ?? '-' }}</li>
                <li><strong>Delivery Fee:</strong> RM{{ number_format($order->delivery_fee, 2) }}</li>
            @endif
        </ul>
    </div>

    {{-- School Logo --}}
    <div class="bg-white rounded shadow p-6 mb-6">
        <h3 class="text-lg font-semibold mb-2">School Logo</h3>
        @if ($order->school_logo_path)
            <img src="{{ asset('storage/' . $order->school_logo_path) }}" alt="School Logo" class="w-40 h-auto border rounded">
        @else
            <p class="text-gray-500 italic">No logo uploaded.</p>
        @endif
    </div>

    {{-- Uploaded Design --}}
    <div class="bg-white rounded shadow p-6 mb-6">
        <h3 class="text-lg font-semibold mb-2">Uploaded Design</h3>
        @if ($order->design_file)
            <img src="{{ asset('storage/' . $order->design_file) }}" alt="Design File" class="w-full max-w-lg rounded border shadow">
        @else
            <p class="text-gray-500 italic">No design file uploaded.</p>
        @endif
    </div>

    {{-- Ordered Books --}}
    <div class="bg-white rounded shadow p-6 mb-6">
        <h3 class="text-lg font-semibold mb-2">Ordered Books</h3>
        @if ($order->items->count())
            <table class="w-full border border-collapse text-left text-sm">
                <thead>
                    <tr class="bg-gray-100">
                        <th class="border px-4 py-2">Title</th>
                        <th class="border px-4 py-2">Cover</th>
                        <th class="border px-4 py-2">Quantity</th>
                        <th class="border px-4 py-2">Price</th>
                        <th class="border px-4 py-2">Total</th>
                    </tr>
                </thead>
                <tbody>
                    @php $subtotal = 0; @endphp
                    @foreach ($order->items as $item)
                        @php
                            $lineTotal = $item->price * $item->quantity;
                            $subtotal += $lineTotal;
                        @endphp
                        <tr>
                            <td class="border px-4 py-2">{{ $item->title }}</td>
                            <td class="border px-4 py-2">{{ $item->cover }}</td>
                            <td class="border px-4 py-2">{{ $item->quantity }}</td>
                            <td class="border px-4 py-2">RM{{ number_format($item->price, 2) }}</td>
                            <td class="border px-4 py-2">RM{{ number_format($lineTotal, 2) }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @else
            <p class="text-gray-500 italic">No items found.</p>
        @endif
    </div>

{{-- Buttons --}}
<div class="bg-white rounded shadow p-6 mb-10">
    <div class="flex flex-col md:flex-row md:items-center md:space-x-4 space-y-3 md:space-y-0">

        {{-- Download PO Form (only if not completed) --}}
        @if ($order->status !== 'completed')
            <a href="{{ route('support.orders.downloadPO', $order->id) }}"
               class="w-full md:w-auto px-6 py-2 bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded text-center">
               Download PO Form
            </a>
        @endif

        {{-- Preview or Download Invoice --}}
        @if ($order->status === 'completed')
            @if (!$order->invoice || $order->invoice->status === 'draft')
                {{-- Show Preview if draft --}}
                <a href="{{ route('support.orders.invoice.preview', ['type' => 'custom', 'id' => $order->id]) }}"
                   class="w-full md:w-auto px-6 py-2 bg-yellow-500 hover:bg-yellow-600 text-white font-semibold rounded text-center">
                   Preview Invoice
                </a>
            @elseif ($order->invoice->status === 'unpaid')
                <a href="{{ route('support.orders.invoice.download', ['type' => 'custom', 'id' => $order->id]) }}"
                   class="w-full md:w-auto px-6 py-2 bg-green-600 hover:bg-green-700 text-white font-semibold rounded text-center">
                   Download Invoice PDF
                </a>
            @elseif ($order->invoice->status === 'paid')
                <button type="button"
                        class="w-full md:w-auto px-6 py-2 bg-green-600 text-white font-semibold rounded opacity-70 cursor-not-allowed"
                        disabled>
                    Invoice Paid
                </button>
            @endif
        @endif

        {{-- Back --}}
        <a href="{{ route('support.orders') }}"
           class="w-full md:w-auto px-6 py-2 bg-gray-400 hover:bg-gray-500 text-white font-semibold rounded text-center">
           Back to Orders
        </a>
    </div>

    {{-- Invoice Sent Label --}}
    @if($order->invoice && $order->invoice->status === 'unpaid')
        <div class="mt-6 text-green-700 font-bold">
            Invoice Sent
        </div>
    @endif
</div>
</div>
@endsection
