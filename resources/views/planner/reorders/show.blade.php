@extends('layouts.planner')

@section('title', 'Reorder Details')

@section('content')
<div class="p-6">
    <h1 class="text-2xl font-bold mb-4">Reorder #{{ $reorder->id }}</h1>

    <div class="mb-4 space-y-1">
        <p><strong>Agent:</strong> {{ $reorder->agent->company_name ?? '-' }}</p>
        <p><strong>School:</strong> {{ $reorder->school->name ?? '-' }}</p>
        <p><strong>Status:</strong> {{ ucfirst($reorder->status) }}</p>
        <p><strong>Submitted At:</strong> {{ $reorder->submitted_at ?? '-' }}</p>
    </div>

    <h2 class="text-xl font-semibold mt-6 mb-2">Reordered Items</h2>
    @if($reorder->items->isEmpty())
        <p>No items found.</p>
    @else
        <table class="w-full table-auto border-collapse mb-6">
            <thead>
                <tr class="bg-gray-200">
                    <th class="border px-4 py-2">Book</th>
                    <th class="border px-4 py-2">Cover</th>
                    <th class="border px-4 py-2">Quantity</th>
                    <th class="border px-4 py-2">Price</th>
                    <th class="border px-4 py-2">Total</th>
                </tr>
            </thead>
            <tbody>
                @foreach($reorder->items as $item)
                    <tr>
                        <td class="border px-4 py-2">{{ $item->title }}</td>
                        <td class="border px-4 py-2">{{ $item->cover }}</td>
                        <td class="border px-4 py-2">{{ $item->quantity }}</td>
                        <td class="border px-4 py-2">RM{{ number_format($item->price, 2) }}</td>
                        <td class="border px-4 py-2">RM{{ number_format($item->price * $item->quantity, 2) }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif

    {{-- Next step: Schedule button / form --}}
    <a href="#" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">Schedule Production</a>
</div>
@endsection
