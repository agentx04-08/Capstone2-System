<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Inventory;

class InventorySeeder extends Seeder
{
    public function run(): void
    {
        $items = [
            ['sku' => 'WD-PLANK-001', 'name' => 'Mahogany Plank', 'unit' => 'pcs', 'stock' => 500, 'reorder_level' => 150, 'lead_time_days' => 7, 'consumption_rate_per_day' => 20],
            ['sku' => 'WD-PLANK-002', 'name' => 'Oak Plank', 'unit' => 'pcs', 'stock' => 400, 'reorder_level' => 120, 'lead_time_days' => 10, 'consumption_rate_per_day' => 18],
            ['sku' => 'WD-VARN-001', 'name' => 'Clear Varnish 1L', 'unit' => 'btl', 'stock' => 200, 'reorder_level' => 50, 'lead_time_days' => 5, 'consumption_rate_per_day' => 8],
            ['sku' => 'WD-NAIL-001', 'name' => 'Wood Nails 2"', 'unit' => 'box', 'stock' => 150, 'reorder_level' => 40, 'lead_time_days' => 3, 'consumption_rate_per_day' => 5],
        ];

        foreach ($items as $item) {
            Inventory::create($item);
        }
    }
}