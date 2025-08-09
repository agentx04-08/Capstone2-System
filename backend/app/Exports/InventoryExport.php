<?php

namespace App\Exports;

use App\Models\Inventory;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class InventoryExport implements FromCollection, WithHeadings
{
    public function collection()
    {
        return Inventory::select('sku','name','unit','stock','reorder_level','lead_time_days','consumption_rate_per_day')->get();
    }

    public function headings(): array
    {
        return ['SKU','Name','Unit','Stock','Reorder Level','Lead Time (days)','Consumption Rate / day'];
    }
}