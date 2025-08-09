<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Inventory;
use Illuminate\Support\Str;

class OrderSeeder extends Seeder
{
    public function run(): void
    {
        $inv = Inventory::first();
        if (! $inv) return;

        $order = Order::create([
            'order_number' => Str::upper(Str::random(8)),
            'customer_name' => 'Acme Furniture',
            'customer_email' => 'buyer@acme.com',
            'status' => 'processing',
            'total_amount' => 0
        ]);

        $item = OrderItem::create([
            'order_id' => $order->id,
            'inventory_id' => $inv->id,
            'quantity' => 10,
            'unit_price' => 25.5,
            'subtotal' => 255,
        ]);

        $order->update(['total_amount' => 255]);
    }
}