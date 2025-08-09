<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <title>Inventory Report</title>
    <style>
        body { font-family: DejaVu Sans, sans-serif; }
        table { width: 100%; border-collapse: collapse; }
        th, td { border: 1px solid #333; padding: 6px; font-size: 12px; }
        th { background: #eee; }
    </style>
</head>
<body>
    <h2>Inventory Report</h2>
    <table>
        <thead>
        <tr>
            <th>SKU</th>
            <th>Name</th>
            <th>Unit</th>
            <th>Stock</th>
            <th>Reorder Level</th>
            <th>Lead Time (days)</th>
            <th>Consumption / day</th>
        </tr>
        </thead>
        <tbody>
        @foreach($items as $item)
            <tr>
                <td>{{ $item->sku }}</td>
                <td>{{ $item->name }}</td>
                <td>{{ $item->unit }}</td>
                <td>{{ $item->stock }}</td>
                <td>{{ $item->reorder_level }}</td>
                <td>{{ $item->lead_time_days }}</td>
                <td>{{ $item->consumption_rate_per_day }}</td>
            </tr>
        @endforeach
        </tbody>
    </table>
</body>
</html>