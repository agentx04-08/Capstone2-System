<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <title>Production Report</title>
    <style>
        body { font-family: DejaVu Sans, sans-serif; }
        table { width: 100%; border-collapse: collapse; }
        th, td { border: 1px solid #333; padding: 6px; font-size: 12px; }
        th { background: #eee; }
    </style>
</head>
<body>
    <h2>Production Report</h2>
    <table>
        <thead>
        <tr>
            <th>SKU</th>
            <th>Item</th>
            <th>Quantity</th>
            <th>Workstation</th>
            <th>Status</th>
            <th>Produced At</th>
        </tr>
        </thead>
        <tbody>
        @foreach($records as $r)
            <tr>
                <td>{{ $r->inventory->sku ?? '' }}</td>
                <td>{{ $r->inventory->name ?? '' }}</td>
                <td>{{ $r->quantity }}</td>
                <td>{{ $r->workstation }}</td>
                <td>{{ $r->status }}</td>
                <td>{{ $r->produced_at }}</td>
            </tr>
        @endforeach
        </tbody>
    </table>
</body>
</html>