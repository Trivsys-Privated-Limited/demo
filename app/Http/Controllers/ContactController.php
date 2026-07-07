<?php

namespace App\Http\Controllers;

use App\Models\ContactMessage;
use App\Models\User;
use Illuminate\Http\Request;

class ContactController extends Controller
{
    // Form dikhane ke liye
    public function create()
    {
        return view('backend.contact.create');
    }

    // Message database mein save karne ke liye
    public function store(Request $request)
    {
        $request->validate([
            'subject' => 'required|string|max:255',
            'message' => 'required|string',
        ]);

        $sender = auth()->user();
        $receiver_id = null;

        // Condition 1: Agar sender Restaurant Admin hai, toh message Super Admin ko jayega
        if ($sender->role === 'restaurant_admin') {
            // Pehla super_admin dhoondhein
            $superAdmin = User::where('role', 'super_admin')->first(); 
            if ($superAdmin) {
                $receiver_id = $superAdmin->id;
            }
        } 
        // Condition 2: Agar sender Restaurant Staff hai, toh message uske Restaurant Admin (parent_id) ko jayega[cite: 1]
        elseif ($sender->role === 'restaurant_user') {
            $receiver_id = $sender->parent_id; // parent_id mein restaurant admin ki ID save hai[cite: 1]
        }

        // Agar valid receiver mil gaya toh message save karein
        if ($receiver_id) {
            ContactMessage::create([
                'sender_id'   => $sender->id,
                'receiver_id' => $receiver_id,
                'subject'     => $request->subject,
                'message'     => $request->message,
            ]);

            return redirect()->back()->with('success', 'Your Message Successfully Submitted.');
        }

        return redirect()->back()->withErrors(['error' => 'Receiver not found.']);
    }

    // Aaye hue messages (Inbox) dikhane ke liye
    public function index()
    {
        // Sirf wahi messages dikhayein jo is user ko bheje gaye hain
        $messages = ContactMessage::where('receiver_id', auth()->id())
            ->orderByDesc('created_at')
            ->get();
// NAYI LINE: Jab user inbox open kare, toh uske tamam unread messages ko 'read' mark kar dein
        ContactMessage::where('receiver_id', auth()->id())
            ->where('is_read', 0)
            ->update(['is_read' => 1]);


        return view('backend.contact.index', compact('messages'));
    }
}