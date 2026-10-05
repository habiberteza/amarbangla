<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invoice Inv_{{ $order->invoice_id ?? $order->id }} - {{ $settings['site_name'] ?? 'Amar Bangla' }}</title>
    <!-- Tailwind CSS & FontAwesome -->
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body {
            font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif;
            background-color: #f4f6f9;
            color: #333;
        }

        /* Fixed side-by-side bottom section */
        .invoice-bottom-grid {
            display: flex !important;
            flex-direction: row !important;
            align-items: flex-start !important;
            justify-content: space-between !important;
            gap: 24px !important;
            page-break-inside: avoid !important;
            break-inside: avoid !important;
        }
        .invoice-bottom-left {
            width: 50% !important;
            flex: 0 0 50% !important;
        }
        .invoice-bottom-right {
            width: 46% !important;
            flex: 0 0 46% !important;
            page-break-inside: avoid !important;
            break-inside: avoid !important;
        }
        .amount-due-header {
            white-space: nowrap !important;
        }

        @media print {
            .no-print {
                display: none !important;
            }
            body {
                background: #fff !important;
                color: #000 !important;
                padding: 0 !important;
                margin: 0 !important;
            }

            /* MODE 1: Standard A4 Print */
            body:not(.print-mode-pos) #view-pos-container {
                display: none !important;
            }
            body:not(.print-mode-pos) .invoice-box {
                display: block !important;
                border: none !important;
                box-shadow: none !important;
                padding: 0 !important;
                max-width: 100% !important;
                width: 100% !important;
            }
            body:not(.print-mode-pos) .invoice-bottom-grid {
                display: flex !important;
                flex-direction: row !important;
                align-items: flex-start !important;
                justify-content: space-between !important;
                width: 100% !important;
                page-break-inside: avoid !important;
                break-inside: avoid !important;
            }
            body:not(.print-mode-pos) .invoice-bottom-left {
                width: 50% !important;
                flex: 0 0 50% !important;
            }
            body:not(.print-mode-pos) .invoice-bottom-right {
                width: 46% !important;
                flex: 0 0 46% !important;
                page-break-inside: avoid !important;
                break-inside: avoid !important;
            }
            body:not(.print-mode-pos) @page {
                margin: 1.2cm;
                size: A4 portrait;
            }

            /* MODE 2: Custom 80mm POS Thermal Receipt Print */
            body.print-mode-pos .invoice-box {
                display: none !important;
            }
            body.print-mode-pos #view-pos-container {
                display: block !important;
                background: transparent !important;
                padding: 0 !important;
                margin: 0 auto !important;
            }
            body.print-mode-pos .pos-receipt-card {
                width: 78mm !important;
                max-width: 78mm !important;
                margin: 0 auto !important;
                padding: 2mm 1mm !important;
                box-shadow: none !important;
                border: none !important;
            }
            body.print-mode-pos @page {
                size: 80mm auto;
                margin: 0mm;
            }
        }
    </style>
