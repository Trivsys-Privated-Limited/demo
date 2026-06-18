<?php
namespace App\Http\Controllers;

use App\Models\order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use PDF;

class OrderController extends Controller
{
    public function index()
    {
        $orders = order::with(['table', 'item'])
            ->orderBy('order_number', 'desc')
            ->get()
            ->groupBy('order_number');

        return view('backend.order', compact('orders'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'table_id'         => 'required|exists:tables,id',
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
        $orders = order::with(['table', 'item'])
            ->orderBy('order_number', 'desc')
            ->get()
            ->groupBy('order_number');

        if (request()->ajax()) {
            return response()->json($orders);
        }

        return view('backend.kichan', compact('orders'));
    }

    public function updateStatus(Request $request)
    {
        $request->validate([
            'order_number' => 'required|exists:orders,order_number',
            'status'       => 'required|in:pending,preparing,served,cancelled',
        ]);

        order::where('order_number', $request->order_number)
            ->update(['status' => $request->status]);

        return response()->json(['success' => true]);
    }

    public function show($orderNumber)
    {
        $orders = order::with('item', 'table')
            ->where('order_number', $orderNumber)
            ->get();
        return response()->json($orders);
    }

    public function invoice($order)
    {
        $orders = order::with(['table', 'item'])
            ->where('order_number', $order)
            ->get();
        if ($orders->isEmpty()) {
            abort(404, 'Order not found');
        }

        $pdf = Pdf::loadView('backend.invoice', compact('orders'));
        return $pdf->stream("Invoice_{$order}.pdf");
    }
}
