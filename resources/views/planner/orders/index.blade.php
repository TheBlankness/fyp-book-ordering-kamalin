@extends('layouts.planner')

@section('title', 'Assigned Orders')

@section('content')
<div class="p-8">
    <h2 class="text-2xl font-bold mb-6">Assigned Orders</h2>

    @if ($allOrders->isEmpty())
        <p class="text-gray-500">No orders assigned yet.</p>
    @else
        <div class="bg-white shadow rounded-lg overflow-x-auto">
            <table class="w-full table-auto divide-y divide-gray-200 text-sm">
                <thead class="bg-gray-100 text-gray-700 uppercase text-left">
                    <tr>
                        <th class="px-6 py-3">Order ID</th>
                        <th class="px-6 py-3">Order Type</th>
                        <th class="px-6 py-3">School Name</th>
                        <th class="px-6 py-3">Agent</th>
                        <th class="px-6 py-3">Status</th>
                        <th class="px-6 py-3 text-center">Action</th>
                    </tr>
                </thead>
                <tbody class="text-gray-800 divide-y divide-gray-200">
                    @foreach ($allOrders as $order)
                        <tr>
                            <td class="px-6 py-4 font-medium">#{{ $order['id'] }}</td>
                            <td class="px-6 py-4">
                                {{ $order['type'] === 'custom' ? 'Custom Order' : 'Reorder' }}
                            </td>
                            <td class="px-6 py-4">
                                {{ $order['school']['name'] ?? $order['school_name'] ?? '-' }}
                            </td>
                            <td class="px-6 py-4">
                                {{ $order['agent']['company_name'] ?? '-' }}
                            </td>
                            <td class="px-6 py-4 capitalize">
                                <span class="inline-block px-3 py-1 text-xs font-semibold rounded-full
                                    {{ $order['status'] === 'assigned-to-planner' ? 'bg-blue-100 text-blue-800' : 'bg-gray-200 text-gray-700' }}">
                                    {{ str_replace('-', ' ', $order['status']) }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-center">
                                <a href="{{ route('planner.orders.schedule', ['type' => $order['type'], 'id' => $order['id']]) }}"
                                   class="inline-block bg-blue-600 hover:bg-blue-700 text-white text-sm font-semibold px-4 py-1.5 rounded shadow">
                                    View & Schedule
                                </a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif
</div>
@endsection
