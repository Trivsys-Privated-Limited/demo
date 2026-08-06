<?php

namespace App\Http\Controllers;

use App\Models\ContactMessage;
use App\Models\User;
use Illuminate\Http\Request;

class ContactController extends Controller
{
    public function create()
    {
        return view('backend.contact.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'message' => 'required|string',
            'receiver_id' => 'nullable|exists:users,id'
        ]);

        $sender = auth()->user();
        $receiver_id = $request->receiver_id;

        if (!$receiver_id) {
            if ($sender->role === 'restaurant_admin') {
                $superAdmin = User::where('role', 'super_admin')->first(); 
                if ($superAdmin) {
                    $receiver_id = $superAdmin->id;
                }
            } 
            elseif ($sender->role === 'restaurant_user') {
                $receiver_id = $sender->parent_id; 
            }
        }

        if ($receiver_id) {
            ContactMessage::create([
                'sender_id'   => $sender->id,
                'receiver_id' => $receiver_id,
                'subject'     => $request->subject ?? 'Chat Message',
                'message'     => $request->message,
            ]);

            return redirect()->back()->with('success', 'Message Sent Successfully!');
        }

        return redirect()->back()->withErrors(['error' => 'Receiver not found.']);
    }

    // 1. Grouped Inbox: Har user ki sirf 1 latest entry dikhayega
    public function index()
    {
        $authId = auth()->id();

        // Unique conversation IDs identify karein
        $latestMessageIds = ContactMessage::selectRaw('MAX(id) as id')
            ->where(function ($q) use ($authId) {
                $q->where('sender_id', $authId)->orWhere('receiver_id', $authId);
            })
            ->groupByRaw('IF(sender_id = ?, receiver_id, sender_id)', [$authId])
            ->pluck('id');

        $messages = ContactMessage::whereIn('id', $latestMessageIds)
            ->with(['sender', 'receiver'])
            ->orderByDesc('created_at')
            ->get();

        return view('backend.contact.index', compact('messages'));
    }

    // 2. Chat Screen: Dedicated conversation history view
    public function chat($userId)
    {
        $authId = auth()->id();
        $chatUser = User::findOrFail($userId);

        // Sub Messages fetch karein
        $messages = ContactMessage::where(function ($q) use ($authId, $userId) {
                $q->where('sender_id', $authId)->where('receiver_id', $userId);
            })->orWhere(function ($q) use ($authId, $userId) {
                $q->where('sender_id', $userId)->where('receiver_id', $authId);
            })
            ->orderBy('created_at', 'asc')
            ->get();

        // Mark as read
        ContactMessage::where('sender_id', $userId)
            ->where('receiver_id', $authId)
            ->where('is_read', 0)
            ->update(['is_read' => 1]);

        return view('backend.contact.chat', compact('chatUser', 'messages'));
    }
}