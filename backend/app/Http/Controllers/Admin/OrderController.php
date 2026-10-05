<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Shop;
use App\Models\Agent;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\StreamedResponse;

class OrderController extends Controller
{
    public function index(Request $request)
    {
        // 1. Status Counts for the navigation tabs
        $statusCounts = [
            'all'          => Order::count(),
            'pending'      => Order::where(function($q) { $q->where('order_status', 'pending')->orWhere('status', '2'); })->count(),
            'accepted'     => Order::where(function($q) { $q->where('delivery_status', 'accepted')->orWhere('order_status', 'accepted'); })->count(),
            'complete'     => Order::where(function($q) { $q->where('delivery_status', 'complete')->orWhere('order_status', 'complete')->orWhere('order_status', 'delivered'); })->count(),
            'not_accepted' => Order::where('delivery_status', 'not_accepted')->whereNotIn('order_status', ['cancelled', 'cancel'])->where('status', '!=', '3')->count(),
            'cancelled'    => Order::where(function($q) { $q->whereIn('order_status', ['cancelled', 'cancel'])->orWhere('status', '3'); })->count(),
        ];

        // 2. Base Query with eager loading
        $query = Order::with(['items.shop', 'items.product', 'user', 'shop', 'agent', 'deliveryBoy', 'invoice']);

        // 3. Status Tab / Filter
        if ($request->filled('status')) {
            $status = strtolower(trim($request->status));
            if ($status === 'pending' || $status === '2') {
                $query->where(function($q) {
                    $q->where('order_status', 'pending')->orWhere('status', '2');
                });
            } elseif ($status === 'accepted' || $status === 'accept') {
                $query->where(function($q) {
                    $q->where('delivery_status', 'accepted')->orWhere('order_status', 'accepted');
                });
            } elseif ($status === 'complete' || $status === 'compliet' || $status === 'delivered') {
                $query->where(function($q) {
                    $q->where('delivery_status', 'complete')
                      ->orWhere('order_status', 'complete')
                      ->orWhere('order_status', 'delivered');
                });
            } elseif ($status === 'not_accepted' || $status === 'not_accept' || $status === 'notaccept') {
                $query->where('delivery_status', 'not_accepted')
                      ->whereNotIn('order_status', ['cancelled', 'cancel'])
                      ->where('status', '!=', '3');
            } elseif ($status === 'cancelled' || $status === 'cancel' || $status === '3') {
                $query->where(function($q) {
                    $q->whereIn('order_status', ['cancelled', 'cancel'])->orWhere('status', '3');
                });
            } else {
                $query->where('order_status', $status);
            }
        }

        // 4. Division Filter (supports name or ID)
        if ($request->filled('division')) {
            $divVal = trim($request->division);
            $divMatches = [$divVal];
            if (is_numeric($divVal) && function_exists('divisionname')) {
                $dName = divisionname((int)$divVal);
                if ($dName) $divMatches[] = $dName;
            } elseif (function_exists('divisionname')) {
                for ($i = 1; $i <= 8; $i++) {
                    if (strcasecmp(divisionname($i), $divVal) === 0) {
                        $divMatches[] = (string)$i;
                        break;
                    }
                }
            }
            $query->whereIn('division', array_unique($divMatches));
        }

        // 5. District Filter (supports name or ID)
        if ($request->filled('district')) {
            $distVal = trim($request->district);
            $distMatches = [$distVal];
            if (is_numeric($distVal) && function_exists('districtname')) {
                $dName = districtname((int)$distVal);
                if ($dName) $distMatches[] = $dName;
            } elseif (function_exists('districtname')) {
                for ($i = 1; $i <= 64; $i++) {
                    if (strcasecmp(districtname($i), $distVal) === 0) {
                        $distMatches[] = (string)$i;
                        break;
                    }
                }
            }
            $query->whereIn('district', array_unique($distMatches));
        }

        // 6. Upazila Filter (supports name or ID)
        if ($request->filled('upazila')) {
            $upzVal = trim($request->upazila);
            $upzMatches = [$upzVal];
            if (is_numeric($upzVal) && function_exists('upazilaname')) {
                $uName = upazilaname((int)$upzVal);
                if ($uName) $upzMatches[] = $uName;
            } elseif (function_exists('upazilaname')) {
                for ($i = 1; $i <= 600; $i++) {
                    $uName = upazilaname($i);
                    if ($uName && strcasecmp($uName, $upzVal) === 0) {
                        $upzMatches[] = (string)$i;
                        break;
                    }
                }
            }
            $query->whereIn('upazila', array_unique($upzMatches));
        }

        // 7. Pourashava / Union Filter
        if ($request->filled('pourashava')) {
            $pours = trim($request->pourashava);
            $pMatches = [$pours];
            if ($pours === 'City Corporation' || $pours === '0' || $pours === '3') {
                $pMatches = ['City Corporation', '0', '3'];
            } elseif ($pours === 'Pourashava' || $pours === '1') {
                $pMatches = ['Pourashava', '1'];
            } elseif ($pours === 'Union' || $pours === '2') {
                $pMatches = ['Union', '2'];
            }
            $query->whereIn('pourashava', $pMatches);
        }

        // 8. Ward Filter
        if ($request->filled('ward')) {
            $w = trim($request->ward);
            $wClean = preg_replace('/[^0-9]/', '', $w);
            $wMatches = [$w];
            if ($wClean) {
                $wMatches[] = $wClean;
                $wMatches[] = 'Ward ' . $wClean;
                $wMatches[] = 'ward ' . $wClean;
            }
            $query->whereIn('ward', array_unique($wMatches));
        }

        // 9. Shop by Order Filter (supports shop_id or shop name)
        if ($request->filled('shop_id') || $request->filled('shop')) {
            $shopFilter = $request->get('shop_id', $request->get('shop'));
            if (!empty($shopFilter) && $shopFilter != '0') {
                $query->where(function($q) use ($shopFilter) {
                    $q->where('orders.shop_id', $shopFilter)
                      ->orWhereExists(function($sub) use ($shopFilter) {
                          $sub->select(DB::raw(1))
                              ->from('order_items')
                              ->whereColumn('order_items.order_id', 'orders.id')
                              ->where('order_items.shop_id', $shopFilter);
                      });
                });
            }
        }

        // 10. Agent Filter (supports agent_id or agent)
        if ($request->filled('agent_id') || $request->filled('agent')) {
            $agentFilter = $request->get('agent_id', $request->get('agent'));
            if (!empty($agentFilter) && $agentFilter != '0') {
                $query->where(function($q) use ($agentFilter) {
                    $q->where('orders.agent_id', $agentFilter)
                      ->orWhereIn('orders.shop_id', function($sub) use ($agentFilter) {
                          $sub->select('sch_id')->from('shops')->where('agent_id', $agentFilter);
                      })
                      ->orWhereIn('orders.global_address_id', function($sub) use ($agentFilter) {
                          $sub->select('global_address_id')->from('agent_permitted_area')->where('agent_id', $agentFilter);
                      })
                      ->orWhereExists(function($sub) use ($agentFilter) {
                          $sub->select(DB::raw(1))
                              ->from('order_items')
                              ->join('shops', 'order_items.shop_id', '=', 'shops.sch_id')
                              ->whereColumn('order_items.order_id', 'orders.id')
                              ->where('shops.agent_id', $agentFilter);
                      });
                });
            }
        }

        // 11. Payment Status
        if ($request->filled('payment_status')) {
            $query->where('payment_status', $request->payment_status);
        }

        // 12. Payment Method
        if ($request->filled('payment_method')) {
            $query->where('payment_method', $request->payment_method);
        }

        // 13. Date Range
        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }
        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        // 14. Customer Filter
        if ($request->filled('customer_id') || $request->filled('customer')) {
            $custId = $request->get('customer_id', $request->get('customer'));
            $query->where(function($q) use ($custId) {
                $q->whereIn('invoice_id', function($sub) use ($custId) {
                    $sub->select('invoice_id')->from('invoice')->where('customer_id', $custId);
                })
                ->orWhere('user_id', $custId);
            });
        }

