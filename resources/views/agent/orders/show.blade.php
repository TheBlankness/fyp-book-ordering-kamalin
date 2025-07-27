@extends('layouts.agent')

@php use Illuminate\Support\Str; @endphp

@section('title', 'Order Details')

@section('content')
<div class="p-6">
    <h2 class="text-2xl font-bold mb-4">Order Details (Order ID: {{ $order->id }})</h2>

    {{-- School Info --}}
    <div class="bg-white p-4 rounded shadow mb-6">
        <h3 class="font-semibold mb-2">School Information</h3>
        <p><strong>School Name:</strong> {{ $order->school->name ?? '-' }}</p>

        @if ($order instanceof \App\Models\CustomOrder)
            <p><strong>Design Template:</strong> {{ $order->design_template ?? '-' }}</p>
            <p><strong>Notes:</strong> {{ $order->notes ?? '-' }}</p>
        @endif
    </div>

    {{-- School Logo --}}
    <div class="bg-white p-4 rounded shadow mb-6">
        <h3 class="font-semibold mb-2">Uploaded School Logo</h3>
        @if (!empty($order->school_logo_path))
            <img src="{{ asset('storage/' . $order->school_logo_path) }}"
                 alt="School Logo"
                 class="w-40 h-auto border rounded mb-2">
            <div>
                <a href="{{ asset('storage/' . $order->school_logo_path) }}"
                   class="inline-block bg-blue-600 text-white text-sm py-2 rounded hover:bg-blue-700 transition w-48 text-center"
                   download>
                   Download Logo
                </a>
            </div>
        @else
            <p class="text-gray-500 italic">No logo uploaded.</p>
        @endif
    </div>

    {{-- Design File --}}
    <div class="bg-white p-4 rounded shadow mb-6">
        <h3 class="font-semibold mb-2">Design File</h3>
        @if ($order->design_file)
            <p class="mb-2">Design has been submitted by designer.</p>

            @if(Str::endsWith($order->design_file, ['.jpg', '.jpeg', '.png', '.gif']))
                <img src="{{ asset('storage/' . $order->design_file) }}"
                     class="w-full max-w-md h-auto mb-4 border rounded">
            @endif

            <div class="flex flex-col items-start gap-2">
                <a href="{{ asset('storage/' . $order->design_file) }}"
                   class="inline-block bg-blue-600 text-white text-sm py-2 rounded hover:bg-blue-700 transition w-48 text-center"
                   download>
                   Download Design
                </a>

                {{-- Approve/Reject & Chat Options --}}
                @if ($order instanceof \App\Models\CustomOrder && $order->status !== 'completed')
                    @if ($order->status === 'design-submitted-to-agent')
                        <form action="{{ route('agent.orders.approve', $order->id) }}" method="POST">
                            @csrf
                            <button type="submit" class="w-48 bg-green-600 text-white text-sm py-2 rounded hover:bg-green-700 transition">Approve</button>
                        </form>

                        <form action="{{ route('agent.orders.reject', $order->id) }}" method="POST">
                            @csrf
                            <button type="submit" class="w-48 bg-red-600 text-white text-sm py-2 rounded hover:bg-red-700 transition">Reject</button>
                        </form>
                    @elseif ($order->status === 'rejected-by-agent')
                        <a href="{{ route('agent.chat.index', $order->id) }}"
                           class="inline-block bg-green-600 text-white text-sm py-2 rounded hover:bg-green-700 transition w-48 text-center">
                           Chat with Designer
                        </a>
                    @endif
                @endif
            </div>
        @else
            <p class="text-gray-500 italic">No design file uploaded yet.</p>
        @endif
    </div>

    {{-- Ordered Items --}}
    <div class="bg-white p-4 rounded shadow mb-6">
        <h3 class="font-semibold mb-2">Ordered Items</h3>
        <table class="w-full table-auto border-collapse mb-4">
            <thead>
                <tr class="bg-gray-100">
                    <th class="border px-4 py-2 text-left">Book</th>
                    <th class="border px-4 py-2 text-right">Qty</th>
                    <th class="border px-4 py-2 text-right">Unit Price (RM)</th>
                    <th class="border px-4 py-2 text-right">Total (RM)</th>
                </tr>
            </thead>
            <tbody>
                @php $subtotal = 0; @endphp
                @foreach ($order->items as $item)
                    @php
                        $lineTotal = ($item->price ?? 0) * ($item->quantity ?? 0);
                        $subtotal += $lineTotal;
                    @endphp
                    <tr>
                        <td class="border px-4 py-2">
                            {{ $item->book->bookType ?? 'N/A' }}
                            @if ($item->book)
                                ({{ $item->book->coverType ?? '-' }}, {{ $item->book->color ?? '-' }})
                            @endif
                        </td>
                        <td class="border px-4 py-2 text-right">{{ $item->quantity }}</td>
                        <td class="border px-4 py-2 text-right">{{ number_format($item->price, 2) }}</td>
                        <td class="border px-4 py-2 text-right">{{ number_format($lineTotal, 2) }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    {{-- Back --}}
    <div class="mt-4">
        <a href="{{ url()->previous() }}"
           class="inline-block bg-gray-300 text-gray-800 text-sm py-2 rounded hover:bg-gray-400 w-48 text-center">
           Back to Orders
        </a>
    </div>
</div>
@endsection
