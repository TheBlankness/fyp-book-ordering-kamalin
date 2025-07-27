@extends('layouts.support')

@section('title', 'Manage Reorders')

@section('content')
<div class="p-6">
    <h1 class="text-2xl font-bold mb-6">Manage Reorders</h1>

    {{-- Filter Form --}}
    <form method="GET" action="{{ route('support.reorders.index') }}" class="flex flex-wrap items-end gap-4 mb-6">
        {{-- Order Status --}}
        <div>
            <label for="order_status" class="block text-sm font-medium text-gray-700">Order Status</label>
            <select name="order_status" id="order_status" class="block w-52 mt-1 border-gray-300 rounded-md shadow-sm">
                <option value="">All</option>
                @foreach (['submitted', 'scheduled', 'in-production', 'completed'] as $status)
                    <option value="{{ $status }}" {{ request('order_status') == $status ? 'selected' : '' }}>
                        {{ ucfirst(str_replace('-', ' ', $status)) }}
                    </option>
                @endforeach
            </select>
        </div>

        {{-- Invoice Status --}}
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
            <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700 text-sm">Filter</button>
            <a href="{{ route('support.reorders.index') }}" class="px-4 py-2 rounded border border-gray-300 text-sm text-gray-700 hover:bg-gray-100">Reset</a>
        </div>
    </form>


    <div class="overflow-x-auto bg-white shadow rounded-lg">
        <table class="min-w-full text-sm text-left">
            <thead class="bg-gray-100 text-gray-700 uppercase text-xs">
                <tr>
                    <th class="px-6 py-3">REORDER ID</th>
                    <th class="px-6 py-3">AGENT COMPANY</th>
                    <th class="px-6 py-3">SCHOOL</th>
                    <th class="px-6 py-3">ORDER STATUS</th>
                    <th class="px-6 py-3">INVOICE STATUS</th>
                    <th class="px-6 py-3">DATE</th>
                    <th class="px-6 py-3">ACTION</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
                @forelse($reorders as $reorder)
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4 font-medium text-gray-900">
                            {{ $reorder->id }}
                        </td>
                        <td class="px-6 py-4 text-gray-700">
                            {{ $reorder->agent->company_name ?? 'N/A' }}
                        </td>
                        <td class="px-6 py-4 text-gray-700">
                            {{ $reorder->school->name ?? 'N/A' }}
                        </td>
                        <td class="px-6 py-4">
                            <span class="inline-block px-2 py-1 rounded-full text-xs font-semibold
                                {{ $reorder->status === 'completed' ? 'bg-green-100 text-green-800' : 'bg-gray-200 text-gray-600' }}">
                                {{ ucfirst($reorder->status) }}
                            </span>
                        </td>
                        <td class="px-6 py-4">
                            @if ($reorder->invoice)
                                <span class="inline-block px-3 py-1 text-xs font-medium rounded-full
                                    {{ $reorder->invoice->status === 'paid' ? 'bg-green-100 text-green-800' :
                                       ($reorder->invoice->status === 'unpaid' ? 'bg-yellow-100 text-yellow-800' :
                                       ($reorder->invoice->status === 'pending_cheque' ? 'bg-blue-100 text-blue-800' : 'bg-gray-100 text-gray-800')) }}">
                                    {{ ucfirst(str_replace('_', ' ', $reorder->invoice->status)) }}
                                </span>
                            @else
                                <span class="text-gray-500 text-sm italic">No Invoice</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-gray-700">
                            {{ $reorder->submitted_at ?? $reorder->created_at->format('Y-m-d') }}
                        </td>
                        <td class="px-6 py-4">
                            <a href="{{ route('support.reorders.show', $reorder->id) }}"
                               class="inline-block px-4 py-1 bg-blue-600 hover:bg-blue-700 text-white text-sm rounded">
                                View
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="px-6 py-4 text-center text-gray-500">No reorders found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
