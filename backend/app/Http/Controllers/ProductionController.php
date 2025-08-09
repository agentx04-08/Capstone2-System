<?php

namespace App\Http\Controllers;

use App\Models\Production;
use App\Models\Inventory;
use Illuminate\Http\Request;
use App\Services\AnalyticsService;
use App\Exports\ProductionsExport;
use Maatwebsite\Excel\Facades\Excel;
use Barryvdh\DomPDF\Facade\Pdf;

class ProductionController extends Controller
{
    public function index()
    {
        return Production::with('inventory')->orderByDesc('produced_at')->get();
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'inventory_id' => 'required|exists:inventories,id',
            'quantity' => 'required|numeric|min:1',
            'workstation' => 'required|string',
            'status' => 'required|in:queued,processing,completed',
            'produced_at' => 'required|date'
        ]);

        return Production::create($data);
    }

    public function show(Production $production)
    {
        return $production->load('inventory');
    }

    public function update(Request $request, Production $production)
    {
        $data = $request->validate([
            'quantity' => 'sometimes|numeric|min:1',
            'workstation' => 'sometimes|string',
            'status' => 'sometimes|in:queued,processing,completed',
            'produced_at' => 'sometimes|date'
        ]);

        $production->update($data);
        return $production->load('inventory');
    }

    public function destroy(Production $production)
    {
        $production->delete();
        return response()->json(['message' => 'Deleted']);
    }

    public function forecastSchedule(AnalyticsService $analyticsService)
    {
        $schedule = $analyticsService->forecastProductionSchedule();
        return response()->json($schedule);
    }

    public function exportExcel()
    {
        return Excel::download(new ProductionsExport, 'productions.xlsx');
    }

    public function exportPdf()
    {
        $records = Production::with('inventory')->orderByDesc('produced_at')->get();
        $pdf = Pdf::loadView('reports.productions', compact('records'));
        return $pdf->download('productions.pdf');
    }
}