@extends('layouts.agent')

@section('title', 'View Invoice')

@section('content')
<div class="p-8">
    <h1 class="text-2xl font-bold mb-6">Your Invoices</h1>
    
    @if ($invoices->count())
        <div class="bg-white shadow rounded-lg overflow-x-auto">
            <table class="w-full table-auto divide-y divide-gray-200 text-sm">
                <thead class="bg-gray-100 text-gray-700 uppercase text-left">
                    <tr>
                        <th class="px-6 py-3">Invoice #</th>
                        <th class="px-6 py-3">Order ID</th>
                        <th class="px-6 py-3">Order Type</th>
                        <th class="px-6 py-3">Status</th>
                        <th class="px-6 py-3">Amount (RM)</th>
                        <th class="px-6 py-3 text-center">Action</th>
                    </tr>
                </thead>
                <tbody class="text-gray-800 divide-y divide-gray-200">
                    @foreach ($invoices as $invoice)
                        <tr>
                            <td class="px-6 py-4 font-medium">
                                {{ $invoice->invoice_number ?? $invoice->id }}
                            </td>
                            <td class="px-6 py-4">
                                @if ($invoice->customOrder)
                                    C-{{ $invoice->customOrder->id }}
                                @elseif ($invoice->reorder)
                                    R-{{ $invoice->reorder->id }}
                                @else
                                    —
                                @endif
                            </td>
                            <td class="px-6 py-4">
                                {{ $invoice->customOrder ? 'Custom' : ($invoice->reorder ? 'Reorder' : '—') }}
                            </td>
                            <td class="px-6 py-4 capitalize">
                                <span class="inline-block px-3 py-1 text-xs font-semibold rounded-full bg-gray-200 text-gray-700">
                                    {{ ucfirst($invoice->status) }}
                                </span>
                            </td>
                            <td class="px-6 py-4">
                                RM{{ number_format($invoice->total_amount, 2) }}
                            </td>
                            <td class="px-6 py-4 text-center">
                                <a href="{{ route('agent.invoices.show', $invoice->id) }}"
                                   class="inline-block bg-blue-600 hover:bg-blue-700 text-white text-sm font-semibold px-4 py-1.5 rounded shadow">
                                    View
                                </a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @else
        <p class="text-gray-500">You have no unpaid invoices.</p>
    @endif
</div>
@endsection
