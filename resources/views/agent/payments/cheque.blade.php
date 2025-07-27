@extends('layouts.agent')
@section('content')
<div class="p-8 max-w-lg mx-auto">
    <h1 class="text-2xl font-bold mb-6">Upload Cheque Payment Proof</h1>
    <div class="mb-4 text-gray-700">
        <p>To pay by cheque, send the physical cheque to our office. Please upload proof (photo/scan) here.</p>
    </div>
    <form method="POST" action="{{ route('agent.payments.cheque.upload', $invoice->id) }}" enctype="multipart/form-data">
        @csrf
        <div class="mb-4">
            <label class="block mb-2">Cheque Proof (Image/PDF)</label>
            <input type="file" name="cheque_proof" class="block w-full border p-2 rounded" accept="image/*,application/pdf" required>
            @error('cheque_proof')
                <span class="text-red-600">{{ $message }}</span>
            @enderror
        </div>
        <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">Upload Proof</button>
    </form>
</div>
@endsection
