<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use App\Models\Order;
use App\Models\OrderItem;

class ImportLegacyOrdersCommand extends Command
{
    protected $signature = 'orders:import-legacy {--truncate : Truncate orders and order_items before importing}';
    protected $description = 'Import all legacy orders from invoice and invoice_item into orders and order_items tables';

    public function handle()
    {
        ini_set('memory_limit', '512M');
        $this->info('Starting legacy orders import from invoice / invoice_item...');

        if (!DB::getSchemaBuilder()->hasTable('invoice')) {
            $this->error('The "invoice" table does not exist in the database!');
            return 1;
        }

        if ($this->option('truncate')) {
            $this->warn('Truncating existing orders and order_items...');
            DB::statement('SET FOREIGN_KEY_CHECKS=0;');
            OrderItem::truncate();
            Order::truncate();
            DB::statement('SET FOREIGN_KEY_CHECKS=1;');
        }

        // Location dictionaries
        $divNames = [
            1 => 'Chattagram',
            2 => 'Rajshahi',
            3 => 'Khulna',
            4 => 'Barisal',
            5 => 'Sylhet',
            6 => 'Dhaka',
            7 => 'Rangpur',
            8 => 'Mymensingh',
        ];

        // Preload needed lookups with minimal columns to keep memory tiny
        $customers = DB::table('customers')->select('customer_id', 'user_id', 'customer_name', 'name', 'phone', 'mobile', 'email', 'global_address_id', 'address', 'division', 'district', 'upazila', 'pourashava', 'ward')->get()->keyBy('customer_id');

        $shopAgentMap = DB::table('shops')->pluck('agent_id', 'sch_id')->toArray();
        $productNameMap = DB::table('products')->pluck('name', 'prod_id')->toArray();
        $agentPermittedAreas = DB::table('agent_permitted_area')->pluck('agent_id', 'global_address_id')->toArray();

        // Deliveries grouped by invoice_id
        $deliveries = DB::table('delivery')->select('invoice_id', 'status', 'delivery_boy_id', 'sch_id')->get()->groupBy('invoice_id');

        // All users for valid foreign key check
        $validUserIds = DB::table('users')->pluck('user_id')->flip()->toArray();

        $invoices = DB::table('invoice')->orderBy('invoice_id', 'asc')->get();
        $totalInvoices = $invoices->count();

        $this->info("Found {$totalInvoices} invoices to process.");
        $bar = $this->output->createProgressBar($totalInvoices);
        $bar->start();

        // Preload global addresses needed by these invoices
        $neededGaIds = $invoices->pluck('global_address_id')->filter()->unique()->toArray();
        $globalAddresses = DB::table('global_address')
            ->whereIn('global_address_id', $neededGaIds)
            ->select('global_address_id', 'division', 'zila', 'district', 'upazila', 'pourashava', 'ward')
            ->get()
            ->keyBy('global_address_id');

        $importedOrders = 0;
        $importedItems = 0;

        // Group invoice items by invoice_id
        $allInvoiceItems = DB::table('invoice_item')->get()->groupBy('invoice_id');

        $ordersToInsert = [];
        $itemsToInsert = [];

        foreach ($invoices as $inv) {
            $invoiceId = (int)$inv->invoice_id;

            // 1. Customer
            $cust = $customers->get($inv->customer_id);
            $custName = !empty($inv->customer_name) ? $inv->customer_name : ($cust->name ?? $cust->customer_name ?? 'Customer #' . $inv->customer_id);
            $custPhone = $cust->phone ?? $cust->mobile ?? '';
            $custEmail = $cust->email ?? null;

            $userId = null;
            if (!empty($cust->user_id) && isset($validUserIds[$cust->user_id])) {
                $userId = $cust->user_id;
            }

            // 2. Geographic info
            $gaId = $inv->global_address_id ?: ($cust->global_address_id ?? null);
            $ga = $gaId ? $globalAddresses->get($gaId) : null;

            $division = null;
            $district = null;
            $upazila = null;
            $pourashava = null;
            $ward = null;

            if ($ga) {
                // Division
                $divKey = (int)$ga->division;
                $division = $divNames[$divKey] ?? (function_exists('divisionname') ? divisionname($divKey) : ($ga->division ?: null));

                // District
                $district = function_exists('districtname') ? districtname((int)$ga->zila) : ($ga->district ?: null);
                if (empty($district) && !empty($ga->district)) {
                    $district = $ga->district;
                }

                // Upazila
                $upazila = function_exists('upazilaname') ? upazilaname((int)$ga->upazila) : ($ga->upazila ?: null);
                if (empty($upazila) && !empty($ga->upazila)) {
                    $upazila = $ga->upazila;
                }

                // Pourashava
                $pVal = (string)$ga->pourashava;
                if ($pVal === '1' || strtolower($pVal) === 'pourashava') {
                    $pourashava = 'Pourashava';
                } elseif ($pVal === '2' || strtolower($pVal) === 'union') {
                    $pourashava = 'Union';
                } elseif ($pVal === '0' || $pVal === '3' || str_contains(strtolower($pVal), 'city')) {
                    $pourashava = 'City Corporation';
                } else {
                    $pourashava = $ga->pourashava ?: null;
                }

                // Ward
                if (!empty($ga->ward)) {
                    $wClean = trim((string)$ga->ward);
                    $ward = str_starts_with(strtolower($wClean), 'ward') ? $wClean : 'Ward ' . $wClean;
                }
            } else {
                $division = $cust->division ?? null;
                $district = $cust->district ?? null;
                $upazila = $cust->upazila ?? null;
                $pourashava = $cust->pourashava ?? null;
                $ward = $cust->ward ?? null;
            }

            $shippingAddress = !empty($inv->address) ? $inv->address : ($cust->address ?? 'Bangladesh');

            // 3. Status determination
            $invStatus = (string)$inv->status; // 2=pending, 0=unpaid, 1=paid, 3=cancel
            $delivList = $deliveries->get($invoiceId);
            $firstDelivery = $delivList ? $delivList->first() : null;

            $deliveryBoyId = $firstDelivery ? $firstDelivery->delivery_boy_id : null;
            $deliveryStatus = 'not_accepted';
            $orderStatus = 'pending';

            if ($invStatus === '3') {
                $orderStatus = 'cancelled';
                $deliveryStatus = 'not_accepted';
            } elseif ($firstDelivery) {
                if ($firstDelivery->status == '1') {
                    $orderStatus = 'complete';
                    $deliveryStatus = 'complete';
                } elseif ($firstDelivery->status == '0') {
                    $orderStatus = 'accepted';
                    $deliveryStatus = 'accepted';
                }
            } else {
                $orderStatus = 'pending';
                $deliveryStatus = 'not_accepted';
            }

            // Payment status & method
            $paymentStatus = ($invStatus === '1') ? 'paid' : 'unpaid';
            $paymentMethod = !empty($inv->payment_method) ? strtolower(trim($inv->payment_method)) : 'cod';
            if (!in_array($paymentMethod, ['cod', 'bkash', 'nagad', 'rocket', 'bank'])) {
                $paymentMethod = 'cod';
            }

            // Amounts
            $totalAmount = (float)($inv->final_amount ?: $inv->amount ?: 0);
            $subtotal = (float)($inv->amount ?: $totalAmount);
            $discount = (float)($inv->entire_sale_discount ?? 0);
            $shippingFee = (float)($inv->delivery_charge ?? 0);

            // Fetch invoice items to determine shop_id and agent_id
            $invItems = $allInvoiceItems->get($invoiceId) ?? collect();
            $shopId = null;
            $agentId = null;

            if ($invItems->isNotEmpty()) {
                $shopId = $invItems->first()->sch_id;
            } elseif ($firstDelivery && !empty($firstDelivery->sch_id)) {
                $shopId = $firstDelivery->sch_id;
            }

            // Agent determination
            if ($shopId && isset($shopAgentMap[$shopId])) {
                $agentId = $shopAgentMap[$shopId];
            }
            if (!$agentId && $gaId && isset($agentPermittedAreas[$gaId])) {
                $agentId = $agentPermittedAreas[$gaId];
            }

            $orderNumber = 'INV-' . str_pad($invoiceId, 6, '0', STR_PAD_LEFT);
            $createdAt = $inv->createdDtm ?: $inv->timestamp ?: now();
            $updatedAt = $inv->updatedDtm ?: $inv->timestamp ?: now();

            $ordersToInsert[] = [
                'id'               => $invoiceId,
                'invoice_id'       => $invoiceId,
                'order_number'     => $orderNumber,
                'user_id'          => $userId,
                'shop_id'          => $shopId,
                'agent_id'         => $agentId,
                'delivery_boy_id'  => $deliveryBoyId,
                'customer_name'    => $custName,
                'customer_phone'   => $custPhone,
                'customer_email'   => $custEmail,
                'shipping_address' => $shippingAddress,
                'division'         => $division,
                'district'         => $district,
                'upazila'          => $upazila,
                'pourashava'       => $pourashava,
                'ward'             => $ward,
                'global_address_id'=> $gaId,
                'notes'            => null,
                'subtotal'         => $subtotal,
                'discount'         => $discount,
                'shipping_fee'     => $shippingFee,
                'total_amount'     => $totalAmount,
                'payment_method'   => $paymentMethod,
                'payment_status'   => $paymentStatus,
                'order_status'     => $orderStatus,
                'delivery_status'  => $deliveryStatus,
                'status'           => $invStatus,
                'transaction_id'   => $inv->payment_details ?: null,
                'created_at'       => $createdAt,
                'updated_at'       => $updatedAt,
            ];

            // Items
            foreach ($invItems as $item) {
                $prodName = 'Product #' . $item->prod_id;
                if (!empty($item->prod_id) && isset($productNameMap[$item->prod_id])) {
                    $prodName = $productNameMap[$item->prod_id];
                }

                $itemPrice = (float)($item->price ?? 0);
                $itemQty = (int)($item->quantity ?? 1);
                $itemSubtotal = (float)($item->final_price ?: $item->total_price ?: ($itemPrice * $itemQty));

                $itemsToInsert[] = [
                    'order_id'     => $invoiceId,
                    'shop_id'      => $item->sch_id ?: ($shopId ?: 7),
                    'product_id'   => $item->prod_id ?: null,
                    'product_name' => $prodName,
                    'variant_info' => null,
                    'price'        => $itemPrice,
                    'quantity'     => $itemQty,
                    'subtotal'     => $itemSubtotal,
                    'status'       => $orderStatus,
                    'created_at'   => $item->createdDtm ?: $createdAt,
                    'updated_at'   => $item->updatedDtm ?: $updatedAt,
                ];
            }

            // Batch insert in chunks of 200 to keep memory low and fast
            if (count($ordersToInsert) >= 200) {
                DB::table('orders')->upsert($ordersToInsert, ['id'], [
                    'invoice_id', 'order_number', 'user_id', 'shop_id', 'agent_id', 'delivery_boy_id',
                    'customer_name', 'customer_phone', 'customer_email', 'shipping_address',
                    'division', 'district', 'upazila', 'pourashava', 'ward', 'global_address_id',
                    'subtotal', 'discount', 'shipping_fee', 'total_amount', 'payment_method',
                    'payment_status', 'order_status', 'delivery_status', 'status', 'transaction_id',
                    'created_at', 'updated_at'
                ]);
                $importedOrders += count($ordersToInsert);
                $ordersToInsert = [];
            }

            if (count($itemsToInsert) >= 500) {
                DB::table('order_items')->insert($itemsToInsert);
                $importedItems += count($itemsToInsert);
                $itemsToInsert = [];
            }

            $bar->advance();
        }

        if (!empty($ordersToInsert)) {
            DB::table('orders')->upsert($ordersToInsert, ['id'], [
                'invoice_id', 'order_number', 'user_id', 'shop_id', 'agent_id', 'delivery_boy_id',
                'customer_name', 'customer_phone', 'customer_email', 'shipping_address',
                'division', 'district', 'upazila', 'pourashava', 'ward', 'global_address_id',
                'subtotal', 'discount', 'shipping_fee', 'total_amount', 'payment_method',
                'payment_status', 'order_status', 'delivery_status', 'status', 'transaction_id',
                'created_at', 'updated_at'
            ]);
            $importedOrders += count($ordersToInsert);
        }

        if (!empty($itemsToInsert)) {
            DB::table('order_items')->insert($itemsToInsert);
            $importedItems += count($itemsToInsert);
        }

        $bar->finish();
        $this->newLine();
        $this->info("Import completed successfully!");
        $this->info("Orders imported: {$importedOrders}");
        $this->info("Order Items imported: {$importedItems}");

        return 0;
    }
}
