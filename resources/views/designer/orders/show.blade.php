@extends('layouts.designer')

@section('title', 'View Assigned Order')

@section('content')
<div class="p-6">
    <h2 class="text-2xl font-bold mb-4">Order Details (ID: {{ $order->id }})</h2>

    {{-- Basic Info --}}
    <div class="bg-white p-4 rounded shadow mb-6">
        <h3 class="font-semibold mb-2">School & Order Info</h3>
        <p><strong>School:</strong> {{ $order->school->name ?? '-' }}</p>
        <p><strong>Template Chosen:</strong> {{ $order->design_template }}</p>
        <p><strong>Notes:</strong> {{ $order->notes ?? '-' }}</p>
    </div>

    {{-- School Logo --}}
    <div class="bg-white p-4 rounded shadow mb-6">
        <h3 class="font-semibold mb-2">School Logo</h3>
        @if ($order->school_logo_path)
            <img src="{{ asset('storage/' . $order->school_logo_path) }}" alt="School Logo"
                 class="w-40 h-auto border rounded mb-4">
            <a href="{{ asset('storage/' . $order->school_logo_path) }}"
               class="inline-block bg-blue-600 text-white text-sm px-4 py-2 rounded hover:bg-blue-700 transition"
               download>
               Download Logo
            </a>
        @else
            <p class="text-gray-500">No logo uploaded.</p>
        @endif
    </div>

    {{-- Submit Design to Agent --}}
    <div class="bg-white p-4 rounded shadow mb-6">
        <h3 class="font-semibold mb-2">Submit Design to Agent</h3>

        @if ($order->status === 'design-submitted-to-agent' && $order->design_file)
            <p class="mb-2">You have already submitted your design. Below is the uploaded file:</p>
            <a href="{{ asset('storage/' . $order->design_file) }}"
               class="inline-block bg-blue-600 text-white text-sm px-4 py-2 rounded hover:bg-blue-700 transition"
               download>
               Download Submitted Design
            </a>
        @else
            @if (session('success'))
                <div class="mb-4 text-green-600 font-semibold">
                    {{ session('success') }}
                </div>
            @endif

            <form action="{{ route('designer.orders.upload', $order->id) }}" method="POST" enctype="multipart/form-data">
                @csrf

                <div class="mb-4">
                    <label for="design_file" class="block text-sm font-medium text-gray-700 mb-1">
                        Choose Design File (PDF, JPG, PNG)
                    </label>
                    <input type="file" name="design_file" id="design_file"
                           class="block w-full border border-gray-300 rounded px-3 py-2">
                    @error('design_file')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <button type="submit"
                        class="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700 transition">
                    Submit to Agent
                </button>
            </form>
        @endif
    </div>

    {{-- Chat Button: only for rejected-by-agent --}}
    @if ($order->status === 'rejected-by-agent' && $order->conversation)
        <a href="{{ route('designer.chat.index', $order->id) }}"
           class="inline-block bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700 transition mb-4">
           Chat with Agent
        </a>
    @endif

    {{-- Back Button --}}
    <a href="{{ route('designer.orders.index') }}"
       class="inline-block bg-gray-300 px-4 py-2 rounded hover:bg-gray-400">
       Back to Orders
    </a>
</div>
@endsection
