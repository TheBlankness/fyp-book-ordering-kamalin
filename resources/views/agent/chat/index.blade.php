@extends('layouts.agent')

@section('title', 'Chat with Graphic Designer')

@section('content')
<div class="p-8 max-w-4xl mx-auto">
    <h1 class="text-2xl font-bold mb-6">Chat with Graphic Designer</h1>

    {{-- Chat History --}}
    <div class="bg-white rounded-xl shadow border p-6 h-[500px] overflow-y-scroll mb-6 space-y-4">
        @if ($messages->isEmpty())
            <p class="text-gray-500 text-center italic">No messages yet. Start the conversation below.</p>
        @endif

        @foreach ($messages->reverse() as $message)
            <div class="flex {{ $message->sender_type === 'agent' ? 'justify-end' : 'justify-start' }}">
                <div class="max-w-xs px-4 py-2 rounded-lg text-sm shadow
                    {{ $message->sender_type === 'agent'
                        ? 'bg-blue-100 text-blue-800'
                        : 'bg-gray-100 text-gray-800' }}">
                    <p class="mb-1">{{ $message->message }}</p>
                    <p class="text-xs text-gray-500 text-right">
                        {{ $message->created_at->format('d M Y, h:i A') }}
                    </p>
                </div>
            </div>
        @endforeach
    </div>

    {{-- Chat Input --}}
    <form action="{{ route('agent.chat.send', $conversation->id) }}" method="POST" class="flex gap-2">
        @csrf
        <input type="text" name="message" required placeholder="Type your message..."
               class="flex-1 border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring focus:border-blue-400 shadow-sm text-sm">
        <button type="submit"
                class="bg-blue-600 hover:bg-blue-700 text-white text-sm font-semibold px-4 py-2 rounded shadow">
            Send
        </button>
    </form>
</div>
@endsection
