@extends('layouts.designer') 

@section('title', 'Chat with Agent')

@section('content')
<div class="max-w-4xl mx-auto px-4 py-8">
    <h1 class="text-2xl font-bold mb-4">Chat with Agent (Order ID: {{ $conversation->order_id }})</h1>

    <div class="bg-white rounded shadow p-4 h-[500px] overflow-y-scroll border mb-6">
        @if($messages->isEmpty())
            <p class="text-gray-500">No messages yet. Start the conversation below.</p>
        @endif

        @foreach($messages->reverse() as $message)
            <div class="mb-4">
                <div class="{{ $message->sender_type === 'designer' ? 'text-right' : 'text-left' }}">
                    <div class="inline-block px-4 py-2 rounded-lg {{ $message->sender_type === 'designer' ? 'bg-blue-100 text-blue-800' : 'bg-gray-200 text-gray-800' }}">
                        <p class="mb-1">{{ $message->message }}</p>
                        <small class="text-xs text-gray-500">{{ $message->created_at->format('d M Y, h:i A') }}</small>
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    <form action="{{ route('designer.chat.send', $conversation->id) }}" method="POST" class="flex gap-2">
        @csrf
        <input type="text" name="message" required placeholder="Type your message..."
               class="flex-1 border rounded px-4 py-2 focus:outline-none focus:ring focus:border-blue-300">
        <button type="submit"
                class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700 transition">
            Send
        </button>
    </form>
</div>
@endsection
