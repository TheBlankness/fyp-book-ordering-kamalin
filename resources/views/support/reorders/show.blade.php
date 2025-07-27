@extends('layouts.support')

@section('title', 'Reorder Details')

@section('content')
<div class="p-6">
    <h1 class="text-2xl font-bold mb-4">Reorder #{{ $reorder->id }}</h1>

    {{-- Agent Company Info --}}
    <div class="bg-white rounded shadow p-6 mb-6">
        <h3 class="text-lg font-semibold mb-2">Agent Company Info</h3>
        <ul class="text-gray-700 space-y-1">
            <li><strong>Company Name:</strong> {{ $reorder->agent->company_name ?? '—' }}</li>
            <li><strong>SSM Number:</strong> {{ $reorder->agent->ssm_number ?? '—' }}</li>
            <li><strong>Business Type:</strong> {{ $reorder->agent->business_type ?? '—' }}</li>
            <li><strong>Email:</strong> {{ $reorder->agent->email ?? '—' }}</li>
            <li><strong>Phone:</strong> {{ $reorder->agent->company_phone ?? '—' }}</li>
            <li><strong>Business Address:</strong> {{ $reorder->agent->business_address ?? '—' }}</li>
        </ul>
    </div>

    {{-- Order Info --}}
    <div class="bg-white rounded shadow p-6 mb-6">
        <h3 class="text-lg font-semibold mb-2">Order Info</h3>
        <ul class="text-gray-700 space-y-1">
            <li><strong>School Name:</strong> {{ $reorder->school->name ?? '-' }}</li>
            <li><strong>Status:</strong> {{ ucfirst($reorder->status) }}</li>
            <li><strong>Submitted At:</strong> {{ $reorder->submitted_at ? \Carbon\Carbon::parse($reorder->submitted_at)->format('d M Y, h:i A') : '—' }}</li>
            <li><strong>Notes:</strong> {{ $reorder->notes ?? '-' }}</li>
        </ul>
    </div>

    {{-- Delivery Info --}}
    @if(isset($reorder->delivery_option))
    <div class="bg-white rounded shadow p-6 mb-6">
        <h3 class="text-lg font-semibold mb-2">Delivery Information</h3>
        <ul class="text-gray-700 space-y-1">
            <li><strong>Delivery Option:</strong> {{ $reorder->delivery_option === 'delivery' ? 'Delivery' : 'Self Pick Up' }}</li>
            @if($reorder->delivery_option === 'delivery')
                <li><strong>Delivery Address:</strong> {{ $reorder->delivery_address ?? '-' }}</li>
                <li><strong>Delivery Fee:</strong> RM{{ number_format($reorder->delivery_fee, 2) }}</li>
            @endif
        </ul>
    </div>
    @endif

    {{-- School Logo --}}
    <div class="bg-white rounded shadow p-6 mb-6">
        <h3 class="text-lg font-semibold mb-2">School Logo</h3>
        @if ($reorder->school_logo_path)
            <img src="{{ asset('storage/' . $reorder->school_logo_path) }}" alt="School Logo" class="w-40 h-auto border rounded">
        @else
            <p class="text-gray-500 italic">No logo uploaded.</p>
        @endif
    </div>

    {{-- Design File --}}
    <div class="bg-white rounded shadow p-6 mb-6">
        <h3 class="text-lg font-semibold mb-2">Uploaded Design</h3>
        @if ($reorder->design_file)
            <img src="{{ asset('storage/' . $reorder->design_file) }}" alt="Design File" class="w-full max-w-lg rounded border shadow">
        @else
            <p class="text-gray-500 italic">No design file uploaded yet.</p>
        @endif
    </div>

    {{-- Ordered Items --}}
    <div class="bg-white rounded shadow p-6 mb-6">
        <h3 class="text-lg font-semibold mb-4">Ordered Items</h3>
        @if ($reorder->items->count())
            <table class="w-full text-sm border border-collapse">
                <thead>
                    <tr class="bg-gray-100 text-left">
                        <th class="border px-4 py-2">Book</th>
                        <th class="border px-4 py-2">Qty</th>
                        <th class="border px-4 py-2">Unit Price (RM)</th>
                        <th class="border px-4 py-2">Total (RM)</th>
                    </tr>
                </thead>
                <tbody>
                    @php $subtotal = 0; @endphp
                    @foreach ($reorder->items as $item)
                        @php
                            $lineTotal = $item->price * $item->quantity;
                            $subtotal += $lineTotal;
                            $bookLabel = $item->title;
                            if ($item->cover || $item->color) {
                                $bookLabel .= ' (' . implode(', ', array_filter([$item->cover, $item->color])) . ')';
                            }
                        @endphp
                        <tr>
                            <td class="border px-4 py-2">{{ $bookLabel }}</td>
                            <td class="border px-4 py-2">{{ $item->quantity }}</td>
                            <td class="border px-4 py-2">{{ number_format($item->price, 2) }}</td>
                            <td class="border px-4 py-2">{{ number_format($lineTotal, 2) }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @else
            <p class="text-gray-500 italic">No items found.</p>
        @endif
    </div>

    {{-- Buttons --}}
    <div class="bg-white rounded shadow p-6 mb-10">
        <div class="flex flex-col md:flex-row md:items-center md:space-x-4 space-y-3 md:space-y-0">

            {{-- Download PO --}}
            @if ($reorder->status !== 'completed')
                <a href="{{ route('support.reorders.downloadPO', $reorder->id) }}"
                class="w-full md:w-auto px-6 py-2 bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded text-center">
                    Download PO Form
                </a>
            @endif


            {{-- Preview Invoice (only if completed and invoice is draft or not created yet) --}}
            @if (
                $reorder->status === 'completed' &&
                (
                    !$reorder->invoice || $reorder->invoice->status === 'draft'
                )
            )
                <form action="{{ route('support.orders.invoice.preview', ['type' => 'reorder', 'id' => $reorder->id]) }}" method="GET" class="w-full md:w-auto">
                    <button type="submit" class="w-full px-6 py-2 bg-yellow-500 hover:bg-yellow-600 text-white font-semibold rounded">
                        Preview Invoice
                    </button>
                </form>
            @endif

            {{-- Download Invoice --}}
            @if($reorder->invoice && $reorder->invoice->status === 'unpaid')
                <a href="{{ route('support.orders.invoice.download', ['type' => 'reorder', 'id' => $reorder->id]) }}"
                   class="w-full md:w-auto px-6 py-2 bg-green-600 hover:bg-green-700 text-white font-semibold rounded text-center">
                   Download Invoice PDF
                </a>
            @endif

            {{-- Assign to Planner --}}
            @if($reorder->status !== 'completed')
                <form action="{{ route('support.reorders.assignPlanner', $reorder->id) }}" method="POST" class="w-full md:w-auto">
                    @csrf
                    <button type="submit" class="w-full px-6 py-2 bg-green-600 hover:bg-green-700 text-white font-semibold rounded">
                        Assign to Planner
                    </button>
                </form>
            @endif

            {{-- Back --}}
            <a href="{{ route('support.reorders.index') }}"
               class="w-full md:w-auto px-6 py-2 bg-gray-400 hover:bg-gray-500 text-white font-semibold rounded text-center">
                Back to Reorders
            </a>
        </div>

        {{-- Invoice Sent Label --}}
        @if($reorder->invoice && $reorder->invoice->status === 'unpaid')
            <div class="mt-6 text-green-700 font-bold">
                Invoice Sent
            </div>
        @endif
    </div>
</div>
@endsection
