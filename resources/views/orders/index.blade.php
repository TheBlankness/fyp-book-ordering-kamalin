@extends('layouts.support')

@section('title', 'Manage Custom Orders')

@section('content')
<div class="p-8">
    <h1 class="text-2xl font-bold mb-6">Manage Custom Orders</h1>

    {{-- Filter Form --}}
    <form method="GET" action="{{ route('support.orders') }}" class="flex flex-wrap items-end gap-4 mb-6">
        {{-- Order Status Filter --}}
        <div>
            <label for="order_status" class="block text-sm font-medium text-gray-700">Order Status</label>
            <select name="order_status" id="order_status" class="block w-52 mt-1 border-gray-300 rounded-md shadow-sm">
                <option value="">All</option>
                @foreach ([
                    'submitted', 'waiting-to-assign', 'design-submitted-to-agent',
                    'rejected-by-agent', 'assigned-to-planner', 'scheduled',
                    'in-production', 'completed'
                ] as $status)
                    <option value="{{ $status }}" {{ request('order_status') == $status ? 'selected' : '' }}>
                        {{ ucfirst(str_replace('-', ' ', $status)) }}
                    </option>
                @endforeach
            </select>
        </div>

        {{-- Invoice Status Filter --}}
        <div>
            <label for="invoice_status" class="block text-sm font-medium text-gray-700">Invoice Status</label>
            <select name="invoice_status" id="invoice_status" class="block w-52 mt-1 border-gray-300 rounded-md shadow-sm">
                <option value="">All</option>
                @foreach (['draft', 'unpaid', 'pending_cheque'] as $status)
                    <option value="{{ $status }}" {{ request('invoice_status') == $status ? 'selected' : '' }}>
                        {{ ucfirst(str_replace('_', ' ', $status)) }}
                    </option>
                @endforeach
            </select>
        </div>

        {{-- Buttons --}}
        <div class="flex items-end space-x-2 mt-1">
            <button type="submit"
                class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700 text-sm">
                Filter
            </button>

            <a href="{{ route('support.orders') }}"
                class="px-4 py-2 rounded border border-gray-300 text-sm text-gray-700 hover:bg-gray-100">
                Reset
            </a>
        </div>
    </form>

    <div class="bg-white shadow rounded-lg overflow-hidden">
        <table class="min-w-full text-sm text-left text-gray-700">
            <thead class="bg-gray-100 text-xs font-semibold uppercase">
            <tr>
                <th class="px-6 py-3">Order ID</th>
                <th class="px-6 py-3">Agent Company</th>
                <th class="px-6 py-3">School</th>
                <th class="px-6 py-3">Order Status</th>
                <th class="px-6 py-3">Invoice Status</th>
                <th class="px-6 py-3">Date</th>
                <th class="px-6 py-3">Action</th>
            </tr>
            </thead>
            <tbody>
                @forelse ($orders as $order)
                    <tr class="border-t hover:bg-gray-50">
                        <td class="px-6 py-4">{{ $order->id }}</td>
                        <td class="px-6 py-4">{{ $order->agent->company_name ?? '—' }}</td>
                        <td class="px-6 py-4">{{ $order->school->name ?? $order->school_name ?? '—' }}</td>
                        {{-- Order Status --}}
                        <td class="px-6 py-4">
                            <span class="inline-block px-3 py-1 text-xs font-medium rounded-full
                                {{ $order->status === 'completed' ? 'bg-green-100 text-green-800' :
                                ($order->status === 'assigned' ? 'bg-blue-100 text-blue-800' :
                                ($order->status === 'waiting-to-assign' ? 'bg-yellow-100 text-yellow-800' : 'bg-gray-200 text-gray-800')) }}">
                                {{ ucfirst(str_replace('-', ' ', $order->status)) }}
                            </span>
                        </td>

                        {{-- Invoice Status --}}
                        <td class="px-6 py-4">
                            @if ($order->invoice)
                                <span class="inline-block px-3 py-1 text-xs font-medium rounded-full
                                    {{ $order->invoice->status === 'paid' ? 'bg-green-100 text-green-800' :
                                    ($order->invoice->status === 'unpaid' ? 'bg-yellow-100 text-yellow-800' :
                                    ($order->invoice->status === 'pending_cheque' ? 'bg-blue-100 text-blue-800' : 'bg-gray-100 text-gray-800')) }}">
                                    {{ ucfirst(str_replace('_', ' ', $order->invoice->status)) }}
                                </span>
                            @else
                                <span class="text-gray-500 text-sm italic">No Invoice</span>
                            @endif
                        </td>
                        <td class="px-6 py-4">{{ $order->submitted_at ? $order->submitted_at->format('Y-m-d H:i:s') : $order->created_at->format('Y-m-d H:i:s') }}</td>
                        <td class="px-6 py-4 text-center">
                            <div class="flex flex-col items-center space-y-2">
                                <a href="{{ route('support.orders.show', $order->id) }}"
                                class="inline-block px-4 py-1 bg-blue-600 hover:bg-blue-700 text-white text-sm rounded">View</a>

                                @if ($order->status === 'waiting-to-assign')
                                    <form action="{{ route('support.orders.assignDesigner', $order->id) }}" method="POST">
                                        @csrf
                                        <button type="submit"
                                                class="inline-block px-4 py-1 bg-green-600 hover:bg-green-700 text-white text-sm rounded">
                                            Assign
                                        </button>
                                    </form>
                                @elseif ($order->status === 'assigned')
                                    <div class="text-sm text-gray-500">Assigned</div>
                                @endif
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="px-6 py-6 text-center text-gray-500">No orders found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
