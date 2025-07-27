@extends('layouts.agent')

@section('title', 'Reorder from Past Orders')

@section('content')
<div class="max-w-4xl mx-auto">
    <h2 class="text-2xl font-bold mb-6">Reorder from Past Orders</h2>

    @if ($orders->isEmpty())
        <div class="bg-blue-100 text-blue-700 p-4 rounded">
            You have no completed orders available for reorder.
        </div>
    @else
        <div class="overflow-x-auto">
            <table class="w-full bg-white shadow rounded">
                <thead>
                    <tr class="bg-gray-100 text-left">
                        <th class="p-3">Order Reference</th>
                        <th class="p-3">Date</th>
                        <th class="p-3">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($orders as $order)
                        <tr class="border-t hover:bg-gray-50">
                            <td class="p-3">{{ $order->reference_number }}</td>
                            <td class="p-3">{{ $order->created_at->format('d M Y') }}</td>
                            <td class="p-3">
                                <form action="{{ route('agent.orders.reorder', $order->id) }}" method="POST">
                                    @csrf
                                    <button type="submit" class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-1 rounded text-sm">
                                        Reorder
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif
</div>
@endsection
