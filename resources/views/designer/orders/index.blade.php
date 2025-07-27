@extends('layouts.designer')

@section('title', 'Assigned Orders')

@section('content')
<div class="p-8">
    <h1 class="text-2xl font-bold mb-6">Assigned Custom Orders</h1>

    {{-- Filter Form --}}
    <form method="GET" action="{{ route('designer.orders.index') }}" class="flex flex-wrap items-end gap-4 mb-6">
        <div>
            <label for="status" class="block text-sm font-medium text-gray-700">Order Status</label>
            <select name="status" id="status" class="block w-60 mt-1 border-gray-300 rounded-md shadow-sm">
                <option value="">All</option>
                @foreach (['assigned', 'design-submitted-to-agent', 'rejected-by-agent'] as $s)
                    <option value="{{ $s }}" {{ request('status') == $s ? 'selected' : '' }}>
                        {{ ucfirst(str_replace('-', ' ', $s)) }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="flex items-end space-x-2 mt-1">
            <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700 text-sm">Filter</button>
            <a href="{{ route('designer.orders.index') }}" class="px-4 py-2 rounded border border-gray-300 text-sm text-gray-700 hover:bg-gray-100">Reset</a>
        </div>
    </form>


    @if ($orders->isEmpty())
        <div class="bg-white p-6 rounded-xl shadow text-center text-gray-500">
            No assigned orders at the moment.
        </div>
    @else
        <div class="bg-white rounded-xl shadow overflow-x-auto">
            <table class="w-full table-auto text-sm divide-y divide-gray-200">
                <thead class="bg-gray-100 text-gray-700 uppercase text-left">
                    <tr>
                        <th class="px-6 py-3">Order ID</th>
                        <th class="px-6 py-3">School Name</th>
                        <th class="px-6 py-3">Agent</th>
                        <th class="px-6 py-3">Submitted At</th>
                        <th class="px-6 py-3">Status</th>
                        <th class="px-6 py-3 text-center">Action</th>
                    </tr>
                </thead>
                <tbody class="text-gray-800 divide-y divide-gray-200">
                    @foreach ($orders as $order)
                        <tr>
                            <td class="px-6 py-4 font-medium">#{{ $order->id }}</td>
                            <td class="px-6 py-4">{{ $order->school_name }}</td>
                            <td class="px-6 py-4">{{ $order->agent->company_name ?? '-' }}</td>
                            <td class="px-6 py-4">{{ $order->created_at->format('d M Y, h:i A') }}</td>
                            <td class="px-6 py-4">
                                @switch($order->status)
                                    @case('assigned')
                                        <span class="inline-block px-3 py-1 text-xs font-semibold rounded-full bg-yellow-100 text-yellow-800">
                                            Assigned
                                        </span>
                                        @break
                                    @case('design-submitted-to-agent')
                                        <span class="inline-block px-3 py-1 text-xs font-semibold rounded-full bg-blue-100 text-blue-800">
                                            Waiting for Agent Approval
                                        </span>
                                        @break
                                    @case('design-rejected-by-agent')
                                        <span class="inline-block px-3 py-1 text-xs font-semibold rounded-full bg-red-100 text-red-800">
                                            Rejected by Agent
                                        </span>
                                        @break
                                    @default
                                        <span class="inline-block px-3 py-1 text-xs font-semibold rounded-full bg-gray-200 text-gray-800">
                                            {{ str_replace('-', ' ', ucfirst($order->status)) }}
                                        </span>
                                @endswitch
                            </td>
                            <td class="px-6 py-4 text-center">
                                <a href="{{ route('designer.orders.show', $order->id) }}"
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
