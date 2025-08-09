<?php

namespace App\Exports;

use App\Models\Order;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class OrdersExport implements FromCollection, WithHeadings
{
    public function collection()
    {
        return Order::select('order_number','customer_name','customer_email','status','total_amount','created_at')->get();
    }

    public function headings(): array
    {
        return ['Order #','Customer Name','Customer Email','Status','Total Amount','Created At'];
    }
}