@extends('layouts.agent')

@section('content')
<div class="p-8">
    <h1 class="text-2xl font-bold mb-6">Invoice #{{ $invoice->invoice_number ?? $invoice->id }}</h1>
    <div><strong>Status:</strong> {{ ucfirst($invoice->status) }}</div>
</div>
@endsection
