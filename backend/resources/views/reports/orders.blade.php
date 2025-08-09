<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <title>Orders Report</title>
    <style>
        body { font-family: DejaVu Sans, sans-serif; }
        table { width: 100%; border-collapse: collapse; }
        th, td { border: 1px solid #333; padding: 6px; font-size: 12px; }
        th { background: #eee; }
    </style>
</head>
<body>
    <h2>Orders Report</h2>
    <table>
        <thead>
        <tr>
            <th>Order #</th>
            <th>Customer</th>
            <th>Email</th>
            <th>Status</th>
            <th>Total</th>
            <th>Created At</th>
        </tr>
        </thead>
        <tbody>
        @foreach($orders as $order)
            <tr>
                <td>{{ $order->order_number }}</td>
                <td>{{ $order->customer_name }}</td>
                <td>{{ $order->customer_email }}</td>
                <td>{{ $order->status }}</td>
                <td>{{ number_format($order->total_amount, 2) }}</td>
                <td>{{ $order->created_at }}</td>
            </tr>
        @endforeach
        </tbody>
    </table>
</body>
</html>