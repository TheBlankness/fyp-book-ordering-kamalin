@extends('layouts.agent')

@section('content')
<div class="max-w-5xl mx-auto px-4 py-8">
    <h1 class="text-2xl font-bold mb-6">Your Cart</h1>

    @if(session('success'))
        <div class="bg-green-100 text-green-800 px-4 py-2 rounded mb-4">
            {{ session('success') }}
        </div>
    @endif

    @if(count($cart) > 0)
        <form action="{{ route('agent.orders.reorder.cart.update') }}" method="POST">
            @csrf
            <table class="table-auto w-full border-collapse mt-6">
                <thead>
                    <tr>
                        <th class="border px-4 py-2">No</th>
                        <th class="border px-4 py-2">Book Type</th>
                        <th class="border px-4 py-2">Color</th>
                        <th class="border px-4 py-2">Cover</th>
                        <th class="border px-4 py-2">Pages</th>
                        <th class="border px-4 py-2">GSM</th>
                        <th class="border px-4 py-2">Unit Price (RM)</th>
                        <th class="border px-4 py-2">Quantity</th>
                        <th class="border px-4 py-2">Amount (RM)</th>
                        <th class="border px-4 py-2">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @php $total = 0; @endphp
                    @foreach ($cart as $index => $item)
                        @php
                            $book = $item['book'];
                            $qty = $item['quantity'];
                            $amount = $book->price * $qty;
                            $total += $amount;
                        @endphp
                        <tr>
                            <td class="border px-4 py-2">{{ $index + 1 }}</td>
                            <td class="border px-4 py-2">{{ $book->bookType }}</td>
                            <td class="border px-4 py-2">{{ $book->color }}</td>
                            <td class="border px-4 py-2">{{ $book->coverType }}</td>
                            <td class="border px-4 py-2">{{ $book->pageNumber }}</td>
                            <td class="border px-4 py-2">{{ $book->gsm }}</td>
                            <td class="border px-4 py-2">{{ number_format($book->price, 2) }}</td>

                            {{-- Editable quantity input --}}
                            <td class="border px-4 py-2">
                                <input type="number" name="cart[{{ $index }}][quantity]" value="{{ $qty }}" min="1" class="w-20 border px-2 py-1 text-center">
                                <input type="hidden" name="cart[{{ $index }}][book_id]" value="{{ $item['book_id'] }}">
                                <input type="hidden" name="cart[{{ $index }}][title]" value="{{ $book->bookType }}">
                                <input type="hidden" name="cart[{{ $index }}][cover]" value="{{ $book->coverType }}">
                            </td>

                            <td class="border px-4 py-2">{{ number_format($amount, 2) }}</td>

                            {{-- Remove button --}}
                            <td class="border px-4 py-2 text-center">
                                <button type="submit" name="remove" value="{{ $index }}" class="text-red-600 hover:underline">
                                    Remove
                                </button>
                            </td>
                        </tr>
                    @endforeach
                    <tr>
                        <td colspan="8" class="text-right font-bold border px-4 py-2">Total</td>
                        <td class="border px-4 py-2 font-bold">RM {{ number_format($total, 2) }}</td>
                        <td class="border px-4 py-2"></td>
                    </tr>
                </tbody>
            </table>

            <div class="mt-6 flex gap-2">
                <a href="{{ route('agent.orders.reorder.catalog') }}" class="bg-gray-600 text-white px-4 py-2 rounded hover:bg-gray-700">
                    Back to Catalog
                </a>
                <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
                    Update Cart
                </button>
            </div>
        </form>

        <!-- Delivery Option -->
        <form action="{{ route('agent.custom-order.reorder.submit') }}" method="POST" class="mt-8">
            @csrf
            <div class="mb-4">
                <label class="block font-semibold mb-2">Delivery Option</label>
                <div class="flex items-center gap-6">
                    <label class="inline-flex items-center">
                        <input type="radio" name="delivery_option" value="pickup" {{ old('delivery_option', session('reorder_design_info.delivery_option', 'pickup')) == 'pickup' ? 'checked' : '' }}>
                        <span class="ml-2">Self Pick Up</span>
                    </label>
                    <label class="inline-flex items-center">
                        <input type="radio" name="delivery_option" value="delivery" {{ old('delivery_option', session('reorder_design_info.delivery_option')) == 'delivery' ? 'checked' : '' }}>
                        <span class="ml-2">Delivery</span>
                    </label>
                </div>
            </div>

            <div id="delivery-address-section" class="mb-4" style="display: {{ old('delivery_option', session('reorder_design_info.delivery_option')) == 'delivery' ? 'block' : 'none' }};">
                <label class="block font-semibold mb-2">Delivery Address</label>
                <textarea name="delivery_address" class="w-full border rounded px-3 py-2" rows="2">{{ old('delivery_address', session('reorder_design_info.delivery_address')) }}</textarea>
            </div>

            <button type="submit" class="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700">
                Submit Order
            </button>
        </form>
    @else
        <p class="text-gray-700">Your cart is empty.</p>
        <a href="{{ route('agent.orders.reorder.catalog') }}" class="mt-4 inline-block bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
            Go to Catalog
        </a>
    @endif
</div>

<script>
    function toggleDeliveryAddress() {
        const deliveryOption = document.querySelector('input[name="delivery_option"]:checked').value;
        document.getElementById('delivery-address-section').style.display = deliveryOption === 'delivery' ? 'block' : 'none';
    }

    document.querySelectorAll('input[name="delivery_option"]').forEach(function(radio) {
        radio.addEventListener('change', toggleDeliveryAddress);
    });

    // Call once on page load
    toggleDeliveryAddress();
</script>
@endsection