</head>
<body class="p-4 sm:p-8 antialiased">

    <!-- Top Action Line (Hidden in Print) -->
    <div class="no-print max-w-4xl mx-auto mb-6 flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4 bg-white p-4 rounded-xl border border-gray-200 shadow-sm">
        <div class="flex items-center gap-3">
            <a href="{{ route('admin.orders.show', $order) }}" class="inline-flex items-center gap-2 px-3.5 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 font-semibold text-xs rounded-lg transition">
                <i class="fa fa-arrow-left"></i>
                <span>Back to Order</span>
            </a>
            <span class="text-xs text-gray-500 font-mono">Invoice: Inv_{{ $order->invoice_id ?? $order->id }}</span>
        </div>

        <div class="flex flex-wrap items-center gap-2">
            <!-- View Mode Switcher -->
            <div class="inline-flex p-1 bg-gray-100 rounded-xl border border-gray-200 mr-1">
                <button type="button" onclick="switchPrintView('a4')" id="tab-btn-a4" 
                        class="px-3 py-1.5 text-xs font-bold rounded-lg transition-all shadow-xs bg-white text-blue-700">
                    📄 A4 Invoice
                </button>
                <button type="button" onclick="switchPrintView('pos')" id="tab-btn-pos" 
                        class="px-3 py-1.5 text-xs font-bold rounded-lg transition-all text-gray-600 hover:text-gray-900">
                    🧾 POS Receipt (80mm)
                </button>
            </div>

            <!-- Print A4 -->
            <button onclick="triggerPrint('a4')" class="inline-flex items-center gap-2 px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white font-bold text-xs rounded-lg shadow-sm transition cursor-pointer">
                <i class="fa fa-print"></i>
                <span>Print A4</span>
            </button>

            <!-- Print POS -->
            <button onclick="triggerPrint('pos')" class="inline-flex items-center gap-2 px-4 py-2 bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-xs rounded-lg shadow-sm transition cursor-pointer">
                <i class="fa fa-receipt"></i>
                <span>Print POS (80mm)</span>
            </button>

            <!-- Direct Link to POS Page -->
            <a href="{{ route('admin.orders.pos', $order) }}" target="_blank" class="inline-flex items-center gap-1.5 px-3 py-2 bg-gray-800 hover:bg-gray-900 text-white font-semibold text-xs rounded-lg shadow-sm transition" title="Open Clean POS Slip">
                <i class="fa fa-external-link"></i>
                <span>POS View</span>
            </a>
        </div>
    </div>

    <!-- ========================================================= -->
    <!-- VIEW 1: Main A4 Invoice Card                              -->
    <!-- ========================================================= -->
    <div id="view-a4-container" class="invoice-box max-w-4xl mx-auto bg-white rounded-2xl border border-gray-200 shadow-lg p-6 sm:p-10 relative overflow-hidden">
        
        <!-- Header row: Date & Logo -->
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center pb-4 border-b border-gray-200 gap-4">
            <div>
                <img src="{{ asset('uploads/schools/amarBangla.jpg') }}" 
                     alt="{{ $order->shop->name ?? 'Amar Bangla' }}" 
                     class="max-w-[220px] h-auto object-contain"
                     onerror="this.src='{{ asset('images/amarBangla.jpg') }}'; this.onerror=null;">
            </div>
            <div class="text-left sm:text-right">
                <div class="text-sm font-semibold text-gray-700">
                    Date: {{ $order->created_at ? $order->created_at->format('d-m-Y') : date('d-m-Y') }}
                </div>
                <div class="text-xs text-gray-500 mt-1">
                    Hotline: <strong>{{ $settings['support_phone'] ?? '+880 1714-070770' }}</strong>
                </div>
            </div>
        </div>

        <!-- Invoice Info row -->
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-3 py-4 border-b border-gray-100 bg-gray-50/70 px-4 rounded-xl mt-4">
            <div>
                <div class="text-sm font-black text-gray-900">
                    <b>Invoice</b> Inv_{{ $order->invoice_id ?? $order->id }} &gt;
                </div>
                <p class="text-xs text-gray-500 mt-0.5">
                    Placed on {{ $order->created_at ? $order->created_at->format('Y-m-d H:i:s') : 'N/A' }}
                </p>
            </div>
            <div>
                @php
                    $invStatus = (string)($order->status ?? '2');
                    $stLower = strtolower($order->order_status ?? '');
                    if ($stLower === 'cancelled' || $stLower === 'cancel' || $invStatus === '3') {
                        $statusBadge = '<span class="px-3 py-1 text-xs font-bold rounded-md bg-red-600 text-white">Cancel</span>';
                    } elseif ($order->payment_status === 'paid' || $invStatus === '1') {
                        $statusBadge = '<span class="px-3 py-1 text-xs font-bold rounded-md bg-emerald-600 text-white">Paid</span>';
                    } elseif ($stLower === 'pending' || $invStatus === '2') {
                        $statusBadge = '<span class="px-3 py-1 text-xs font-bold rounded-md bg-amber-500 text-white">Pending</span>';
                    } else {
                        $statusBadge = '<span class="px-3 py-1 text-xs font-bold rounded-md bg-blue-600 text-white">Unpaid</span>';
                    }
                @endphp
                {!! $statusBadge !!}
            </div>
        </div>

        <!-- Packages & Products Loop -->
        <div class="space-y-5 my-6">
            @php
                $itemsByShop = $order->items->groupBy(function($item) {
                    return $item->shop_id ?: 'direct';
                });
            @endphp

            @foreach($itemsByShop as $shopKey => $shopItems)
                @php
                    $firstItem = $shopItems->first();
                    $shop = $firstItem->shop ?? $order->shop;
                    $agent = $shop?->agent ?? $order->agent;
                    
                    $delivStatus = strtolower($order->delivery_status ?? 'not_accepted');
                    if ($delivStatus === 'complete' || $delivStatus === 'delivered') {
                        $delivBadge = '<span class="px-2.5 py-0.5 text-[11px] font-bold rounded bg-emerald-600 text-white">Complete</span>';
                    } elseif ($delivStatus === 'accepted' || $delivStatus === 'processing' || $delivStatus === 'shipped') {
                        $delivBadge = '<span class="px-2.5 py-0.5 text-[11px] font-bold rounded bg-sky-600 text-white">Processing</span>';
                    } else {
                        $delivBadge = '<span class="px-2.5 py-0.5 text-[11px] font-bold rounded bg-amber-500 text-white">Place order</span>';
                    }

                    if ($order->payment_status === 'paid') {
                        $pkgBadge = '<span class="px-2.5 py-0.5 text-[11px] font-bold rounded bg-emerald-600 text-white">Paid</span>';
                    } elseif ($order->order_status === 'cancelled' || $order->order_status === 'cancel') {
                        $pkgBadge = '<span class="px-2.5 py-0.5 text-[11px] font-bold rounded bg-red-600 text-white">Cancel</span>';
                    } else {
                        $pkgBadge = '<span class="px-2.5 py-0.5 text-[11px] font-bold rounded bg-emerald-600 text-white">Unpaid</span>';
                    }
                @endphp

                <div class="package-box border border-gray-300 rounded-xl p-4 sm:p-5 bg-white" style="border: 1px solid #e4e0e0; margin-top: 10px; text-transform: capitalize;">
                    <div class="package-header border-b border-gray-200 pb-3 mb-4 flex flex-col sm:flex-row justify-between items-start gap-2" style="border-bottom: 1px solid #e4e0e0;">
                        <div>
                            <h4 class="text-base font-bold text-gray-900 capitalize">
                                {{ $shop->name ?? 'Amer bangla bazar' }}
                            </h4>
                            <div class="text-xs text-gray-500 mt-0.5">
                                {{ $shop->phone ?? $shop->mobile ?? '+880 1714070770' }}
                            </div>

                            @if($agent)
                            <div class="no-print text-xs text-gray-500 mt-1">
                                <div>Agent Name: <span class="font-medium text-gray-700">{{ $agent->name ?? $agent->agent_name }}</span></div>
                                <div>Agent Phone: <span class="font-medium text-gray-700">{{ $agent->phone ?? $agent->mobile }}</span></div>
                            </div>
                            @endif

                            <div class="text-xs text-gray-700 font-semibold mt-1">
                                delivery charge : ৳ {{ number_format($order->shipping_fee, 2) }}
                            </div>
                        </div>

                        <div class="flex items-center gap-2 self-start sm:self-auto">
                            {!! $pkgBadge !!}
                            {!! $delivBadge !!}
                        </div>
                    </div>

                    <div class="products-list divide-y divide-gray-100">
                        @foreach($shopItems as $item)
                            @php
                                $product = $item->product;
                                $supplier = $product?->supplier;
                                $supName = $supplier->name ?? 'Habib';
                                $supPhone = $supplier->phone ?? '1714070770';
                                $purchasePrice = $product->purchase_price ?? 15.00;
                            @endphp
                            <div class="py-3.5 first:pt-0 last:pb-0 flex items-start gap-4">
                                <div class="w-18 h-18 rounded-lg overflow-hidden border border-gray-200 bg-gray-50 flex-shrink-0 flex items-center justify-center p-1">
                                    <img src="{{ $item->thumbnail_url }}" 
                                         alt="{{ $item->product_name }}"
                                         class="w-full h-full object-cover rounded check-out-img"
                                         onerror="this.src='{{ asset('uploads/demo_product_image/noImage.jpg') }}'; this.onerror=null;">
                                </div>

                                <div class="flex-1">
                                    <p class="font-bold text-gray-900 text-sm leading-snug">
                                        {{ $item->product_name }}
                                    </p>
                                    <div class="text-xs font-semibold text-gray-800 mt-1">
                                        ৳ {{ number_format($item->price, 2) }}
                                    </div>
                                    <div class="text-xs text-gray-600 font-medium">
                                        x{{ $item->quantity }}
                                    </div>

                                    <div class="no-print text-[11px] text-gray-500 mt-1.5 leading-relaxed bg-gray-50 p-2 rounded border border-gray-100 inline-block">
                                        <div><strong>Suplier:</strong> {{ $supName }} {{ $supPhone }}</div>
                                        <div><strong>Purchase Price:</strong> ৳ {{ number_format($purchasePrice, 2) }}</div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endforeach
        </div>

        <!-- ========================================================================= -->
        <!-- Fixed Side-by-Side Bottom Section (Will NOT break downwards in Print)    -->
        <!-- ========================================================================= -->
        <div class="invoice-bottom-grid flex flex-col md:flex-row items-start justify-between gap-6 pt-6 border-t border-gray-200">
            <!-- Left: Payment Method & Customer Address -->
            <div class="invoice-bottom-left w-full md:w-[50%] space-y-4">
                <div>
                    <p class="text-base font-bold text-gray-900">Payment Methods:</p>
                    <p class="text-sm font-bold text-gray-800 mt-1">
                        {{ $order->payment_method === 'cod' ? 'Cash on delivery' : ucfirst($order->payment_method) }}
                    </p>
                </div>

                <div>
                    <p class="text-sm font-semibold text-gray-700">Customer delivery address</p>
                    <div class="well text-xs leading-relaxed text-gray-700 bg-gray-50 border border-gray-200 rounded-xl p-4 mt-2 space-y-1">
                        <div><b>Name:</b> {{ $order->customer_name }}</div>
                        <div><b>Phone:</b> +880 {{ $order->customer_phone }}</div>
                        <div><b>Division:</b> {{ $order->division ?? 'Khulna' }}</div>
                        <div><b>District:</b> {{ $order->district ?? 'Jashore' }}</div>
                        <div><b>Upazila:</b> {{ $order->upazila ?? 'Abhaynagar' }}</div>
                        <div><b>Pourashava/Union:</b> {{ $order->pourashava ?? 'Pourashava' }}</div>
                        <div><b>Ward:</b> {{ $order->ward ?? 'Ward 1' }}</div>
                        <div><b>Home Address:</b> {{ $order->shipping_address ?? 'Fast' }}</div>
                    </div>
                </div>
            </div>

            <!-- Right: Amounts Due & Financial Breakdown (Protected with nowrap and locked width) -->
            <div class="invoice-bottom-right w-full md:w-[46%]">
                <p class="amount-due-header text-base font-bold text-gray-900 mb-3 whitespace-nowrap">
                    Amount Due {{ $order->created_at ? $order->created_at->format('d-m-Y') : date('d-m-Y') }}
                </p>

                <div class="table-responsive bg-white rounded-xl border border-gray-200 overflow-hidden shadow-xs">
                    <table class="w-full text-xs">
                        <tbody class="divide-y divide-gray-100">
                            <tr>
                                <th class="w-1/2 px-4 py-2.5 text-left font-semibold text-gray-600">Subtotal:</th>
                                <td class="px-4 py-2.5 text-right font-bold text-gray-900">৳ {{ number_format($order->subtotal, 2) }}</td>
                            </tr>
                            <tr>
                                <th class="px-4 py-2.5 text-left font-semibold text-gray-600">Delivery charge:</th>
                                <td class="px-4 py-2.5 text-right font-bold text-gray-900">৳ {{ number_format($order->shipping_fee, 2) }}</td>
                            </tr>
                            <tr class="bg-gray-50/80 text-sm font-black text-gray-900">
                                <th class="px-4 py-3 text-left">Total:</th>
                                <td class="px-4 py-3 text-right text-blue-700">৳ {{ number_format($order->total_amount, 2) }}</td>
                            </tr>

                            @if(!empty($legacyInvoice?->nagad_paid) && $legacyInvoice->nagad_paid > 0)
                            <tr>
                                <th class="px-4 py-2.5 text-left font-semibold text-emerald-700">Cash Pay:</th>
                                <td class="px-4 py-2.5 text-right font-bold text-emerald-700">৳ {{ number_format($legacyInvoice->nagad_paid, 2) }}</td>
                            </tr>
                            @endif

                            @if(!empty($legacyInvoice?->bank_paid) && $legacyInvoice->bank_paid > 0)
                            <tr>
                                <th class="px-4 py-2.5 text-left font-semibold text-blue-700">Bank Pay:</th>
                                <td class="px-4 py-2.5 text-right font-bold text-blue-700">৳ {{ number_format($legacyInvoice->bank_paid, 2) }}</td>
                            </tr>
                            @endif

                            @if(!empty($legacyInvoice?->chaque_paid) && $legacyInvoice->chaque_paid > 0)
                            <tr>
                                <th class="px-4 py-2.5 text-left font-semibold text-purple-700">Cheque Pay:</th>
                                <td class="px-4 py-2.5 text-right font-bold text-purple-700">৳ {{ number_format($legacyInvoice->chaque_paid, 2) }}</td>
                            </tr>
                            @endif
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Print Line Action (Hidden in print) -->
        <div class="no-print mt-8 pt-4 border-t border-gray-200 flex flex-wrap items-center justify-end gap-3">
            <button onclick="triggerPrint('a4');" class="inline-flex items-center gap-2 px-5 py-2.5 bg-blue-600 hover:bg-blue-700 text-white font-bold text-xs rounded-xl shadow-md transition cursor-pointer">
                <i class="fa fa-print"></i>
                <span>Print A4 Invoice</span>
            </button>
            <button onclick="triggerPrint('pos');" class="inline-flex items-center gap-2 px-5 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-xs rounded-xl shadow-md transition cursor-pointer">
                <i class="fa fa-receipt"></i>
                <span>Print POS Receipt (80mm)</span>
            </button>
        </div>
    </div>


    <!-- ========================================================= -->
    <!-- VIEW 2: POS Thermal Receipt (80mm) Preview Container      -->
    <!-- ========================================================= -->
    <div id="view-pos-container" class="hidden max-w-4xl mx-auto">
        <div class="bg-gray-100 rounded-2xl border border-gray-300 p-6 sm:p-10 flex flex-col items-center">
            
            <div class="no-print mb-4 flex items-center justify-between w-full max-w-[80mm]">
                <span class="text-xs font-bold text-gray-500 uppercase tracking-wider">
                    🧾 Thermal POS Slip (80mm)
                </span>
                <button onclick="triggerPrint('pos')" class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-xs rounded-lg shadow-sm transition cursor-pointer">
                    <i class="fa fa-print"></i>
                    <span>Print Receipt</span>
                </button>
            </div>

            <!-- Authentic 80mm POS Thermal Slip Container -->
            <div class="pos-receipt-card w-[80mm] max-w-[80mm] bg-white text-black p-5 shadow-2xl rounded-md font-mono text-xs border border-gray-300">
                <!-- Shop Header -->
                <div class="text-center">
                    <h3 class="text-base font-black tracking-wide uppercase">{{ $order->shop->name ?? 'AMAR BANGLA BAZAR' }}</h3>
                    <div class="text-[11px] text-gray-700 mt-0.5">{{ $order->shop->address ?? ($order->shop->upazila ?? 'Local Marketplace') }}</div>
                    <div class="text-[11px] text-gray-700">Phone: {{ $order->shop->phone ?? $order->shop->mobile ?? $settings['support_phone'] ?? '+880 1714070770' }}</div>
                </div>

                <div class="border-t-2 border-dashed border-black my-2.5"></div>

                <div class="text-center font-bold text-xs tracking-wider">
                    CASH MEMO / INVOICE
                </div>

                <div class="flex justify-between text-[11px] mt-1.5">
                    <span>Inv: <b>Inv_{{ $order->invoice_id ?? $order->id }}</b></span>
                    <span>Ord: <b>#{{ $order->order_number }}</b></span>
                </div>
                <div class="flex justify-between text-[11px]">
                    <span>Date: {{ $order->created_at ? $order->created_at->format('d/m/Y') : date('d/m/Y') }}</span>
                    <span>Time: {{ $order->created_at ? $order->created_at->format('h:i A') : date('h:i A') }}</span>
                </div>

                <div class="border-t border-dashed border-black my-2"></div>

                <!-- Customer Details -->
                <div class="text-[11px]">
                    <div><b>Customer:</b> {{ $order->customer_name }}</div>
                    <div><b>Phone:</b> +880 {{ $order->customer_phone }}</div>
                    <div class="text-[10px] text-gray-800">
                        <b>Address:</b> {{ $order->shipping_address ?? '' }}, {{ $order->upazila ?? $order->district }}
                    </div>
                </div>

                <div class="border-t border-dashed border-black my-2"></div>

                <!-- Items Table -->
                <table class="w-full text-[11px] border-collapse">
                    <thead>
                        <tr class="border-b border-dashed border-black text-[10px]">
                            <th class="text-left py-1 w-[50%]">ITEM</th>
                            <th class="text-center py-1 w-[14%]">QTY</th>
                            <th class="text-right py-1 w-[18%]">PRICE</th>
                            <th class="text-right py-1 w-[18%]">TOTAL</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-dotted divide-gray-400">
                        @foreach($order->items as $item)
                        <tr>
                            <td class="py-1 pr-1 font-bold break-words">
                                {{ $item->product_name }}
                            </td>
                            <td class="py-1 text-center align-top">{{ $item->quantity }}</td>
                            <td class="py-1 text-right align-top">{{ number_format($item->price, 0) }}</td>
                            <td class="py-1 text-right font-bold align-top">
                                {{ number_format($item->subtotal ?? ($item->price * $item->quantity), 0) }}
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>

                <div class="border-t border-dashed border-black my-2"></div>

                <!-- Financial Breakdown -->
                <table class="w-full text-[11px]">
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
                        <td colspan="2"><div class="border-t-2 border-dashed border-black my-1.5"></div></td>
                    </tr>
                    <tr class="text-sm font-black">
                        <td>NET PAYABLE:</td>
                        <td class="text-right" style="font-size: 15px;">৳ {{ number_format($order->total_amount, 2) }}</td>
                    </tr>
                    <tr>
                        <td colspan="2"><div class="border-t-2 border-dashed border-black my-1.5"></div></td>
                    </tr>
                    <tr>
                        <td>Payment Method:</td>
                        <td class="text-right font-bold uppercase">{{ $order->payment_method === 'cod' ? 'Cash On Delivery' : $order->payment_method }}</td>
                    </tr>
                    <tr>
                        <td>Payment Status:</td>
                        <td class="text-right">
                            <span class="inline-block border border-black px-1.5 py-0.2 text-[10px] font-bold uppercase">
                                {{ strtoupper($stLower ?: 'UNPAID') }}
                            </span>
                        </td>
                    </tr>
                </table>

                <div class="border-t border-dashed border-black my-2.5"></div>

                <!-- Barcode Representation -->
                <div class="text-center font-bold tracking-widest text-xs my-2">
                    *{{ $order->order_number }}*
                </div>

                <!-- POS Footer -->
                <div class="text-center text-[10px] text-gray-700 space-y-0.5 mt-2">
                    <div class="font-bold text-black">পণ্য ক্রয়ের জন্য ধন্যবাদ, আবার আসবেন!</div>
                    <div>Thank You For Shopping With Us!</div>
                    <div>Hotline: {{ $settings['support_phone'] ?? '+880 1714-070770' }}</div>
                    <div>www.amarbangla.com.bd</div>
                    <div class="text-[9px] tracking-wider mt-1 text-gray-500">*** END OF RECEIPT ***</div>
                </div>
            </div>

            <div class="no-print mt-6 flex items-center gap-3">
                <button onclick="triggerPrint('pos');" class="inline-flex items-center gap-2 px-6 py-2.5 bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-xs rounded-xl shadow-md transition cursor-pointer">
                    <i class="fa fa-receipt"></i>
                    <span>Print Thermal POS Receipt</span>
                </button>
                <button onclick="switchPrintView('a4');" class="px-4 py-2.5 bg-gray-200 hover:bg-gray-300 text-gray-800 font-semibold text-xs rounded-xl transition cursor-pointer">
                    Back to A4 View
                </button>
            </div>
        </div>
    </div>

    <!-- Switcher Script -->
    <script>
    function switchPrintView(mode) {
        const a4 = document.getElementById('view-a4-container');
        const pos = document.getElementById('view-pos-container');
        const tabA4 = document.getElementById('tab-btn-a4');
        const tabPos = document.getElementById('tab-btn-pos');

        if (mode === 'pos') {
            a4.classList.add('hidden');
            pos.classList.remove('hidden');
            tabPos.classList.add('bg-white', 'text-emerald-700', 'shadow-xs');
            tabPos.classList.remove('text-gray-600');
            tabA4.classList.remove('bg-white', 'text-blue-700', 'shadow-xs');
            tabA4.classList.add('text-gray-600');
        } else {
            pos.classList.add('hidden');
            a4.classList.remove('hidden');
            tabA4.classList.add('bg-white', 'text-blue-700', 'shadow-xs');
            tabA4.classList.remove('text-gray-600');
            tabPos.classList.remove('bg-white', 'text-emerald-700', 'shadow-xs');
            tabPos.classList.add('text-gray-600');
        }
    }

    function triggerPrint(mode) {
        document.body.classList.remove('print-mode-a4', 'print-mode-pos');
        if (mode === 'pos') {
            document.body.classList.add('print-mode-pos');
        } else {
            document.body.classList.add('print-mode-a4');
        }
        window.print();
    }

    window.onafterprint = function() {
        document.body.classList.remove('print-mode-a4', 'print-mode-pos');
    };
    </script>
</body>
</html>
