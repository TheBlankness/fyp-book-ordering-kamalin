<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Invoice PDF</title>
    <style>
        body { font-family: sans-serif; font-size: 12px; }
        table { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
        th, td { border: 1px solid #000; padding: 6px; }
        th { background-color: #f2f2f2; }
        h2, h3, h4 { margin-bottom: 5px; }
    </style>
</head>
<body>
    <h2>Invoice</h2>
    <p><strong>Invoice Number:</strong> {{ $invoice->invoice_number }}</p>
    <p><strong>Date:</strong> {{ $invoice->issue_date }}</p>

    @php
        $order = $invoice->customOrder ?? $invoice->reorder;
    @endphp

    <p><strong>Agent:</strong> {{ $order->agent->company_name ?? '—' }}</p>
    <p><strong>School:</strong> {{ $order->school->name ?? '—' }}</p>

    {{-- Order Items --}}
    <h4>Order Items</h4>
    <table>
        <thead>
            <tr>
                <th>Title</th>
                <th>Cover</th>
                <th>Quantity</th>
                <th>Price (RM)</th>
                <th>Total (RM)</th>
            </tr>
        </thead>
        <tbody>
            @php $subtotal = 0; @endphp
            @foreach($order->items as $item)
                @php
                    $line = $item->price * $item->quantity;
                    $subtotal += $line;
                @endphp
                <tr>
                    <td>{{ $item->title }}</td>
                    <td>{{ $item->cover }}</td>
                    <td>{{ $item->quantity }}</td>
                    <td>{{ number_format($item->price, 2) }}</td>
                    <td>{{ number_format($line, 2) }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    {{-- Additional Charges --}}
    @php
        $additionalCharges = $invoice->additional_charges ? json_decode($invoice->additional_charges, true) : [];
        $additionalTotal = 0;
    @endphp

    @if(count($additionalCharges))
        <h4>Additional Charges</h4>
        <table>
            <thead>
                <tr>
                    <th>Title</th>
                    <th>Type</th>
                    <th>Quantity</th>
                    <th>Price (RM)</th>
                    <th>Total (RM)</th>
                </tr>
            </thead>
            <tbody>
                @foreach($additionalCharges as $charge)
                    @php
                        $chargeTotal = $charge['price'] * $charge['quantity'];
                        $additionalTotal += $chargeTotal;
                    @endphp
                    <tr>
                        <td>{{ $charge['title'] }}</td>
                        <td>{{ $charge['type'] ?? '—' }}</td>
                        <td>{{ $charge['quantity'] }}</td>
                        <td>{{ number_format($charge['price'], 2) }}</td>
                        <td>{{ number_format($chargeTotal, 2) }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif

    {{-- Final Total Section --}}
    @php
        $deliveryFee = $order->delivery_fee ?? 0;
        $finalTotal = $subtotal + $additionalTotal + $deliveryFee;
    @endphp

    <table>
        <tr>
            <td colspan="4" style="text-align: right;"><strong>Subtotal (RM)</strong></td>
            <td>{{ number_format($subtotal + $additionalTotal, 2) }}</td>
        </tr>
        <tr>
            <td colspan="4" style="text-align: right;"><strong>Delivery Fee (RM)</strong></td>
            <td>{{ number_format($deliveryFee, 2) }}</td>
        </tr>
        <tr>
            <td colspan="4" style="text-align: right;"><strong>Total Amount (RM)</strong></td>
            <td><strong>RM{{ number_format($finalTotal, 2) }}</strong></td>
        </tr>
    </table>
</body>
</html>
