@extends('layouts.agent')

@section('content')
<div class="p-8 max-w-lg mx-auto">
    <h1 class="text-2xl font-bold mb-6">Select Payment Method</h1>
    <form method="GET" action="{{ route('agent.payments.method', $invoice->id) }}">
        <div class="mb-4">
            <label class="block mb-2 font-semibold">How would you like to pay?</label>
            <select name="method" class="w-full border rounded p-2" required>
                <option value="">-- Please select --</option>
                <option value="online_banking">Online Banking</option>
                <option value="cheque">Cheque</option>
            </select>
        </div>
        <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">Continue</button>
    </form>
</div>
@endsection
