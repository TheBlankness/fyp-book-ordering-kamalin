@extends('layouts.support')

@section('title', 'Edit Order')

@section('content')
<div class="p-6">
    <h2 class="text-2xl font-bold mb-6">Manage Order (Order ID: {{ $order->id }})</h2>

    @if (session('success'))
        <div class="mb-4 text-green-600 font-semibold">
            {{ session('success') }}
        </div>
    @endif

    <div class="bg-white rounded shadow p-6 max-w-2xl">
        <form action="{{ route('support.orders.update', $order->id) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="mb-4">
                <label class="block text-sm font-medium mb-1">School Name</label>
                <input type="text" name="school_name" value="{{ old('school_name', $order->school_name) }}"
                       class="w-full px-4 py-2 border rounded" required>
            </div>

            <div class="mb-4">
                <label class="block text-sm font-medium mb-1">Notes</label>
                <textarea name="notes" rows="3"
                          class="w-full px-4 py-2 border rounded">{{ old('notes', $order->notes) }}</textarea>
            </div>

            <div class="flex space-x-4">
                <button type="submit" name="assign" value="designer"
                        class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
                    Assign to Designer
                </button>

                <button type="submit" name="assign" value="planner"
                        class="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700">
                    Assign to Planner
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
