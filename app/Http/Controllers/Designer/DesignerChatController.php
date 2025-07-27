<?php

namespace App\Http\Controllers\Designer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Conversation;
use App\Models\Message;
use Illuminate\Support\Facades\Auth;
use App\Notifications\NewMessageNotification;
use App\Models\Agent;


class DesignerChatController extends Controller
{
    public function index($orderId)
    {
        $designerId = Auth::id();

        $conversation = Conversation::where('order_id', $orderId)
            ->where('designer_id', $designerId)
            ->firstOrFail();

        $messages = $conversation->messages()->latest()->get();

        return view('designer.chat.index', compact('conversation', 'messages'));
    }

    public function send(Request $request, $conversationId)
    {
        $request->validate([
            'message' => 'required|string',
        ]);

        $designer = Auth::user();

        // Save the message
        Message::create([
            'conversation_id' => $conversationId,
            'sender_id' => $designer->id,
            'sender_type' => 'designer',
            'message' => $request->message,
            'is_read' => false,
        ]);

        // Notify the agent
        $conversation = Conversation::findOrFail($conversationId);
        $agent = $conversation->agent; // relationship in Conversation model
        $agent->notify(new NewMessageNotification($designer, $conversationId));

        return redirect()->back()->with('success', 'Message sent!');
    }

}
