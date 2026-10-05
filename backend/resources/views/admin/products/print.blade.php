<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Products Catalog Report - Amar Bangla</title>
    <style>
        body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; margin: 20px; color: #1e293b; font-size: 11px; }
        .header { border-bottom: 2px solid #2563eb; padding-bottom: 12px; margin-bottom: 16px; display: flex; justify-content: space-between; align-items: flex-end; }
        h1 { margin: 0; font-size: 20px; color: #1e293b; font-weight: 800; }
        .meta { color: #64748b; font-size: 10px; margin-top: 4px; }
        table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        th { background: #f8fafc; color: #475569; font-weight: 700; text-transform: uppercase; font-size: 9px; letter-spacing: 0.5px; border: 1px solid #e2e8f0; padding: 7px 8px; text-align: left; }
        td { border: 1px solid #e2e8f0; padding: 6px 8px; vertical-align: top; }
        tr:nth-child(even) { background: #fcfdfe; }
        .badge { display: inline-block; padding: 2px 6px; border-radius: 4px; font-size: 9px; font-weight: bold; }
        .badge-active { background: #ecfdf5; color: #047857; }
        .badge-inactive { background: #f1f5f9; color: #475569; }
        .price { font-weight: bold; color: #0f172a; }
        .discount { color: #dc2626; font-size: 10px; }
        .thumb { width: 32px; height: 32px; object-fit: cover; border-radius: 4px; }
        @media print {
            body { margin: 0; }
            .no-print { display: none; }
        }
    </style>
</head>
<body onload="window.print()">
    <div class="header">
        <div>
            <h1>AMAR BANGLA BAZAR</h1>
            <div class="meta">Master Product Catalog & Inventory Report | Generated: {{ date('d M Y, h:i A') }}</div>
        </div>
        <div style="text-align: right;">
            <strong style="color: #2563eb; font-size: 14px;">Total Items: {{ count($products) }}</strong>
        </div>
    </div>

    <table>
        <thead>
            <tr>
                <th style="width: 30px; text-align: center;">No</th>
                <th style="width: 40px; text-align: center;">Image</th>
                <th>Product Name & Code</th>
                <th>Shop / Outlet</th>
                <th>Category</th>
                <th>Brand</th>
                <th style="text-align: right;">Price</th>
                <th style="text-align: center;">Stock</th>
                <th style="text-align: center;">Status</th>
            </tr>
        </thead>
        <tbody>
            @foreach($products as $index => $p)
            <tr>
                <td style="text-align: center; font-weight: bold; color: #64748b;">{{ $index + 1 }}</td>
                <td style="text-align: center;">
                    @if($p->main_image)
                        <img src="{{ $p->main_image }}" class="thumb" alt="">
                    @else
                        -
                    @endif
                </td>
                <td>
                    <strong style="font-size: 11px;">{{ $p->name }}</strong>
                    <div style="color: #64748b; font-size: 9px;">Code: {{ $p->item_code ?? 'N/A' }}</div>
                </td>
                <td>{{ $p->shop->name ?? 'N/A' }}</td>
                <td>{{ $p->category->name ?? 'General' }}</td>
                <td>{{ $p->brand ?? '-' }}</td>
                <td style="text-align: right;">
                    <div class="price">৳{{ number_format($p->price, 2) }}</div>
                    @if($p->discount_price)
                        <div class="discount">Disc: ৳{{ number_format($p->discount_price, 2) }}</div>
                    @endif
                </td>
                <td style="text-align: center; font-weight: bold;">{{ $p->stock_quantity }}</td>
                <td style="text-align: center;">
                    <span class="badge {{ $p->is_active ? 'badge-active' : 'badge-inactive' }}">
                        {{ $p->is_active ? 'Active' : 'Inactive' }}
                    </span>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
