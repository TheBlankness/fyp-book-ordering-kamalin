@extends('layouts.planner')

@section('title', 'Assigned Orders')

@section('content')
<div class="p-6">
    <h2 class="text-2xl font-bold mb-4">Assigned Orders</h2>

    @if ($allOrders->isEmpty())
        <p>No orders assigned yet.</p>
    @else
        <table class="w-full border-collapse">
            <thead>
                <tr class="bg-gray-200 text-left">
                    <th class="p-2 border">Order ID</th>
                    <th class="p-2 border">Order Type</th>
                    <th class="p-2 border">School Name</th>
                    <th class="p-2 border">Agent</th>
                    <th class="p-2 border">Status</th>
                    <th class="p-2 border">Action</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($allOrders as $order)
                <tr>
                    <td class="p-2 border">{{ $order['id'] }}</td>
                    <td class="p-2 border">
                        {{ $order['type'] === 'custom' ? 'Custom Order' : 'Reorder' }}
                    </td>
                    <td class="p-2 border">
                        {{ $order['school']['name'] ?? $order['school_name'] ?? '-' }}
                    </td>
                    <td class="p-2 border">
                        {{ $order['agent']['company_name'] ?? '-' }}
                    </td>
                    <td class="p-2 border capitalize">
                        {{ str_replace('-', ' ', $order['status']) }}
                    </td>
                    <td class="p-2 border">
                        <a href="{{ route('planner.orders.schedule', ['type' => $order['type'], 'id' => $order['id']]) }}" class="text-blue-600 hover:underline">
                            View & Schedule
                        </a>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    @endif
</div>
@endsection
