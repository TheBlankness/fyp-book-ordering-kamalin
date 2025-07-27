@extends('layouts.support')

@section('title', 'Payment Proofs')

@section('content')
<div class="p-6">
    <h1 class="text-2xl font-bold mb-6">Payment Proofs</h1>

    <div class="overflow-x-auto bg-white shadow rounded-lg">
        <table class="min-w-full text-sm text-left">
            <thead class="bg-gray-100 text-gray-700 uppercase text-xs">
                <tr>
                    <th class="px-6 py-3">Invoice #</th>
                    <th class="px-6 py-3">Order ID</th>
                    <th class="px-6 py-3">School/Agent</th>
                    <th class="px-6 py-3">Amount (RM)</th>
                    <th class="px-6 py-3">Status</th>
                    <th class="px-6 py-3">Proof</th>
                    <th class="px-6 py-3">Date</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
                @forelse($invoices as $invoice)
                    @php
                        $order = $invoice->customOrder ?? $invoice->reorder;
                        $agent = $order->agent->company_name ?? '-';
                        $orderId = $order->id ?? '-';

                        // Calculate total for reorder if needed
                        if ($invoice->customOrder) {
                            $amount = 'RM' . number_format($invoice->customOrder->total_amount, 2);
                        } elseif ($invoice->reorder) {
                            $total = 0;
                            foreach ($invoice->reorder->items as $item) {
                                $total += ($item->price ?? 0) * ($item->quantity ?? 0);
                            }
                            $amount = 'RM' . number_format($total, 2);
                        } else {
                            $amount = '-';
                        }
                    @endphp
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4 font-medium text-gray-900">
                            {{ $invoice->invoice_number }}
                        </td>
                        <td class="px-6 py-4 text-gray-700">
                            {{ $orderId }}
                        </td>
                        <td class="px-6 py-4 text-gray-700">
                            {{ $agent }}
                        </td>
                        <td class="px-6 py-4 text-gray-700">
                            {{ $amount }}
                        </td>
                        <td class="px-6 py-4">
                            <span class="inline-block px-2 py-1 rounded-full text-xs font-semibold
                                {{ $invoice->cheque_proof ? 'bg-blue-100 text-blue-800' : 'bg-gray-200 text-gray-600' }}">
                                {{ $invoice->cheque_proof ? 'Submitted' : 'Not Submitted' }}
                            </span>
                        </td>
                        <td class="px-6 py-4">
                            @if($invoice->cheque_proof)
                                <a href="{{ asset('storage/' . $invoice->cheque_proof) }}" target="_blank"
                                   class="inline-block px-4 py-1 bg-blue-600 hover:bg-blue-700 text-white text-sm rounded">
                                    View Proof
                                </a>
                            @else
                                <span class="text-gray-400">-</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-gray-700">
                            {{ $invoice->issue_date ?? $invoice->created_at->format('Y-m-d') }}
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="px-6 py-4 text-center text-gray-500">
                            No payment proofs found.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
