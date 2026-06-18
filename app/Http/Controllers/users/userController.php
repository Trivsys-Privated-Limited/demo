<?php
namespace App\Http\Controllers\users;

use App\Http\Controllers\Controller;
use App\Models\item;
use App\Models\table;
use App\Models\order;
use App\Models\User;
use Illuminate\Http\Request;

class userController extends Controller
{
    public function index()
    {
        $allusers = User::all();
        return view('backend.user.manage_user', compact('allusers'));
    }

    public function create()
    {
        return view('backend.user.add_user');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'     => 'required',
            'email'    => 'required|email|unique:users,email',
            'phone'    => 'required|unique:users,phone',
            'password' => 'required|min:6',
            'status'   => 'required',
        ]);

        User::create([
            'name'           => $request->name,
            'email'          => $request->email,
            'phone'          => $request->phone,
            'password'       => $request->password,
            'bussiness_name' => $request->business_name,
            'status'         => $request->status,
            'role'           => $request->role,
        ]);

        return redirect()->route('users.index')->with('success', 'User created successfully.');
    }

    public function edit($id)
    {
        $user = User::findOrFail($id);
        return view('backend.user.edit', compact('user'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name'   => 'required',
            'email'  => 'required',
            'phone'  => 'required',
            'status' => 'required',
        ]);

        $user = User::findOrFail($id);
        $user->update([
            'name'           => $request->name,
            'email'          => $request->email,
            'phone'          => $request->phone,
            'bussiness_name' => $request->business_name,
            'status'         => $request->status,
            'role'           => $request->role,
        ]);

        return redirect()->route('users.index')->with('success', 'User updated successfully.');
    }

    public function destroy($id)
    {
        $user = User::findOrFail($id);
        $user->delete();

        return redirect()->route('users.index')->with('success', 'User deleted successfully.');
    }

    public function menu($token)
    {
        $table = table::where('qr_token', $token)->firstOrFail();

        $restaurant = $table->user_id;

        $menuItems = item::where('user_id', $restaurant)
            ->where('status', 'active')
            ->get();

        $activeOrder = order::where('table_id', $table->id)
            ->whereNotIn('status', ['served', 'cancelled'])
            ->latest()
            ->first();
        $activeOrderNumber = $activeOrder ? $activeOrder->order_number : null;

        return view('frontend.menu', compact('table', 'menuItems', 'activeOrderNumber'));
    }

    public function checkOrderStatus(Request $request, $token)
    {
        $table = table::where('qr_token', $token)->first();
        if (!$table) {
            return response()->json(['success' => false, 'message' => 'Table not found']);
        }

        $orderNumber = $request->query('order_number');

        // 1. Agar specific order number diya gaya hai
        if ($orderNumber) {
            $order = order::where('order_number', $orderNumber)
                ->where('table_id', $table->id)
                ->latest()
                ->first();
        } else {
            // 2. Warna purana logic (Active order dhoondo)
            $order = order::where('table_id', $table->id)
                ->whereNotIn('status', ['served', 'cancelled'])
                ->latest()
                ->first();
        }

        // 2. Agar koi active nahi, to aakhri Served order dikhao
        if (!$order) {
            $order = order::where('table_id', $table->id)
                ->where('status', 'served')
                ->where('created_at', '>=', now()->subHours(2))
                ->latest()
                ->first();
        }

        // 3. Fallback: Agar kuch bhi nahi, to check karo kya koi cancelled hai (sirf info ke liye)
        if (!$order) {
            $order = order::where('table_id', $table->id)
                ->where('created_at', '>=', now()->subHours(1))
                ->latest()
                ->first();
        }

        if (!$order) {
            return response()->json(['success' => false, 'message' => 'No active order']);
        }

        $orderItems = order::with('item')
            ->where('order_number', $order->order_number)
            ->get();

        return response()->json([
            'success'      => true,
            'status'       => $order->status,
            'order_number' => $order->order_number,
            'created_at'   => $order->created_at->toIso8601String(),
            'server_time'  => now()->toIso8601String(),
            'items'        => $orderItems->map(fn($oi) => [
                'name' => $oi->item->name,
                'qty'  => $oi->quantity
            ])
        ]);
    }
}
