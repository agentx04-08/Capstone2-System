<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Inventory;
use App\Models\Production;
use Carbon\Carbon;

class ProductionSeeder extends Seeder
{
    public function run(): void
    {
        $inv = Inventory::first();
        if (! $inv) return;

        Production::create([
            'inventory_id' => $inv->id,
            'quantity' => 50,
            'workstation' => 'Cutting Station',
            'status' => 'completed',
            'produced_at' => Carbon::now()->subDay(),
        ]);

        Production::create([
            'inventory_id' => $inv->id,
            'quantity' => 30,
            'workstation' => 'Finishing Station',
            'status' => 'processing',
            'produced_at' => Carbon::now(),
        ]);
    }
}