<?php

namespace App\Http\Controllers;

use App\Models\Inventory;
use Illuminate\Http\Request;
use App\Services\AnalyticsService;
use App\Exports\InventoryExport;
use Maatwebsite\Excel\Facades\Excel;
use Barryvdh\DomPDF\Facade\Pdf;

class InventoryController extends Controller
{
    public function index()
    {
        return Inventory::orderBy('name')->get();
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'sku' => 'required|string|unique:inventories,sku',
            'name' => 'required|string',
            'unit' => 'required|string',
            'stock' => 'required|numeric',
            'reorder_level' => 'required|numeric',
            'lead_time_days' => 'required|numeric',
            'consumption_rate_per_day' => 'required|numeric'
        ]);

        return Inventory::create($data);
    }

    public function show(Inventory $inventory)
    {
        return $inventory;
    }

    public function update(Request $request, Inventory $inventory)
    {
        $data = $request->validate([
            'name' => 'sometimes|string',
            'unit' => 'sometimes|string',
            'stock' => 'sometimes|numeric',
            'reorder_level' => 'sometimes|numeric',
            'lead_time_days' => 'sometimes|numeric',
            'consumption_rate_per_day' => 'sometimes|numeric'
        ]);

        $inventory->update($data);
        return $inventory;
    }

    public function destroy(Inventory $inventory)
    {
        $inventory->delete();
        return response()->json(['message' => 'Deleted']);
    }

    public function forecast(Inventory $inventory, AnalyticsService $analyticsService)
    {
        $forecast = $analyticsService->forecastInventory($inventory);
        return response()->json($forecast);
    }

    public function exportExcel()
    {
        return Excel::download(new InventoryExport, 'inventory.xlsx');
    }

    public function exportPdf()
    {
        $items = Inventory::orderBy('name')->get();
        $pdf = Pdf::loadView('reports.inventory', compact('items'));
        return $pdf->download('inventory.pdf');
    }
}