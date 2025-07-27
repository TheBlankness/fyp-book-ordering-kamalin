@extends('layouts.agent')

@section('content')
<div class="max-w-4xl mx-auto px-4 py-8">
    <h1 class="text-2xl font-bold mb-6">Select Book Design Template</h1>

    @if(session('error'))
        <div class="bg-red-100 text-red-800 px-4 py-2 rounded mb-4">
            {{ session('error') }}
        </div>
    @endif

    <form action="{{ route('agent.custom-order.design.submit') }}" method="POST" enctype="multipart/form-data">
        @csrf

        {{-- Design Templates --}}
        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-6 mb-8">
            @foreach($templates as $template)
                <label class="cursor-pointer block border rounded-xl overflow-hidden shadow hover:shadow-md transition relative w-full h-[420px]">
                    <input type="radio" name="selected_template" value="{{ $template['name'] }}" class="absolute top-2 right-2 w-5 h-5 z-10" required>
                    <img src="{{ asset('images/designs/' . $template['image']) }}" alt="{{ $template['name'] }}" class="w-full h-full object-cover">
                    <div class="absolute bottom-0 left-0 right-0 bg-white bg-opacity-80 text-center py-2 font-semibold">
                        {{ $template['name'] }}
                    </div>
                </label>
            @endforeach
        </div>

        {{-- School Name --}}
        <div class="mb-4">
            <label for="school_name" class="block font-medium mb-1">School Name</label>
            <input type="text" name="school_name" id="school_name" class="w-full border border-gray-300 rounded px-3 py-2" required>
        </div>

        {{-- School Logo --}}
        <div class="mb-4">
            <label for="school_logo" class="block font-medium mb-1">Upload School Logo</label>

            <div class="flex items-center space-x-4">
                <label for="school_logo" class="bg-gray-700 hover:bg-gray-800 text-white px-4 py-2 rounded cursor-pointer">
                    Choose File
                </label>
                <span id="file-name" class="text-gray-600">No file chosen</span>
            </div>

            <input type="file" name="school_logo" id="school_logo" accept="image/*" class="hidden" onchange="document.getElementById('file-name').textContent = this.files[0]?.name || 'No file chosen';">
        </div>

        {{-- Notes --}}
        <div class="mb-6">
            <label for="notes" class="block font-medium mb-1">Notes (Optional)</label>
            <textarea name="notes" id="notes" rows="4" class="w-full border border-gray-300 rounded px-3 py-2"></textarea>
        </div>

        {{-- Delivery Option --}}
        <div class="mb-4">
            <label class="block font-medium mb-1">Delivery Option</label>
            <select name="delivery_option" id="delivery_option" class="w-full border border-gray-300 rounded px-3 py-2" required onchange="toggleDeliveryFields()">
                <option value="">-- Select Option --</option>
                <option value="pickup">Self Pick Up</option>
                <option value="delivery">Delivery</option>
            </select>
        </div>

        {{-- Delivery Fields --}}
        <div id="delivery_fields" style="display:none;">
            <div class="mb-4">
                <label for="delivery_address" class="block font-medium mb-1">Delivery Address</label>
                <textarea name="delivery_address" id="delivery_address" class="w-full border border-gray-300 rounded px-3 py-2"></textarea>
            </div>
        </div>

        <script>
        function toggleDeliveryFields() {
            var option = document.getElementById('delivery_option').value;
            document.getElementById('delivery_fields').style.display = (option === 'delivery') ? 'block' : 'none';
        }
        </script>

        {{-- Submit --}}
        <div class="text-right">
            <button type="submit" class="bg-blue-600 text-white px-6 py-2 rounded hover:bg-blue-700">
                Submit Order
            </button>
        </div>
    </form>
</div>
@endsection
