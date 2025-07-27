@extends('layouts.agent')

@section('title', 'Past Ordered Schools')

@section('content')
<div class="p-8">
    <h1 class="text-2xl font-bold mb-6">Schools You've Ordered For</h1>

    {{-- Search Form --}}
    <form method="GET" action="{{ route('agent.orders.pastSchools') }}" class="mb-6 flex gap-4 items-end">
        <div>
            <label for="school" class="block text-sm font-medium text-gray-700">Search School</label>
            <input type="text" name="school" id="school"
                value="{{ request('school') }}"
                class="mt-1 block w-72 rounded-md border-gray-300 shadow-sm text-sm"
                placeholder="Enter school name...">
        </div>
        <div>
            <button type="submit"
                    class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded">
                Search
            </button>
            <a href="{{ route('agent.orders.pastSchools') }}"
            class="bg-gray-300 hover:bg-gray-400 text-gray-800 text-sm font-semibold px-4 py-2 rounded shadow inline-block">
                Reset
            </a>
        </div>
    </form>

    @if ($schoolOrders->isEmpty())
        <p class="text-gray-600">No schools found. You have not ordered for any schools yet.</p>
    @else
        <div class="bg-white shadow rounded-lg overflow-hidden">
            <table class="min-w-full divide-y divide-gray-200 text-sm">
                <thead class="bg-gray-100 text-gray-700 uppercase text-left">
                    <tr>
                        <th class="px-6 py-3">School</th>
                        <th class="px-6 py-3">Last Ordered At</th>
                        <th class="px-6 py-3 text-center">Action</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200 text-gray-800">
                    @foreach($schoolOrders as $order)
                    <tr>
                        <td class="px-6 py-4 font-medium">
                            {{ optional($order->school)->name ?? '-' }}
                        </td>
                        <td class="px-6 py-4">
                            {{ $order->created_at->format('d M Y') }}
                        </td>
                        <td class="px-6 py-4 text-center">
                            <a href="{{ route('agent.orders.reorder.bySchool', $order->school_id) }}"
                               class="inline-block bg-blue-600 hover:bg-blue-700 text-white text-sm font-semibold px-4 py-1.5 rounded shadow">
                                Order
                            </a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif
</div>
@endsection
