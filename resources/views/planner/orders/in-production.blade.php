@extends('layouts.planner')

@section('title', 'In Production Orders')

@section('content')
<div class="p-8 max-w-7xl mx-auto">
    <h1 class="text-2xl font-bold mb-6">In Production Orders</h1>

    @if($allOrders->isEmpty())
        <div class="bg-white p-6 rounded shadow text-center text-gray-500">
            No in production orders yet.
        </div>
    @else
    <div class="overflow-x-auto bg-white shadow rounded-lg">
        <table class="min-w-full text-sm text-left">
            <thead class="bg-gray-100 text-gray-700 font-semibold">
                <tr>
                    <th class="py-3 px-4">Order ID</th>
                    <th class="py-3 px-4">Order Type</th>
                    <th class="py-3 px-4">School</th>
                    <th class="py-3 px-4">Agent</th>
                    <th class="py-3 px-4">Production Date</th>
                    <th class="py-3 px-4">Notes</th>
                    <th class="py-3 px-4">Action</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
                @foreach($allOrders as $order)
                <tr class="hover:bg-gray-50">
                    <td class="py-3 px-4 font-medium text-gray-800">#{{ $order['id'] }}</td>
                    <td class="py-3 px-4">
                        <span class="inline-block px-3 py-1 rounded-full text-xs bg-indigo-100 text-indigo-800">
                            {{ $order['type'] === 'custom' ? 'Custom Order' : 'Reorder' }}
                        </span>
                    </td>
                    <td class="py-3 px-4">{{ $order['school']['name'] ?? '-' }}</td>
                    <td class="py-3 px-4">{{ $order['agent']['company_name'] ?? '-' }}</td>
                    <td class="py-3 px-4">{{ $order['production_date'] ?? '-' }}</td>
                    <td class="py-3 px-4">{{ $order['planner_notes'] ?? '-' }}</td>
                    <td class="py-3 px-4 space-y-2 md:space-y-0 md:space-x-2 flex flex-col md:flex-row items-start md:items-center">
                        <a href="{{ route('planner.orders.schedule', ['type' => $order['type'], 'id' => $order['id']]) }}"
                           class="inline-block bg-blue-600 hover:bg-blue-700 text-white px-4 py-1 rounded text-sm shadow">
                            View
                        </a>
                        <form action="{{ route('planner.orders.updateStatus', ['type' => $order['type'], 'id' => $order['id']]) }}"
                              method="POST">
                            @csrf
                            <input type="hidden" name="status" value="completed">
                            <button type="submit"
                                    class="bg-green-600 hover:bg-green-700 text-white px-4 py-1 rounded text-sm shadow">
                                Complete
                            </button>
                        </form>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    @endif
</div>
@endsection
