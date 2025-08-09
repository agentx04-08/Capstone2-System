<?php

namespace App\Services;

use App\Models\Inventory;
use App\Models\Production;
use App\Models\OrderItem;
use Carbon\Carbon;
use Illuminate\Support\Collection;

class AnalyticsService
{
    public function forecastInventory(Inventory $inventory, int $windowDays = 14): array
    {
        // Fetch recent consumption from order items
        $since = Carbon::now()->subDays($windowDays);
        $consumed = OrderItem::where('inventory_id', $inventory->id)
            ->where('created_at', '>=', $since)
            ->sum('quantity');

        $avgDailyConsumption = $windowDays > 0 ? $consumed / $windowDays : 0;
        $avgDailyConsumption = max($avgDailyConsumption, (float) $inventory->consumption_rate_per_day);

        $daysUntilStockout = $avgDailyConsumption > 0 ? $inventory->stock / $avgDailyConsumption : INF;
        $shouldReorder = $inventory->stock <= $inventory->reorder_level;
        $suggestedReorderQty = $avgDailyConsumption * ($inventory->lead_time_days + 7); // lead time + 1 week safety

        return [
            'avg_daily_consumption' => round($avgDailyConsumption, 2),
            'days_until_stockout' => is_infinite($daysUntilStockout) ? null : round($daysUntilStockout, 1),
            'should_reorder' => $shouldReorder,
            'suggested_reorder_qty' => (int) ceil($suggestedReorderQty),
        ];
    }

    public function forecastProductionSchedule(int $horizonDays = 14): array
    {
        $today = Carbon::today();
        $schedule = [];

        $inventories = Inventory::orderBy('name')->get();
        foreach ($inventories as $inv) {
            $forecast = $this->forecastInventory($inv);
            $days = $forecast['days_until_stockout'] ?? 30;
            $planDate = $today->copy()->addDays(max(0, (int) ceil($days - $inv->lead_time_days)));

            $schedule[] = [
                'inventory_id' => $inv->id,
                'sku' => $inv->sku,
                'name' => $inv->name,
                'planned_date' => $planDate->toDateString(),
                'suggested_qty' => $forecast['suggested_reorder_qty'],
            ];
        }

        return $schedule;
    }
}