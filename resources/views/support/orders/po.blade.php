<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Purchase Order</title>
    <style>
        body {
            font-family: sans-serif;
            font-size: 12px;
        }
        .header, .footer {
            text-align: center;
        }
        .company-info {
            margin-bottom: 10px;
            text-align: left;
        }
        .company-info h2 {
            margin: 0;
            font-size: 16px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
        }
        th, td {
            border: 1px solid #000;
            padding: 6px;
            text-align: center;
        }
        .no-border {
            border: none !important;
        }
        .text-right {
            text-align: right;
        }
    </style>
</head>
<body>

    <div class="header">
        <h2>Tan Eng Hong Paper & Stationery Sdn. Bhd.</h2>
        <div class="company-info">
            <p>Lot 266, Tingkat Perusahaan 6, Prai Industrial Estate,</p>
            <p>13600 Perai, Pulau Pinang</p>
            <p>GST Reg. No.: 0020140143616 | Company No.: 95222-A</p>
        </div>
        <h3><u>PURCHASE ORDER</u></h3>
    </div>

    <table>
        <tr>
            <td class="no-border"><strong>Supplier:</strong> Tan Eng Hong</td>
            <td class="no-border"><strong>Date:</strong> {{ now()->format('d M Y') }}</td>
        </tr>
        <tr>
            <td class="no-border"><strong>School:</strong> {{ $order->school->name ?? '-' }}</td>
            <td class="no-border"><strong>Agent:</strong> {{ $order->agent->company_name ?? '-' }}</td>
        </tr>
    </table>

    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Description</th>
                <th>No. Buku</th>
                <th>Jenis Cover</th>
                <th>Bil. M/S</th>
                <th>Warna</th>
                <th>GSM(g)</th>
                <th>Unit Price</th>
                <th>Order Quantity</th>
                <th>Amount</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($order->items as $index => $item)
                @php $book = $item->book; @endphp
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $book->bookType }}</td>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $book->coverType }}</td>
                    <td>{{ $book->pageNumber }}</td>
                    <td>{{ $book->color }}</td>
                    <td>{{ $book->gsm }}</td>
                    <td>RM {{ number_format($book->price, 2) }}</td>
                    <td>{{ $item->quantity }}</td>
                    <td>RM {{ number_format($book->price * $item->quantity, 2) }}</td>
                </tr>
            @endforeach
            @for ($i = count($order->items); $i < 15; $i++)
                <tr>
                    <td>{{ $i + 1 }}</td>
                    <td colspan="9">&nbsp;</td>
                </tr>
            @endfor
        </tbody>
        <tfoot>
            <tr>
                <td colspan="9" class="text-right"><strong>Delivery Fee</strong></td>
                <td><strong>RM {{ number_format($order->delivery_fee ?? 0, 2) }}</strong></td>
            </tr>
            <tr>
                <td colspan="8" class="text-right"><strong>Total Amount</strong></td>
                <td><strong>{{ $order->items->sum('quantity') }} BKS</strong></td>
                <td><strong>RM {{ number_format($order->total_amount, 2) }}</strong></td>
            </tr>
        </tfoot>
    </table>

    <br><br>
    <table>
        <tr>
            <td class="no-border"><strong>Issued By:</strong> {{ $order->issuedBy?->name ?? '___________________' }}</td>
        </tr>
    </table>

</body>
</html>
