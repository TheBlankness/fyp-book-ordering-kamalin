@extends('layouts.agent')

@section('content')
<div class="p-8 max-w-lg mx-auto">
    <h1 class="text-2xl font-bold mb-6">Online Banking Payment</h1>

    <div class="mb-4">
        <p>
            Total to Pay (including delivery):
            <strong>RM{{ number_format($invoice->total_amount, 2) }}</strong>
        </p>
    </div>

    {{-- Disabled button for demo --}}
    <button
        class="bg-green-400 text-white px-4 py-2 rounded cursor-not-allowed opacity-70"
        disabled
    >
        Proceed to Payment Gateway
    </button>

    <div class="mt-4 text-gray-600">
        * This is a demo. Integration with a real payment gateway is not enabled.
    </div>
</div>
@endsection
