<?php
namespace App\Http\Controllers;

use App\Models\Guest;
use App\Models\order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;
use PDF;

class OrderController extends Controller
{
    public function index()
    {
        $orders = order::with(['table', 'item'])
            ->orderBy('order_number', 'desc')
            ->get()
            ->groupBy('order_number')
            ->sortKeysDesc();

        return view('backend.order', compact('orders'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'table_id'         => 'required|exists:tables,id',
            'guest_id'         => 'nullable|exists:guests,id',
            'order_number'     => 'nullable|integer',
            'items'            => 'required|array',
            'items.*.item_id'  => 'required|exists:items,id',
            'items.*.quantity' => 'required|integer|min:1',
            'items.*.total'    => 'required|integer|min:0',
        ]);

        DB::beginTransaction();
        try {
            $orderNumber = $request->order_number;
            $orderStatus = 'pending';

            if ($orderNumber) {
                // Check if active order exists for this table
                $existingOrder = order::where('order_number', $orderNumber)
                    ->where('table_id', $request->table_id)
                    ->whereNotIn('status', ['served', 'cancelled'])
                    ->first();
                
                if ($existingOrder) {
                    $orderStatus = $existingOrder->status;
                    if ($request->guest_id) {
                        order::where('order_number', $orderNumber)
                            ->update(['guest_id' => $request->guest_id]);
                    }
                } else {
                    $orderNumber = null;
                }
            }

            if (!$orderNumber) {
                $maxOrderNumber = DB::table('orders')->lockForUpdate()->selectRaw('MAX(CAST(order_number AS UNSIGNED)) as max_num')->value('max_num');
                $orderNumber = ($maxOrderNumber ?? 0) + 1;
            }

            foreach ($request->items as $item) {
                // Check if this item is already in the order
                $existingItem = order::where('order_number', $orderNumber)
                    ->where('item_id', $item['item_id'])
                    ->first();

                if ($existingItem) {
                    // Update quantity and total
                    $existingItem->increment('quantity', $item['quantity']);
                    $existingItem->increment('total', $item['total']);

                    if ($request->note && $request->note !== 'No text' && $request->note !== 'No note') {
                        $newNote = ($existingItem->note && $existingItem->note !== 'No text') ? ($existingItem->note . ', ' . $request->note) : $request->note;
                        $existingItem->update(['note' => $newNote]);
                    }
                } else {
                    // Create new row
                    order::create([
                        'user_id'      => Auth::id() ?: 1,
                        'table_id'     => $request->table_id,
                        'guest_id'     => $request->guest_id,
                        'item_id'      => $item['item_id'],
                        'quantity'     => $item['quantity'],
                        'total'        => $item['total'],
                        'note'         => $request->note ?? 'No text',
                        'order_number' => $orderNumber,
                        'status'       => $orderStatus,
                    ]);
                }
            }

            DB::commit();

            return response()->json([
                'success'      => true,
                'order_number' => $orderNumber,
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    public function kitchen()
    {
        $orders = order::with(['table', 'item', 'guest'])
            ->orderBy('order_number', 'desc')
            ->get()
            ->groupBy('order_number');

        if (request()->ajax()) {
            return response()->json($orders);
        }

        return view('backend.kitchen', compact('orders'));
    }

    public function updateStatus(Request $request)
    {
        $request->validate([
            'order_number' => 'required|exists:orders,order_number',
            'status'       => 'required|in:pending,preparing,served,cancelled',
            'served_at'    => 'sometimes|nullable|date',
            'delivered_at' => 'sometimes|nullable|date',
            'preparation_minutes' => 'sometimes|nullable|integer|min:0',
        ]);

        $update = ['status' => $request->status];

        if ($request->filled('served_at')) {
            $update['served_at'] = Carbon::parse($request->served_at);
        }

        if ($request->filled('delivered_at')) {
            $update['delivered_at'] = Carbon::parse($request->delivered_at);
        }

        if ($request->filled('preparation_minutes')) {
            $update['preparation_minutes'] = (int) $request->preparation_minutes;
        }

        order::where('order_number', $request->order_number)->update($update);

        // Fetch one row to return updated timestamps and status
        $updatedOrder = order::where('order_number', $request->order_number)->first();

        return response()->json([
            'success' => true,
            'order_number' => $request->order_number,
            'status' => $updatedOrder ? $updatedOrder->status : $request->status,
            'served_at' => $updatedOrder && $updatedOrder->served_at ? $updatedOrder->served_at->toIso8601String() : null,
            'delivered_at' => $updatedOrder && $updatedOrder->delivered_at ? $updatedOrder->delivered_at->toIso8601String() : null,
            'preparation_minutes' => $updatedOrder ? $updatedOrder->preparation_minutes : null,
        ]);
    }

    public function show($order)
    {
        $orders = order::with(['item', 'table', 'guest'])
            ->where('order_number', $order)
            ->get();
        return response()->json($orders);
    }

   /* public function invoice($order)
    {
        $orders = order::with(['table', 'item', 'guest'])
            ->where('order_number', $order)
            ->get();
        if ($orders->isEmpty()) {
            abort(404, 'Order not found');
        }

        $pdf = Pdf::loadView('backend.invoice', compact('orders'));
        return $pdf->stream("Invoice_{$order}.pdf");
    } */

   public function invoice($order)
    {
        $orders = order::with(['table', 'item', 'guest'])
            ->where('order_number', $order)
            ->get();
            
        if ($orders->isEmpty()) {
            abort(404, 'Order not found');
        }

        // 226.8 points = 80mm (Standard thermal printer size)
        $customPaper = array(0, 0, 226.8, 650.00);

        $pdf = Pdf::loadView('backend.invoice', compact('orders'))
                  ->setPaper($customPaper);

        return $pdf->stream("Invoice_{$order}.pdf");
    }
}
