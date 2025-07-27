<?php

namespace App\Http\Controllers\Agent;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Conversation;
use App\Models\Message;
use Illuminate\Support\Facades\Auth;
use App\Notifications\NewMessageNotification;
use App\Models\User; // designer


class ChatController extends Controller
{
    public function index($orderId)
    {
        $agentId = Auth::guard('agent')->user()->id;

        // Use fixed designer ID = 2
        $conversation = Conversation::firstOrCreate(
            ['order_id' => $orderId, 'agent_id' => $agentId],
            ['designer_id' => 2] // hardcoded designer ID
        );

        $messages = $conversation->messages()->latest()->get();

        return view('agent.chat.index', compact('conversation', 'messages'));
    }

    public function send(Request $request, $conversationId)
    {
        $request->validate([
            'message' => 'required|string',
        ]);

        $agent = Auth::guard('agent')->user();

        // Save the message
        Message::create([
            'conversation_id' => $conversationId,
            'sender_id' => $agent->id,
            'sender_type' => 'agent',
            'message' => $request->message,
            'is_read' => false,
        ]);

        // Notify the designer
        $conversation = Conversation::findOrFail($conversationId);
        $designer = $conversation->designer; // relationship in Conversation model
        $designer->notify(new NewMessageNotification($agent, $conversationId));

        return redirect()->back()->with('success', 'Message sent!');
    }

}
