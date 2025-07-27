<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Design File PDF</title>
    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
            padding: 40px;
            font-size: 14px;
        }

        h1 {
            text-align: center;
            font-size: 22px;
            margin-bottom: 30px;
        }

        .section {
            margin-bottom: 30px;
            text-align: center;
        }

        .label {
            font-weight: bold;
        }

        .image-container {
            border: 1px solid #ccc;
            padding: 20px;
            text-align: center;
        }

        .image-container img {
            width: 100%;
            max-width: 400px; /* Shrink to safe size */
            height: auto;
            display: inline-block;
            object-fit: contain;
        }
    </style>
</head>
<body>

    <h1>Final Book Design</h1>

    <div class="section">
        <p><span class="label">School Name:</span> {{ $order->school->name ?? '-' }}</p>
    </div>

    <div class="image-container">
        @if ($order->design_file)
            <img src="{{ public_path('storage/' . $order->design_file) }}" alt="Design File">
        @else
            <p>No design uploaded.</p>
        @endif
    </div>

</body>
</html>
