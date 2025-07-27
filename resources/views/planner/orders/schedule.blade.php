@extends('layouts.planner')

@section('title', 'Schedule Order')

@section('content')
<div class="p-8 max-w-3xl mx-auto">
    <h1 class="text-2xl font-bold mb-6">
        Schedule {{ ucfirst($type) }} Order
    </h1>

    {{-- Order Details --}}
    <div class="bg-white shadow rounded-lg p-6 mb-6">
        <h2 class="text-lg font-semibold mb-4">Order Details</h2>
        <div class="space-y-2 text-sm text-gray-800">
            <div><strong>Order ID:</strong> #{{ $order->id }}</div>
            <div><strong>Order Type:</strong> {{ ucfirst($type) }}</div>
            <div><strong>School Name:</strong> {{ $order->school->name ?? $order->school_name ?? '-' }}</div>
            <div><strong>Agent:</strong> {{ $order->agent->company_name ?? '-' }}</div>
            <div>
                <strong>Status:</strong>
                <span class="inline-block px-3 py-1 text-xs font-semibold rounded-full bg-gray-200 text-gray-700">
                    {{ str_replace('-', ' ', $order->status) }}
                </span>
            </div>
            <div><strong>Production Date:</strong> {{ $order->production_date ?? '-' }}</div>
            <div><strong>Notes:</strong> {{ $order->planner_notes ?? '-' }}</div>
        </div>
    </div>

    {{-- Order Items --}}
    <div class="bg-white shadow rounded-lg p-6 mb-6">
        <h2 class="text-lg font-semibold mb-4">Order Items</h2>
        @if ($order->items && count($order->items))
            <ul class="list-disc list-inside text-sm text-gray-800">
                @foreach ($order->items as $item)
                    <li>{{ $item->book['bookType'] ?? $item->title ?? '-' }} - Quantity: {{ $item->quantity }}</li>
                @endforeach
            </ul>
        @else
            <p class="text-gray-500 text-sm">No items found.</p>
        @endif
    </div>

    {{-- Download PDFs --}}
    <div class="flex flex-col sm:flex-row gap-4 mb-6">
        <a href="{{ route('planner.orders.checklist.download', ['type' => $type, 'id' => $order->id]) }}"
           class="inline-block bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-semibold px-6 py-2 rounded shadow text-center">
            Download Checklist PDF
        </a>

        <a href="{{ route('planner.orders.design.download', ['type' => $type, 'id' => $order->id]) }}"
           class="inline-block bg-pink-600 hover:bg-pink-700 text-white text-sm font-semibold px-6 py-2 rounded shadow text-center">
            Download Design PDF
        </a>
    </div>

    @if (session('success'))
        <div class="mb-4 bg-green-100 text-green-800 px-4 py-2 rounded shadow text-sm">
            {{ session('success') }}
        </div>
    @endif

    {{-- Scheduling Form --}}
    @if (!$isScheduled)
        <form action="{{ route('planner.orders.schedule.save', ['type' => $type, 'id' => $order->id]) }}"
              method="POST"
              class="bg-white shadow rounded-lg p-6">
            @csrf
            <h2 class="text-lg font-semibold mb-4">Schedule This Order</h2>

            <div class="mb-4">
                <label for="production_date" class="block mb-1 font-medium text-sm">Production Date</label>
                <input type="date" name="production_date" id="production_date"
                    value="{{ old('production_date') }}"
                    min="{{ \Carbon\Carbon::today('Asia/Kuala_Lumpur')->format('Y-m-d') }}"
                    class="border rounded px-3 py-2 w-full text-sm text-gray-800">
                @if ($errors->has('production_date'))
                    <p class="text-red-600 text-sm mt-1">{{ $errors->first('production_date') }}</p>
                @endif
            </div>

            <div class="mb-4">
                <label for="notes" class="block mb-1 font-medium text-sm">Notes (optional)</label>
                <textarea name="notes" id="notes" rows="3" class="border rounded px-3 py-2 w-full text-sm text-gray-800">
                    {{ old('notes') }}
                </textarea>


            <button type="submit"
                    class="bg-blue-600 hover:bg-blue-700 text-white text-sm font-semibold px-6 py-2 rounded shadow">
                Save Schedule
            </button>
        </form>
        @elseif (!in_array($order->status, ['in-production', 'completed']) && !session('success'))
            <div class="bg-green-100 text-green-800 px-4 py-3 rounded text-center font-semibold shadow">
                This order has already been scheduled. You can view the details above.
            </div>
        @endif
</div>
@endsection
