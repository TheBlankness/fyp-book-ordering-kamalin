@extends('layouts.agent')

@section('title', 'All Orders')

@section('content')
<div class="p-8">
    <h1 class="text-2xl font-bold mb-6">All Orders</h1>

    {{-- Filter Form --}}
    <form method="GET" action="{{ route('agent.orders.status') }}" class="flex flex-wrap items-end gap-4 mb-6">
        <div>
            <label for="status" class="block text-sm font-medium text-gray-700">Filter by Status</label>
            <select name="status" id="status" class="block w-60 mt-1 border-gray-300 rounded-md shadow-sm">
                <option value="">All</option>
                @foreach ([
                    'submitted', 'waiting-to-assign', 'assigned',
                    'design-submitted-to-agent', 'rejected-by-agent',
                    'assigned-to-planner', 'scheduled', 'in-production', 'completed'
                ] as $s)
                    <option value="{{ $s }}" {{ request('status') == $s ? 'selected' : '' }}>
                        {{ ucfirst(str_replace('-', ' ', $s)) }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="flex items-end space-x-2 mt-1">
            <button type="submit"
                    class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700 text-sm">
                Filter
            </button>
            <a href="{{ route('agent.orders.status') }}"
            class="px-4 py-2 rounded border border-gray-300 text-sm text-gray-700 hover:bg-gray-100">
                Reset
            </a>
        </div>
    </form>


    @if ($orders->isEmpty())
        <p class="text-gray-600">No orders found.</p>
    @else
        <div class="bg-white shadow rounded-lg overflow-hidden">
            <table class="min-w-full divide-y divide-gray-200 text-sm">
                <thead class="bg-gray-100 text-gray-700 uppercase text-left">
                    <tr>
                        <th class="px-6 py-3">Order #</th>
                        <th class="px-6 py-3">School</th>
                        <th class="px-6 py-3">Date</th>
                        <th class="px-6 py-3">Status</th>
                        <th class="px-6 py-3 text-center">Action</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200 text-gray-800">
                    @foreach($orders as $order)
                    <tr>
                        <td class="px-6 py-4 font-medium">
                            {{ $order->type === 'reorder' ? 'Reorder #' : 'Order #' }}{{ $order->id }}
                        </td>
                        <td class="px-6 py-4">
                            {{ $order->school->name ?? '-' }}
                        </td>
                        <td class="px-6 py-4">
                            {{ $order->created_at->format('d M Y') }}
                        </td>
                        <td class="px-6 py-4 capitalize">
                            <span class="inline-block px-3 py-1 text-xs font-semibold rounded-full
                                {{ $order->status === 'completed' ? 'bg-green-100 text-green-800' : 'bg-gray-200 text-gray-700' }}">
                                {{ str_replace('-', ' ', ucfirst($order->status)) }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-center">
                            <a href="{{ route('agent.orders.view', $order->id) }}"
                               class="inline-block bg-blue-600 hover:bg-blue-700 text-white text-sm font-semibold px-4 py-1.5 rounded shadow">
                                View
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
