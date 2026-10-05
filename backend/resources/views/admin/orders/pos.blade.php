<!DOCTYPE html>
<html lang="bn">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>POS Receipt - Inv_{{ $order->invoice_id ?? $order->id }} - Amar Bangla</title>
    <style>
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }
        body {
            background-color: #f3f4f6;
            color: #000;
            font-family: 'Courier New', Courier, monospace, -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif;
            font-size: 12px;
            line-height: 1.35;
            padding: 20px;
        }
        .no-print {
            max-width: 80mm;
            margin: 0 auto 15px auto;
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 10px;
        }
        .btn {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 8px 14px;
            font-size: 12px;
            font-weight: bold;
            border-radius: 8px;
            cursor: pointer;
            border: none;
            text-decoration: none;
        }
        .btn-primary {
            background: #2563eb;
            color: #fff;
        }
        .btn-primary:hover {
            background: #1d4ed8;
        }
        .btn-secondary {
            background: #4b5563;
            color: #fff;
        }
        .btn-secondary:hover {
            background: #374151;
        }

        /* POS Receipt Thermal Paper Container (80mm width) */
        .pos-receipt {
            width: 80mm;
            max-width: 80mm;
            margin: 0 auto;
            background: #fff;
            padding: 5mm 4mm;
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
            border-radius: 6px;
            color: #000;
        }

        /* Typography & Layout */
        .text-center { text-align: center; }
        .text-right { text-align: right; }
        .text-left { text-align: left; }
        .font-bold { font-weight: bold; }
        .font-black { font-weight: 900; }
        .uppercase { text-transform: uppercase; }

        .brand-title {
            font-size: 15px;
            font-weight: 900;
            letter-spacing: 0.5px;
            margin-bottom: 2px;
        }
        .sub-header {
            font-size: 11px;
            color: #222;
            margin-bottom: 2px;
        }

        .divider-dashed {
            border-top: 1px dashed #000;
            margin: 6px 0;
        }
        .divider-double {
            border-top: 2px dashed #000;
            margin: 6px 0;
        }
        .divider-solid {
            border-top: 1px solid #000;
            margin: 5px 0;
        }

        .meta-row {
            display: flex;
            justify-content: space-between;
            font-size: 11px;
            margin: 2px 0;
        }

        /* Table */
        .pos-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 11px;
            margin: 4px 0;
        }
        .pos-table th {
            text-align: left;
            border-bottom: 1px dashed #000;
            padding: 3px 0;
            font-size: 10px;
        }
        .pos-table td {
            padding: 3px 0;
            vertical-align: top;
        }

        .item-name {
            font-weight: bold;
            font-size: 11px;
            word-break: break-word;
        }
        .item-sub {
            font-size: 10px;
            color: #333;
        }

        /* Totals */
        .totals-table {
            width: 100%;
            font-size: 11px;
        }
        .totals-table td {
            padding: 2px 0;
        }
        .grand-total {
            font-size: 14px;
            font-weight: 900;
            padding: 4px 0 !important;
        }

        .badge-status {
            display: inline-block;
            padding: 1px 6px;
            border: 1px solid #000;
            font-weight: bold;
            font-size: 10px;
            text-transform: uppercase;
        }

        .barcode-box {
            margin: 8px 0 4px 0;
            text-align: center;
            font-family: monospace;
            font-size: 13px;
            letter-spacing: 2px;
            font-weight: bold;
        }

        .footer-note {
            font-size: 10px;
            color: #333;
            margin-top: 4px;
            line-height: 1.3;
        }

        @media print {
            body {
                background: #fff !important;
                padding: 0 !important;
                margin: 0 !important;
            }
            .no-print {
                display: none !important;
            }
            .pos-receipt {
                width: 78mm !important;
                max-width: 78mm !important;
                margin: 0 auto !important;
                padding: 2mm 1mm !important;
                box-shadow: none !important;
                border-radius: 0 !important;
            }
            @page {
                size: 80mm auto;
                margin: 0mm;
            }
        }
    </style>
