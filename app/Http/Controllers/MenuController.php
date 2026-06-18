<?php

namespace App\Http\Controllers;

use App\Models\table;
use App\Models\Guest;
use App\Models\item;
use App\Models\order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cookie; // <--- Yeh nayi line add karein


class MenuController extends Controller
{
    // 1. Shows the form asking for Name & Phone
    public function showGuestForm($qr_token) 
    {
        $table = Table::where('qr_token', $qr_token)->firstOrFail();

        // Testing ke liye aapne isko comment kiya hua hai, jo ke temporary testing ke liye bilkul theek hai.
         if (session()->has('guest_registered_' . $qr_token)) {
            return redirect()->route('menu.view', $qr_token);
        }
        
        return view('frontend.guestform', compact('table'));
    }

    // 2. Saves data to database and redirects
    public function registerGuest(Request $request, $qr_token) {
        /*$request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
        ]);*/
        $request->validate([
        'name' => 'required|string|max:255',
        'phone' => [
            'required',
            'string',
            'regex:/^((\+92)|(0))?3[0-9]{9}$/' // Yahan badlao kiya hy
        ],
    ], [
        // Custom Error Message (Urdu ya English jaisa aap chahein)
        'phone.regex' => 'Please Enter a Valid Pakistani Phone Number Format (e.g., 03001234567 ya +923001234567).',
    ]);

        $table = Table::where('qr_token', $qr_token)->firstOrFail();

        // Save Guest Record in Database
        Guest::create([
            'table_id' => $table->id,
            'name' => $request->name,
            'phone' => $request->phone,
        ]);

        // Save A flag in the Session so they don't have to fill it out again
        session(['guest_registered_' . $qr_token => true]);

        return redirect()->route('menu.view', $qr_token);
    }

    // 3. The actual menu page
    public function ShowMenu($qr_token)
    {
        $table = Table::where('qr_token', $qr_token)->firstOrFail();

        // Security Check: Agar user bina form bhare direct view page par aaye toh form par bhejo
        if(!session()->has('guest_registered_' . $qr_token)) {
            return redirect()->route('menu.guest', $qr_token);
        }

        $menuItems = item::where('user_id', $table->user_id)
            ->where('status', 'active')
            ->get();

        $activeOrder = order::where('table_id', $table->id)
            ->whereNotIn('status', ['served', 'cancelled'])
            ->latest()
            ->first();

        $activeOrderNumber = $activeOrder ? $activeOrder->order_number : null;
        $guest = Guest::where('table_id', $table->id)
            ->latest()
            ->first();

        return view('frontend.menu', compact('table', 'menuItems', 'activeOrderNumber', 'guest'));
    }
}