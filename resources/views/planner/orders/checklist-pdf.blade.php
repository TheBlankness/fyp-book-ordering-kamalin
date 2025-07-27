<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Order Checklist</title>
    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 13px;
            margin: 30px;
        }

        h2 {
            text-align: center;
            margin-bottom: 30px;
        }

        p {
            margin: 5px 0;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 25px;
        }

        th, td {
            border: 1px solid #000;
            padding: 8px;
            vertical-align: top;
        }

        th {
            background-color: #f0f0f0;
            text-align: left;
        }

        small {
            color: #555;
        }
    </style>
</head>
<body>
    <h2>Production Checklist</h2>

    <p><strong>Order ID:</strong> {{ $order->id }}</p>
    <p><strong>Type:</strong> {{ ucfirst($type) }}</p>
    <p><strong>School:</strong> {{ $order->school->name ?? '-' }}</p>
    <p><strong>Agent:</strong> {{ $order->agent->company_name ?? '-' }}</p>
    <p><strong>Production Date:</strong> {{ $order->production_date ?? '-' }}</p>

    <table>
        <thead>
            <tr>
                <th>Book Title</th>
                <th>Quantity</th>
                <th>✓</th>
            </tr>
        </thead>
        <tbody>
        @foreach ($order->items as $item)
            <tr>
                <td>
                    <strong>{{ $item->book['bookType'] ?? $item->title ?? '-' }}</strong><br>
                    <small>Cover: {{ $item->book['coverType'] ?? $item->cover ?? '-' }}</small>
                </td>
                <td>{{ $item->quantity }}</td>
                <td>☐</td>
            </tr>
        @endforeach
        </tbody>
    </table>
</body>
</html>