</head>
<body>

    <!-- On-screen Action Toolbar (Hidden in Print) -->
    <div class="no-print">
        <a href="{{ route('admin.orders.show', $order) }}" class="btn btn-secondary">
            &larr; Back to Order
        </a>
        <button onclick="window.print()" class="btn btn-primary">
            &#128438; Print POS Receipt
        </button>
    </div>

    <!-- POS Thermal Receipt (80mm Width) -->
    <div class="pos-receipt">
        
        <!-- Header -->
        <div class="text-center">
            <div class="brand-title">{{ $order->shop->name ?? 'AMAR BANGLA BAZAR' }}</div>
            <div class="sub-header">{{ $order->shop->address ?? ($order->shop->upazila ?? 'Local Marketplace Network') }}</div>
            <div class="sub-header">Phone: {{ $order->shop->phone ?? $order->shop->mobile ?? $settings['support_phone'] ?? '+880 1714070770' }}</div>
        </div>

        <div class="divider-double"></div>

        <!-- Memo Title & Basic Info -->
        <div class="text-center font-bold" style="font-size: 12px; letter-spacing: 1px;">
            CASH MEMO / INVOICE
        </div>

        <div class="meta-row" style="margin-top: 4px;">
            <span>Inv: <b>Inv_{{ $order->invoice_id ?? $order->id }}</b></span>
            <span>Ord: <b>#{{ $order->order_number }}</b></span>
        </div>
        <div class="meta-row">
            <span>Date: {{ $order->created_at ? $order->created_at->format('d/m/Y') : date('d/m/Y') }}</span>
            <span>Time: {{ $order->created_at ? $order->created_at->format('h:i A') : date('h:i A') }}</span>
        </div>

        <div class="divider-dashed"></div>

        <!-- Customer Details -->
        <div>
            <div style="font-size: 11px;"><b>Customer:</b> {{ $order->customer_name }}</div>
            <div style="font-size: 11px;"><b>Phone:</b> +880 {{ $order->customer_phone }}</div>
            <div style="font-size: 10px; color: #222;">
                <b>Address:</b> {{ $order->shipping_address ?? '' }}, {{ $order->upazila ?? $order->district }}
            </div>
        </div>

        <div class="divider-dashed"></div>

        <!-- Product Items Table -->
        <table class="pos-table">
            <thead>
                <tr>
                    <th style="width: 48%;">ITEM</th>
                    <th class="text-center" style="width: 14%;">QTY</th>
                    <th class="text-right" style="width: 18%;">PRICE</th>
                    <th class="text-right" style="width: 20%;">TOTAL</th>
                </tr>
            </thead>
            <tbody>
                @foreach($order->items as $idx => $item)
                <tr>
                    <td>
                        <div class="item-name">{{ $item->product_name }}</div>
                        @if($item->unit || $item->variant_name)
                        <div class="item-sub">{{ $item->variant_name ?? $item->unit }}</div>
                        @endif
                    </td>
                    <td class="text-center">{{ $item->quantity }}</td>
                    <td class="text-right">{{ number_format($item->price, 0) }}</td>
                    <td class="text-right font-bold">{{ number_format($item->subtotal ?? ($item->price * $item->quantity), 0) }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>

        <div class="divider-dashed"></div>

        <!-- Financial Summary -->
        <table class="totals-table">
            <tr>
                <td>Subtotal ({{ $order->items->sum('quantity') }} items):</td>
                <td class="text-right font-bold">৳ {{ number_format($order->subtotal, 2) }}</td>
            </tr>
            <tr>
                <td>Delivery Charge:</td>
                <td class="text-right font-bold">৳ {{ number_format($order->shipping_fee, 2) }}</td>
            </tr>
            @if(!empty($order->discount_amount) && $order->discount_amount > 0)
            <tr>
                <td>Discount:</td>
                <td class="text-right font-bold">- ৳ {{ number_format($order->discount_amount, 2) }}</td>
            </tr>
            @endif
            <tr>
                <td colspan="2"><div class="divider-double"></div></td>
            </tr>
            <tr class="grand-total">
                <td>NET PAYABLE:</td>
                <td class="text-right font-black" style="font-size: 15px;">৳ {{ number_format($order->total_amount, 2) }}</td>
            </tr>
            <tr>
                <td colspan="2"><div class="divider-double"></div></td>
            </tr>
            <tr>
                <td>Payment Method:</td>
                <td class="text-right font-bold uppercase">{{ $order->payment_method === 'cod' ? 'Cash On Delivery' : $order->payment_method }}</td>
            </tr>
            <tr>
                <td>Payment Status:</td>
                <td class="text-right">
                    <span class="badge-status">
                        {{ strtoupper($order->payment_status ?: 'UNPAID') }}
                    </span>
                </td>
            </tr>
        </table>

        <div class="divider-dashed"></div>

        <!-- Barcode Line -->
        <div class="barcode-box">
            *{{ $order->order_number }}*
        </div>

        <!-- Footer -->
        <div class="text-center footer-note">
            <div class="font-bold">পণ্য ক্রয়ের জন্য ধন্যবাদ, আবার আসবেন!</div>
            <div>Thank You For Shopping With Us!</div>
            <div style="margin-top: 3px;">Hotline: {{ $settings['support_phone'] ?? '+880 1714-070770' }}</div>
            <div>www.amarbangla.com.bd</div>
            <div style="margin-top: 4px; font-size: 9px; letter-spacing: 1px;">*** END OF RECEIPT ***</div>
        </div>

    </div>

    <script>
        // Auto print if ?autoprint=1 is in query
        if (window.location.search.includes('autoprint=1')) {
            window.addEventListener('load', () => {
                setTimeout(() => window.print(), 300);
            });
        }
    </script>
</body>
</html>
