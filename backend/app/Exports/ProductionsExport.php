<?php

namespace App\Exports;

use App\Models\Production;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class ProductionsExport implements FromCollection, WithHeadings
{
    public function collection()
    {
        return Production::with('inventory')
            ->get()
            ->map(function ($p) {
                return [
                    'SKU' => $p->inventory->sku ?? '',
                    'Item' => $p->inventory->name ?? '',
                    'Quantity' => $p->quantity,
                    'Workstation' => $p->workstation,
                    'Status' => $p->status,
                    'Produced At' => optional($p->produced_at)->toDateTimeString(),
                ];
            });
    }

    public function headings(): array
    {
        return ['SKU','Item','Quantity','Workstation','Status','Produced At'];
    }
}