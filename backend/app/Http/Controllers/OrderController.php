<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Inventory;
use Illuminate\Http\Request;
use App\Exports\OrdersExport;
use Maatwebsite\Excel\Facades\Excel;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    public function index()
    {
        return Order::with('items.inventory')->orderByDesc('created_at')->get();
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'customer_name' => 'required|string',
            'customer_email' => 'required|email',
            'items' => 'required|array|min:1',
            'items.*.inventory_id' => 'required|exists:inventories,id',
            'items.*.quantity' => 'required|numeric|min:1',
            'items.*.unit_price' => 'required|numeric|min:0'
        ]);

        return DB::transaction(function () use ($data) {
            $order = Order::create([
                'order_number' => Str::upper(Str::random(8)),
                'customer_name' => $data['customer_name'],
                'customer_email' => $data['customer_email'],
                'status' => 'pending',
                'total_amount' => 0
            ]);

            $total = 0;
            foreach ($data['items'] as $item) {
                $subtotal = $item['quantity'] * $item['unit_price'];
                $total += $subtotal;
                OrderItem::create([
                    'order_id' => $order->id,
                    'inventory_id' => $item['inventory_id'],
                    'quantity' => $item['quantity'],
                    'unit_price' => $item['unit_price'],
                    'subtotal' => $subtotal,
                ]);
            }

            $order->update(['total_amount' => $total]);
            return $order->load('items.inventory');
        });
    }

    public function storePublic(Request $request)
    {
        return $this->store($request);
    }

    public function show(Order $order)
    {
        return $order->load('items.inventory');
    }

    public function update(Request $request, Order $order)
    {
        $data = $request->validate([
            'status' => 'sometimes|in:pending,processing,completed,cancelled'
        ]);
        $order->update($data);
        return $order->load('items.inventory');
    }

    public function destroy(Order $order)
    {
        $order->delete();
        return response()->json(['message' => 'Deleted']);
    }

    public function addItem(Request $request, Order $order)
    {
        $data = $request->validate([
            'inventory_id' => 'required|exists:inventories,id',
            'quantity' => 'required|numeric|min:1',
            'unit_price' => 'required|numeric|min:0'
        ]);

        $subtotal = $data['quantity'] * $data['unit_price'];
        $item = OrderItem::create([
            'order_id' => $order->id,
            'inventory_id' => $data['inventory_id'],
            'quantity' => $data['quantity'],
            'unit_price' => $data['unit_price'],
            'subtotal' => $subtotal,
        ]);

        $order->update(['total_amount' => $order->total_amount + $subtotal]);

        return $order->load('items.inventory');
    }

    public function track(Order $order)
    {
        return [
            'order_number' => $order->order_number,
            'status' => $order->status,
            'updated_at' => $order->updated_at,
        ];
    }

    public function exportExcel()
    {
        return Excel::download(new OrdersExport, 'orders.xlsx');
    }

    public function exportPdf()
    {
        $orders = Order::with('items.inventory')->orderByDesc('created_at')->get();
        $pdf = Pdf::loadView('reports.orders', compact('orders'));
        return $pdf->download('orders.pdf');
    }
}