        // 15. Search Query (Order #, invoice id, customer name, phone, email, trx)
        if ($request->filled('search')) {
            $search = trim($request->search);
            $query->where(function ($q) use ($search) {
                $q->where('order_number', 'like', "%{$search}%")
                  ->orWhere('id', 'like', "%{$search}%")
                  ->orWhere('invoice_id', 'like', "%{$search}%")
                  ->orWhere('customer_name', 'like', "%{$search}%")
                  ->orWhere('customer_phone', 'like', "%{$search}%")
                  ->orWhere('customer_email', 'like', "%{$search}%")
                  ->orWhere('transaction_id', 'like', "%{$search}%");
            });
        }

        // 16. Sorting
        $sort = $request->get('sort', 'latest');
        match ($sort) {
            'oldest'      => $query->oldest(),
            'amount_high' => $query->orderByDesc('total_amount'),
            'amount_low'  => $query->orderBy('total_amount'),
            default       => $query->latest(),
        };

        $orders = $query->paginate(20)->withQueryString();

        // 16. Dropdown Options for Filter UI
        $divisions = [
            'Barisal',
            'Chattagram',
            'Dhaka',
            'Khulna',
            'Mymensingh',
            'Rajshahi',
            'Rangpur',
            'Sylhet',
        ];

        // Districts list (optionally filtered by selected division)
        $districtQuery = Order::select('district')->distinct()->whereNotNull('district')->where('district', '!=', '');
        if ($request->filled('division')) {
            $districtQuery->where('division', $request->division);
        }
        $districts = $districtQuery->pluck('district')->sort()->values()->toArray();

