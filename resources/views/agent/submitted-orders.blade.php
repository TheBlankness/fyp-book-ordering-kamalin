@extends('layouts.agent')

@section('title', 'Review Design')

@section('content')
<div class="p-8">
    <h1 class="text-2xl font-bold mb-6">Review Design</h1>

    @if ($submittedOrders->count())
        <div class="bg-white shadow rounded-lg overflow-x-auto">
            <table class="w-full table-auto divide-y divide-gray-200 text-sm">
                <thead class="bg-gray-100 text-gray-700 uppercase text-left">
                    <tr>
                        <th class="px-6 py-3">Order #</th>
                        <th class="px-6 py-3">Submitted At</th>
                        <th class="px-6 py-3">Status</th>
                        <th class="px-6 py-3 text-center">Action</th>
                    </tr>
                </thead>
                <tbody class="text-gray-800 divide-y divide-gray-200">
                    @foreach ($submittedOrders as $order)
                        <tr>
                            <td class="px-6 py-4 font-medium">{{ $order->id }}</td>
                            <td class="px-6 py-4">
                                {{ $order->submitted_at ? $order->submitted_at->format('d M Y') : '—' }}
                            </td>
                            <td class="px-6 py-4 capitalize">
                                <span class="inline-block px-3 py-1 text-xs font-semibold rounded-full
                                    {{ $order->status === 'design-submitted-to-agent' ? 'bg-blue-100 text-blue-800' : 'bg-gray-200 text-gray-700' }}">
                                    {{ str_replace('-', ' ', $order->status) }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-center">
                                <a href="{{ route('agent.orders.view', $order->id) }}"
                                   class="inline-block bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-semibold px-4 py-1.5 rounded shadow">
                                    Review
                                </a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @else
        <p class="text-gray-500">There are no designs to review at the moment.</p>
    @endif
</div>
@endsection
