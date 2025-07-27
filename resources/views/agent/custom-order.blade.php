@extends('layouts.agent')

@section('content')
<div class="max-w-7xl mx-auto px-4 py-8">
    <h1 class="text-2xl font-bold mb-6">Place Customized Order</h1>

    @if(session('success'))
        <div class="bg-green-100 text-green-800 px-4 py-2 rounded mb-4">
            {{ session('success') }}
        </div>
    @endif

    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
        @foreach ($books as $book)
            <div class="border rounded-xl p-4 shadow hover:shadow-md transition bg-white">
                <img src="{{ asset('images/' . $book['image']) }}" alt="{{ $book['title'] }}" class="w-full h-48 object-cover rounded mb-3">

                <h2 class="font-semibold text-lg">{{ $book['title'] }}</h2>
                <p class="text-sm text-gray-600">Color: {{ $book['color'] }}</p>
                <p class="text-sm text-gray-600">Cover: {{ $book['cover'] }}</p>
                <p class="text-sm text-gray-600">Pages: {{ $book['pages'] }}</p>
                <p class="text-sm text-gray-600">GSM: {{ $book['gsm'] }}</p>
                <p class="text-sm text-gray-600">Price: RM {{ number_format($book['price'], 2) }}</p>

                <form action="{{ route('agent.custom-order.cart') }}" method="POST" class="mt-4">
                    @csrf
                    <label class="block text-sm mb-1">Quantity</label>
                    <input type="number" name="quantity" min="1" class="w-full border rounded px-3 py-1 mb-2" required>

                    <input type="hidden" name="id" value="{{ $book['id'] }}">
                    <input type="hidden" name="title" value="{{ $book['title'] }}">
                    <input type="hidden" name="cover" value="{{ $book['cover'] }}">

                    <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700 w-full">
                        Add to Cart
                    </button>
                </form>
            </div>
        @endforeach
    </div>
</div>

<!-- Fixed View Cart button -->
<a href="{{ route('agent.custom-order.cart.view') }}"
   class="fixed bottom-6 right-6 bg-gray-900 text-white px-6 py-3 rounded-full shadow-lg hover:bg-gray-800 transition">
   View Cart
</a>
@endsection