        // Upazilas list (optionally filtered by selected district)
        $upazilaQuery = Order::select('upazila')->distinct()->whereNotNull('upazila')->where('upazila', '!=', '');
        if ($request->filled('district')) {
            $upazilaQuery->where('district', $request->district);
        } elseif ($request->filled('division')) {
            $upazilaQuery->where('division', $request->division);
        }
        $upazilas = $upazilaQuery->pluck('upazila')->sort()->values()->toArray();

        // Pourashavas
        $pourashavas = ['Pourashava', 'Union', 'City Corporation'];

        // Wards (natural sort)
        $rawWards = Order::select('ward')->distinct()->whereNotNull('ward')->where('ward', '!=', '')->pluck('ward')->toArray();
        natsort($rawWards);
        $wards = array_values($rawWards);

        // Shops for "Shop by Order filter"
        $shopIdsWithOrders = Order::select('shop_id')->distinct()->whereNotNull('shop_id')->pluck('shop_id');
        $shops = Shop::whereIn('sch_id', $shopIdsWithOrders)
            ->orderBy('name')
            ->get(['sch_id', 'name']);

        // Agents for "Agent filter"
        $agents = Agent::orderBy('name')->get(['id', 'name', 'phone']);
        if ($agents->isEmpty() && DB::getSchemaBuilder()->hasTable('agent')) {
            $agents = DB::table('agent')->select('agent_id as id', 'agent_name as name', 'mobile as phone')->get();
        }

        // Full hierarchy for instant client-side cascade
        $hierarchy = $this->getLocationHierarchy();

