@extends('layouts.agent')

@section('title', 'Edit Order')

@section('content')
<div class="container mx-auto px-4 py-6">
    <h1 class="text-2xl font-bold mb-4">Edit Order #{{ $order->id }}</h1>

    <form action="{{ route('agent.orders.update', $order->id) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="mb-4">
            <label class="block text-sm font-medium text-gray-700">School Name</label>
            <input type="text" name="school_name" value="{{ old('school_name', $order->school_name) }}"
                   class="w-full mt-1 p-2 border rounded" required>
        </div>

        <div class="mb-4">
            <label class="block text-sm font-medium text-gray-700">Notes</label>
            <textarea name="notes" rows="4" class="w-full mt-1 p-2 border rounded">{{ old('notes', $order->notes) }}</textarea>
        </div>

        <h2 class="text-lg font-semibold mt-6 mb-2">Books in This Order</h2>
        @if ($order->school_logo_path)
            <div class="mb-6">
                <p class="text-sm font-medium text-gray-700 mb-1">Uploaded School Logo:</p>
                <img src="{{ asset('storage/' . $order->school_logo_path) }}" alt="School Logo"
                class="h-24 w-auto max-w-xs object-contain border rounded shadow">
            </div>
        @endif

        <div class="space-y-4">
            @foreach ($items as $item)
                <div class="border rounded p-4">
                    <p><strong>Title:</strong> {{ $item->title }}</p>
                    <p><strong>Cover:</strong> {{ $item->cover }}</p>
                    <p><strong>Quantity:</strong> {{ $item->quantity }}</p>
                </div>
            @endforeach
        </div>

        <div class="mt-6">
            <button type="submit"
                    class="bg-blue-600 text-white px-6 py-2 rounded hover:bg-blue-700 transition">
                Save Changes
            </button>
        </div>
    </form>
</div>
@endsection
