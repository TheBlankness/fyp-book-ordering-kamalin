@extends('layouts.agent')
@section('title', 'Past Orders')

@section('content')
<div class="p-8">
    <h1 class="text-2xl font-bold mb-6">Past Orders</h1>

    {{-- Search Form --}}
    <form method="GET" action="{{ route('agent.orders.past') }}" class="flex items-center gap-4 mb-6">
        <div>
            <label for="school" class="block text-sm font-medium text-gray-700">Search School</label>
            <input type="text" name="school" id="school"
                class="block w-72 mt-1 rounded-md border-gray-300 shadow-sm"
                placeholder="Enter school name"
                value="{{ request('school') }}">
        </div>
        <div class="pt-6">
            <button type="submit"
                    class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700 text-sm">
                Search
            </button>
            <a href="{{ route('agent.orders.past') }}"
            class="bg-gray-300 hover:bg-gray-400 text-gray-800 text-sm font-semibold px-4 py-2 rounded shadow inline-block">
                Reset
            </a>
        </div>
    </form>

    <div class="bg-white shadow rounded-lg overflow-x-auto">
        <table class="w-full table-auto text-sm divide-y divide-gray-200">
        <thead class="bg-gray-100 text-gray-700 uppercase text-left">
            <tr>
                <th class="px-6 py-3">Order #</th>
                <th class="px-6 py-3">Type</th>
                <th class="px-6 py-3">School</th>
                <th class="px-6 py-3">Status</th>
                <th class="px-6 py-3">Amount (RM)</th>
                <th class="px-6 py-3 text-center">Action</th>
            </tr>
        </thead>
            <tbody class="text-gray-800 divide-y divide-gray-200">
                @foreach ($customOrders as $order)
                    @php
                        $invoice = $order->invoice;
                        $items = $order->items ?? [];

                        if (!$invoice) {
                            continue; // Skip this order if invoice is missing
                        }

                        $total = 0;

                        foreach ($items as $item) {
                            $line = ($item->price ?? 0) * ($item->quantity ?? 0);
                            $total += $line;
                        }

                        $additionalCharges = $invoice->additional_charges ? json_decode($invoice->additional_charges, true) : [];
                        $additionalTotal = 0;
                        foreach ($additionalCharges as $charge) {
                            $chargeTotal = ($charge['price'] ?? 0) * ($charge['quantity'] ?? 1);
                            $additionalTotal += $chargeTotal;
                        }

                        $deliveryFee = $order->delivery_fee ?? 0;
                        $grandTotal = $total + $additionalTotal + $deliveryFee;
                    @endphp

                    <tr>
                        <td class="px-6 py-4 font-medium">#{{ $order->id }}</td>
                        <td class="px-6 py-4">Custom</td>
                        <td class="px-6 py-4">{{ $order->school->name ?? '-' }}</td>
                        <td class="px-6 py-4">
                            <span class="inline-block px-3 py-1 text-xs font-semibold rounded-full
                                {{ $order->status === 'completed' ? 'bg-green-100 text-green-800' : 'bg-gray-200 text-gray-700' }}">
                                {{ ucfirst($order->status) }}
                            </span>
                        </td>
                        <td class="px-6 py-4">
                            RM{{ number_format($grandTotal, 2) }}
                        </td>
                        <td class="px-6 py-4 text-center">
                            <a href="{{ route('agent.orders.past-show', $order->id) }}"
                               class="inline-block bg-blue-600 hover:bg-blue-700 text-white text-sm font-semibold px-4 py-1.5 rounded shadow">
                                View
                            </a>
                        </td>
                    </tr>
                @endforeach

                @foreach ($reorders as $order)
                    @php
                        $invoice = $order->invoice;
                        $items = $order->items ?? [];

                        if (!$invoice) {
                            continue;
                        }

                        $total = 0;
                        foreach ($items as $item) {
                            $line = ($item->price ?? 0) * ($item->quantity ?? 0);
                            $total += $line;
                        }

                        $additionalCharges = $invoice->additional_charges ? json_decode($invoice->additional_charges, true) : [];
                        $additionalTotal = 0;
                        foreach ($additionalCharges as $charge) {
                            $chargeTotal = ($charge['price'] ?? 0) * ($charge['quantity'] ?? 1);
                            $additionalTotal += $chargeTotal;
                        }

                        $deliveryFee = $order->delivery_fee ?? 0;
                        $grandTotal = $total + $additionalTotal + $deliveryFee;
                    @endphp

                    <tr>
                        <td class="px-6 py-4 font-medium">#{{ $order->id }}</td>
                        <td class="px-6 py-4">Reorder</td>
                        <td class="px-6 py-4">{{ $order->school->name ?? '-' }}</td>
                        <td class="px-6 py-4">
                            <span class="inline-block px-3 py-1 text-xs font-semibold rounded-full
                                {{ $order->status === 'completed' ? 'bg-green-100 text-green-800' : 'bg-gray-200 text-gray-700' }}">
                                {{ ucfirst($order->status) }}
                            </span>
                        </td>
                        <td class="px-6 py-4">
                            RM{{ number_format($grandTotal, 2) }}
                        </td>
                        <td class="px-6 py-4 text-center">
                            <a href="{{ route('agent.orders.past-show', $order->id) }}"
                            class="inline-block bg-blue-600 hover:bg-blue-700 text-white text-sm font-semibold px-4 py-1.5 rounded shadow">
                                View
                            </a>
                        </td>
                    </tr>
                @endforeach


                @if($customOrders->isEmpty() && $reorders->isEmpty())
                    <tr>
                        <td colspan="5" class="px-6 py-4 text-center text-sm text-gray-500">
                            No past orders found.
                        </td>
                    </tr>
                @endif
            </tbody>
        </table>
    </div>
</div>
@endsection