        return view('admin.orders.index', compact(
            'orders',
            'statusCounts',
            'divisions',
            'districts',
            'upazilas',
            'pourashavas',
            'wards',
            'shops',
            'agents',
            'hierarchy'
        ));
    }

    public function show(Order $order)
    {
        $order->load(['items.product.supplier', 'items.shop', 'user', 'shop', 'agent', 'deliveryBoy', 'globalAddress']);
        $legacyInvoice = DB::table('invoice')->where('invoice_id', $order->invoice_id ?? $order->id)->first();
        $legacyPackages = DB::table('package')->where('invoice_id', $order->invoice_id ?? $order->id)->get();
        $settings = Setting::pluck('value', 'label');
        return view('admin.orders.show', compact('order', 'legacyInvoice', 'legacyPackages', 'settings'));
    }

    public function updateStatus(Request $request, Order $order)
    {
        $validated = $request->validate([
            'order_status'   => 'required|string|max:50',
            'delivery_status'=> 'nullable|string|max:50',
            'payment_status' => 'required|in:unpaid,paid,refunded',
            'transaction_id' => 'nullable|string|max:100',
            'notes'          => 'nullable|string|max:1000',
        ]);

        if (empty($validated['delivery_status'])) {
            $st = strtolower($validated['order_status']);
            if (in_array($st, ['delivered', 'complete'])) {
                $validated['delivery_status'] = 'complete';
            } elseif (in_array($st, ['shipped', 'processing', 'accepted'])) {
                $validated['delivery_status'] = 'accepted';
            } else {
                $validated['delivery_status'] = 'not_accepted';
            }
        }

        $order->update($validated);

        return back()->with('success', 'Order #' . $order->order_number . ' updated successfully.');
    }

    public function invoice(Order $order)
    {
        $order->load(['items.product.supplier', 'items.shop', 'user', 'shop', 'agent', 'deliveryBoy', 'globalAddress']);
        $legacyInvoice = DB::table('invoice')->where('invoice_id', $order->invoice_id ?? $order->id)->first();
        $legacyPackages = DB::table('package')->where('invoice_id', $order->invoice_id ?? $order->id)->get();
        $settings = Setting::pluck('value', 'label');

        return view('admin.orders.invoice', compact('order', 'legacyInvoice', 'legacyPackages', 'settings'));
    }

    public function pos(Order $order)
    {
        $order->load(['items.product.supplier', 'items.shop', 'user', 'shop', 'agent', 'deliveryBoy', 'globalAddress']);
        $legacyInvoice = DB::table('invoice')->where('invoice_id', $order->invoice_id ?? $order->id)->first();
        $legacyPackages = DB::table('package')->where('invoice_id', $order->invoice_id ?? $order->id)->get();
        $settings = Setting::pluck('value', 'label');

        return view('admin.orders.pos', compact('order', 'legacyInvoice', 'legacyPackages', 'settings'));
    }

    public function destroy(Order $order)
    {
        $num = $order->order_number;
        $order->items()->delete();
        $order->delete();

        return redirect()->route('admin.orders.index')->with('success', "Order #{$num} was permanently deleted.");
    }

    public function export(Request $request): StreamedResponse
    {
        $query = Order::with(['items.shop', 'shop', 'agent']);

        if ($request->filled('status')) {
            $status = strtolower(trim($request->status));
            if ($status === 'pending' || $status === '2') {
                $query->where(function($q) { $q->where('order_status', 'pending')->orWhere('status', '2'); });
            } elseif ($status === 'accepted' || $status === 'accept') {
                $query->where(function($q) { $q->where('delivery_status', 'accepted')->orWhere('order_status', 'accepted'); });
            } elseif ($status === 'complete' || $status === 'compliet' || $status === 'delivered') {
                $query->where(function($q) { $q->where('delivery_status', 'complete')->orWhere('order_status', 'complete')->orWhere('order_status', 'delivered'); });
            } elseif ($status === 'not_accepted' || $status === 'not_accept') {
                $query->where('delivery_status', 'not_accepted')->whereNotIn('order_status', ['cancelled', 'cancel']);
            } elseif ($status === 'cancelled' || $status === 'cancel' || $status === '3') {
                $query->where(function($q) { $q->whereIn('order_status', ['cancelled', 'cancel'])->orWhere('status', '3'); });
            } else {
                $query->where('order_status', $status);
            }
        }

        if ($request->filled('division')) {
            $query->where('division', $request->division);
        }
        if ($request->filled('district')) {
            $query->where('district', $request->district);
        }
        if ($request->filled('upazila')) {
            $query->where('upazila', $request->upazila);
        }
        if ($request->filled('pourashava')) {
            $query->where('pourashava', $request->pourashava);
        }
        if ($request->filled('ward')) {
            $query->where('ward', $request->ward);
        }
        if ($request->filled('shop_id')) {
            $query->where('shop_id', $request->shop_id);
        }
        if ($request->filled('agent_id')) {
            $query->where('agent_id', $request->agent_id);
        }
        if ($request->filled('payment_status')) {
            $query->where('payment_status', $request->payment_status);
        }

        $headers = [
            'Content-Type'        => 'text/csv',
            'Content-Disposition' => 'attachment; filename="orders_' . date('Ymd_His') . '.csv"',
        ];

        return response()->stream(function () use ($query) {
            $handle = fopen('php://output', 'w');
            fputcsv($handle, [
                'Order #',
                'Invoice ID',
                'Date',
                'Customer Name',
                'Phone',
                'Email',
                'Division',
                'District',
                'Upazila',
                'Pourashava/Union',
                'Ward',
                'Shop',
                'Agent',
                'Items Count',
                'Subtotal',
                'Shipping Fee',
                'Discount',
                'Total Amount',
                'Payment Method',
                'Payment Status',
                'Order Status',
                'Delivery Status',
            ]);

            $query->chunk(100, function ($orders) use ($handle) {
                foreach ($orders as $order) {
                    fputcsv($handle, [
                        $order->order_number,
                        $order->invoice_id,
                        $order->created_at->format('Y-m-d H:i:s'),
                        $order->customer_name,
                        $order->customer_phone,
                        $order->customer_email,
                        $order->division,
                        $order->district,
                        $order->upazila,
                        $order->pourashava,
                        $order->ward,
                        $order->shop ? $order->shop->name : '',
                        $order->agent ? $order->agent->name : '',
                        $order->items->count(),
                        $order->subtotal,
                        $order->shipping_fee,
                        $order->discount,
                        $order->total_amount,
                        $order->payment_method,
                        $order->payment_status,
                        $order->order_status,
                        $order->delivery_status,
                    ]);
                }
            });

            fclose($handle);
        }, 200, $headers);
    }

    /**
     * Builds client-side location hierarchy for instant dependent dropdown cascading
     */
    protected function getLocationHierarchy(): array
    {
        $hierarchy = [];
        $divisions = ['Barisal', 'Chattagram', 'Dhaka', 'Khulna', 'Mymensingh', 'Rajshahi', 'Rangpur', 'Sylhet'];

        foreach ($divisions as $div) {
            $hierarchy[$div] = [];
        }

        // Pull distinct combinations from orders & global_address
        $geoRows = Order::select('division', 'district', 'upazila')
            ->distinct()
            ->whereNotNull('division')
            ->whereNotNull('district')
            ->get();

        foreach ($geoRows as $r) {
            $div = $r->division;
            $dist = $r->district;
            $upz = $r->upazila;

            if (!isset($hierarchy[$div])) {
                $hierarchy[$div] = [];
            }
            if (!isset($hierarchy[$div][$dist])) {
                $hierarchy[$div][$dist] = [];
            }
            if (!empty($upz) && !in_array($upz, $hierarchy[$div][$dist])) {
                $hierarchy[$div][$dist][] = $upz;
            }
        }

        // Also merge from global_helper if available for complete nationwide coverage
        if (function_exists('districtView') && function_exists('upazilasView')) {
            $divMap = [
                1 => 'Chattagram',
                2 => 'Rajshahi',
                3 => 'Khulna',
                4 => 'Barisal',
                5 => 'Sylhet',
                6 => 'Dhaka',
                7 => 'Rangpur',
                8 => 'Mymensingh',
            ];
            $allDistricts = districtView();
            $allUpazilas = upazilasView();

            $distInfo = [];
            foreach ($allDistricts as $d) {
                $dName = $d['name'] ?? null;
                $divName = $divMap[$d['division_id']] ?? null;
                if ($dName && $divName) {
                    $distInfo[$d['id']] = ['div' => $divName, 'dist' => $dName];
                    if (!isset($hierarchy[$divName])) $hierarchy[$divName] = [];
                    if (!isset($hierarchy[$divName][$dName])) $hierarchy[$divName][$dName] = [];
                }
            }

            foreach ($allUpazilas as $u) {
                $uName = $u['name'] ?? null;
                $dId = $u['district_id'] ?? null;
                if ($uName && isset($distInfo[$dId])) {
                    $divName = $distInfo[$dId]['div'];
                    $dName = $distInfo[$dId]['dist'];
                    if (!in_array($uName, $hierarchy[$divName][$dName])) {
                        $hierarchy[$divName][$dName][] = $uName;
                    }
                }
            }
        }

        return $hierarchy;
    }
}
