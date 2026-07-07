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

    public function editPassword($id)
    {
        $user = User::findOrFail($id);
        return view('backend.user.edit_password', compact('user'));
    }

    public function updatePassword(Request $request, $id)
    {
        $request->validate([
            'password' => 'required|min:6|confirmed',
        ]);

        $user = User::findOrFail($id);
        $user->update([
            'password' => $request->password,
        ]);

        return redirect()->route('users.index')->with('success', 'User password updated successfully.');
    }

    /**
     * ============================================
     * STAFF MANAGEMENT (For Restaurant Admin)
     * ============================================
     */
    public function staffIndex()
    {
        $restaurantId = auth()->id();
        $staff = User::where('parent_id', $restaurantId)
            ->where('role', 'restaurant_user')
            ->orderByDesc('created_at')
            ->get();

        return view('backend.staff.index', compact('staff'));
    }

    public function staffCreate()
    {
        return view('backend.staff.create');
    }

    public function staffStore(Request $request)
    {
        $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'required|email|unique:users,email',
            'phone'    => 'required|unique:users,phone',
            'password' => 'required|min:6',
        ]);

        User::create([
            'name'      => $request->name,
            'email'     => $request->email,
            'phone'     => $request->phone,
            'password'  => $request->password,
            'role'      => 'restaurant_user',
            'parent_id' => auth()->id(),
            'status'    => 'active',
        ]);

        return redirect()->route('staff.index')->with('success', 'Staff member added successfully.');
    }

    public function staffEdit($id)
    {
        $staff = User::where('parent_id', auth()->id())
            ->where('role', 'restaurant_user')
            ->findOrFail($id);

        return view('backend.staff.edit', compact('staff'));
    }

    public function staffUpdate(Request $request, $id)
    {
        $request->validate([
            'name'   => 'required|string|max:255',
            'email'  => 'required|email|unique:users,email,' . $id,
            'phone'  => 'required|unique:users,phone,' . $id,
        ]);

        $staff = User::where('parent_id', auth()->id())
            ->where('role', 'restaurant_user')
            ->findOrFail($id);

        $staff->update([
            'name'   => $request->name,
            'email'  => $request->email,
            'phone'  => $request->phone,
        ]);

        return redirect()->route('staff.index')->with('success', 'Staff member updated successfully.');
    }

    public function staffDestroy($id)
    {
        $staff = User::where('parent_id', auth()->id())
            ->where('role', 'restaurant_user')
            ->findOrFail($id);

        $staff->delete();

        return redirect()->route('staff.index')->with('success', 'Staff member deleted successfully.');
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
            'served_at'    => $order->served_at ? $order->served_at->toIso8601String() : null,
            'delivered_at' => $order->delivered_at ? $order->delivered_at->toIso8601String() : null,
            'preparation_minutes' => $order->preparation_minutes ?? null,
            'estimated_finish_at' => (function() use ($order) {
                // Priority: delivered_at > served_at > preparation_minutes > null
                if ($order->delivered_at) return $order->delivered_at->toIso8601String();
                if ($order->served_at) return $order->served_at->toIso8601String();
                if ($order->preparation_minutes) return $order->created_at->copy()->addMinutes($order->preparation_minutes)->toIso8601String();
                return null;
            })(),
            'items'        => $orderItems->map(fn($oi) => [
                'name' => $oi->item->name,
                'qty'  => $oi->quantity
            ])
        ]);
    }
}
