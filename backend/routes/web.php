<?php

use App\Http\Controllers\FrontendController;
use Illuminate\Support\Facades\Route;

// SEO & Static Assets
Route::get('/sitemap.xml', [FrontendController::class, 'sitemap'])->name('sitemap');
Route::get('/robots.txt', [FrontendController::class, 'robots'])->name('robots');

// Frontend Public Storefront (Vue 3 + SEO SSR meta payload)
Route::get('/', [FrontendController::class, 'index'])->name('home');
Route::get('/shops', [FrontendController::class, 'shops'])->name('frontend.shops');
Route::get('/shops/{idOrSlug}', [FrontendController::class, 'shop'])->name('frontend.shop.show');
Route::get('/products', [FrontendController::class, 'products'])->name('frontend.products');
Route::get('/products/{idOrSlug}', [FrontendController::class, 'product'])->name('frontend.product.show');


// Hyper-local Multi-Shop Duplicate Product SEO Architecture (Hierarchical & Shop-Scoped)
Route::get('/shop/{shopSlug}/{productSlug}', [FrontendController::class, 'shopScopedProduct'])->name('frontend.product.shop-scoped');
Route::get('/{division}/{district}/{upazila}/{shopSlug}/product/{productSlug}', [FrontendController::class, 'hierarchicalProductFull'])->name('frontend.product.hierarchical-full')->where('division', '^(?!super_admin|admin|api).*$');
Route::get('/{district}/{upazila}/{shopSlug}/product/{productSlug}', [FrontendController::class, 'hierarchicalProductDistrictUpazila'])->name('frontend.product.hierarchical-district-upazila')->where('district', '^(?!super_admin|admin|api).*$');
Route::get('/{division}/{district}/{shopSlug}/product/{productSlug}', [FrontendController::class, 'hierarchicalProduct'])->name('frontend.product.hierarchical')->where('division', '^(?!super_admin|admin|api).*$');
Route::get('/{district}/{upazila}/{shopSlug}', [FrontendController::class, 'hierarchicalShopDistrictUpazila'])->name('frontend.shop.hierarchical-district-upazila')->where('district', '^(?!super_admin|admin|api).*$');

Route::get('/categories', [FrontendController::class, 'index'])->name('frontend.categories');
Route::get('/categories/{idOrSlug}', [FrontendController::class, 'category'])->name('frontend.category.show');
Route::get('/popular-products', [FrontendController::class, 'catchAll'])->name('frontend.popular-products');
Route::get('/cart', [FrontendController::class, 'catchAll'])->name('frontend.cart');
Route::get('/checkout', [FrontendController::class, 'catchAll'])->name('frontend.checkout');
Route::get('/order-success/{orderNumber}', [FrontendController::class, 'catchAll'])->name('frontend.order.success');
Route::get('/track-order', [FrontendController::class, 'catchAll'])->name('frontend.order.track');
Route::get('/login', [FrontendController::class, 'catchAll'])->name('login');
Route::get('/register', [FrontendController::class, 'catchAll'])->name('register');
Route::get('/account/{any?}', [FrontendController::class, 'catchAll'])->where('any', '.*');
Route::get('/about', [FrontendController::class, 'catchAll']);
Route::get('/contact', [FrontendController::class, 'catchAll']);
Route::get('/privacy', [FrontendController::class, 'catchAll']);
Route::get('/terms', [FrontendController::class, 'catchAll']);

// Legacy index.php redirect support (e.g. index.php/super_admin/shops)
Route::get('/index.php/{path?}', function ($path = null) {
    return redirect('/' . ($path ?? 'super_admin/shops'));
})->where('path', '.*');

require __DIR__ . '/admin.php';

// ==========================================
// CodeIgniter 4 Business Backpanels
// Super Admin, Shop Admin, Agent
// ==========================================
require __DIR__ . '/ci4_routes.php';

// ==========================================
// Super Admin Legacy & Direct Backpanel Routes
// (Highest precedence to serve 200 responses)
// ==========================================
Route::get('/super_admin/dashboard', [App\Http\Controllers\Admin\DashboardController::class, 'index']);

// 1. Shops Management
Route::get('/super_admin/shops', [App\Http\Controllers\Admin\ShopController::class, 'index']);
Route::get('/super_admin/shops/create', [App\Http\Controllers\Admin\ShopController::class, 'create']);
Route::get('/super_admin/shops_create', [App\Http\Controllers\Admin\ShopController::class, 'create']);
Route::post('/super_admin/shops', [App\Http\Controllers\Admin\ShopController::class, 'store']);
Route::post('/super_admin/shops_create', [App\Http\Controllers\Admin\ShopController::class, 'store']);
Route::get('/super_admin/shops/{id}/edit', [App\Http\Controllers\Admin\ShopController::class, 'edit']);
Route::get('/super_admin/shops_update/{id}', [App\Http\Controllers\Admin\ShopController::class, 'edit']);
Route::get('/super_admin/shops_view/{id}', [App\Http\Controllers\Admin\ShopController::class, 'edit']);
Route::put('/super_admin/shops/{id}', [App\Http\Controllers\Admin\ShopController::class, 'update']);
Route::put('/super_admin/shops_update/{id}', [App\Http\Controllers\Admin\ShopController::class, 'update']);
Route::post('/super_admin/shops/{id}/status', [App\Http\Controllers\Admin\ShopController::class, 'toggleStatus']);
Route::post('/super_admin/shops/{id}/toggle-status', [App\Http\Controllers\Admin\ShopController::class, 'toggleStatus']);
Route::get('/super_admin/shops/export/{format}', [App\Http\Controllers\Admin\ShopController::class, 'export']);
Route::get('/super_admin/shops_commission', [App\Http\Controllers\Admin\ShopCommissionController::class, 'index']);
Route::get('/super_admin/shops_delete/{id}', [App\Http\Controllers\Admin\ShopController::class, 'destroyLegacy'])->where('id', '[0-9]+');

// 2. Products Management
Route::get('/super_admin/products', [App\Http\Controllers\Admin\ProductController::class, 'index']);
Route::get('/super_admin/demo_product_list', [App\Http\Controllers\Admin\ProductController::class, 'index']);
Route::get('/super_admin/products/export/{format}', [App\Http\Controllers\Admin\ProductController::class, 'export']);
Route::get('/super_admin/products/create', [App\Http\Controllers\Admin\ProductController::class, 'create']);
Route::post('/super_admin/products', [App\Http\Controllers\Admin\ProductController::class, 'store']);
Route::get('/super_admin/products/{id}/edit', [App\Http\Controllers\Admin\ProductController::class, 'edit']);
Route::put('/super_admin/products/{id}', [App\Http\Controllers\Admin\ProductController::class, 'update']);
Route::post('/super_admin/products/{product}/status', [App\Http\Controllers\Admin\ProductController::class, 'updateStatus']);
Route::post('/super_admin/products/{product}/toggle-status', [App\Http\Controllers\Admin\ProductController::class, 'toggleStatus']);

// 3. Licenses Management
Route::get('/super_admin/license', [App\Http\Controllers\Admin\LicenseController::class, 'index']);
Route::get('/super_admin/license/create', [App\Http\Controllers\Admin\LicenseController::class, 'create']);
Route::get('/super_admin/license_create', [App\Http\Controllers\Admin\LicenseController::class, 'create']);
Route::post('/super_admin/license', [App\Http\Controllers\Admin\LicenseController::class, 'store']);
Route::post('/super_admin/license_create', [App\Http\Controllers\Admin\LicenseController::class, 'store']);
Route::get('/super_admin/license/{id}/edit', [App\Http\Controllers\Admin\LicenseController::class, 'edit']);
Route::get('/super_admin/license_update/{id}', [App\Http\Controllers\Admin\LicenseController::class, 'edit']);
Route::put('/super_admin/license/{id}', [App\Http\Controllers\Admin\LicenseController::class, 'update']);
Route::put('/super_admin/license_update/{id}', [App\Http\Controllers\Admin\LicenseController::class, 'update']);
Route::post('/super_admin/license_update_action/{id?}', [App\Http\Controllers\Admin\LicenseController::class, 'update']);
Route::get('/super_admin/license/export/{format}', [App\Http\Controllers\Admin\LicenseController::class, 'export']);
Route::get('/super_admin/license_print/{id?}', [App\Http\Controllers\Admin\LicenseController::class, 'print']);
Route::patch('/super_admin/license/{id}/status', [App\Http\Controllers\Admin\LicenseController::class, 'updateStatus']);
Route::post('/super_admin/license/{id}/status', [App\Http\Controllers\Admin\LicenseController::class, 'updateStatus']);

// 4. Vendors & Sellers Management
Route::get('/super_admin/vendors', [App\Http\Controllers\Admin\VendorController::class, 'index']);
Route::get('/super_admin/vendors/create', [App\Http\Controllers\Admin\VendorController::class, 'create']);
Route::post('/super_admin/vendors', [App\Http\Controllers\Admin\VendorController::class, 'store']);
Route::get('/super_admin/sellers', [App\Http\Controllers\Admin\VendorController::class, 'index']);
Route::get('/super_admin/sellers/export/{format}', [App\Http\Controllers\Admin\VendorController::class, 'export']);
Route::get('/super_admin/sellers_create', [App\Http\Controllers\Admin\VendorController::class, 'create']);
Route::post('/super_admin/sellers_create', [App\Http\Controllers\Admin\VendorController::class, 'store']);
Route::get('/super_admin/sellers_update/{id}', [App\Http\Controllers\Admin\VendorController::class, 'edit']);
Route::post('/super_admin/sellers_update_action/{id}', [App\Http\Controllers\Admin\VendorController::class, 'updateCredentials']);
Route::post('/super_admin/sellers_personal_update/{id}', [App\Http\Controllers\Admin\VendorController::class, 'updatePersonalInfo']);
Route::post('/super_admin/sellers_address_update/{id}', [App\Http\Controllers\Admin\VendorController::class, 'updateAddress']);
Route::post('/super_admin/sellers_photo_update/{id}', [App\Http\Controllers\Admin\VendorController::class, 'updatePhoto']);
Route::post('/super_admin/sellers/{id}/status', [App\Http\Controllers\Admin\VendorController::class, 'toggleStatus']);
Route::get('/super_admin/sellers_order/{id}', [App\Http\Controllers\Admin\VendorController::class, 'orders']);
Route::get('/super_admin/sellers_order/{id}/export/{format}', [App\Http\Controllers\Admin\VendorController::class, 'exportSellerOrders']);
Route::get('/super_admin/sellers_ledger/{id}', [App\Http\Controllers\Admin\VendorController::class, 'ledger']);
Route::post('/super_admin/sellers_ledger/{id}', [App\Http\Controllers\Admin\VendorController::class, 'storeSellerLedger']);
Route::get('/super_admin/sellers_ledger/{id}/export/{format}', [App\Http\Controllers\Admin\VendorController::class, 'exportSellerLedger']);
Route::get('/super_admin/sellers_commission/{id}', [App\Http\Controllers\Admin\VendorController::class, 'commission']);
Route::post('/super_admin/sellers_commission/{id}', [App\Http\Controllers\Admin\VendorController::class, 'updateCommission']);

// Order Legacy Endpoints
Route::get('/super_admin/order', [App\Http\Controllers\Admin\OrderController::class, 'index']);
Route::get('/super_admin/order/pandding', function() {
    return redirect()->route('admin.orders.index', ['status' => 'pending']);
});
Route::get('/super_admin/order/accepted', function() {
    return redirect()->route('admin.orders.index', ['status' => 'accepted']);
});
Route::get('/super_admin/order/complete', function() {
    return redirect()->route('admin.orders.index', ['status' => 'complete']);
});
Route::get('/super_admin/order/notaccepted', function() {
    return redirect()->route('admin.orders.index', ['status' => 'not_accepted']);
});
Route::get('/super_admin/order/cancel', function() {
    return redirect()->route('admin.orders.index', ['status' => 'cancelled']);
});
Route::get('/order/invoice/{id}', function($id) {
    $order = App\Models\Order::where('id', $id)->orWhere('invoice_id', $id)->firstOrFail();
    return app(App\Http\Controllers\Admin\OrderController::class)->invoice($order);
});
Route::get('/super_admin/order/invoice/{id}', function($id) {
    $order = App\Models\Order::where('id', $id)->orWhere('invoice_id', $id)->firstOrFail();
    return app(App\Http\Controllers\Admin\OrderController::class)->invoice($order);
});
Route::get('/super_admin/index.php/order/invoice/{id}', function($id) {
    $order = App\Models\Order::where('id', $id)->orWhere('invoice_id', $id)->firstOrFail();
    return app(App\Http\Controllers\Admin\OrderController::class)->invoice($order);
});
Route::get('/super_admin/order_invoice/{id}', function($id) {
    $order = App\Models\Order::where('id', $id)->orWhere('invoice_id', $id)->firstOrFail();
    return app(App\Http\Controllers\Admin\OrderController::class)->invoice($order);
});
Route::get('/super_admin/customer_order_list/{customer}', [App\Http\Controllers\Admin\CustomerController::class, 'orders'])->name('legacy.customer_order_list');


// 5. Additional Legacy Endpoints Matching Super Sidebar Navigation
Route::get('/super_admin/shop_category', [App\Http\Controllers\Admin\ShopCategoryController::class, 'index']);
Route::get('/super_admin/shop_category_create', [App\Http\Controllers\Admin\ShopCategoryController::class, 'create']);
Route::post('/super_admin/shop_category_create_action', [App\Http\Controllers\Admin\ShopCategoryController::class, 'store']);
Route::get('/super_admin/shop_category_update/{id}', [App\Http\Controllers\Admin\ShopCategoryController::class, 'edit']);
Route::post('/super_admin/shop_category_update_action', [App\Http\Controllers\Admin\ShopCategoryController::class, 'updateLegacy']);
Route::post('/super_admin/shop_category_others_action', [App\Http\Controllers\Admin\ShopCategoryController::class, 'othersActionLegacy']);
Route::get('/super_admin/shop_category_delete/{id}', [App\Http\Controllers\Admin\ShopCategoryController::class, 'destroy']);
Route::get('/super_admin/users', [App\Http\Controllers\Admin\VendorController::class, 'index']);
Route::get('/super_admin/roles', [App\Http\Controllers\Admin\VendorController::class, 'index']);
Route::get('/super_admin/super_gen_settings', [App\Http\Controllers\Admin\SettingController::class, 'index']);
Route::get('/super_admin/super_commission', [App\Http\Controllers\Admin\ShopCommissionController::class, 'index']);
Route::get('/super_admin/ledger_super', [App\Http\Controllers\Admin\LedgerController::class, 'index']);
Route::get('/super_admin/customer_type', [App\Http\Controllers\Admin\CustomerTypeController::class, 'index']);
Route::get('/super_admin/customers', [App\Http\Controllers\Admin\CustomerController::class, 'index']);
Route::get('/super_admin/customer_update/{id}', [App\Http\Controllers\Admin\CustomerController::class, 'edit']);
Route::post('/super_admin/customer_update_action/{id?}', [App\Http\Controllers\Admin\CustomerController::class, 'updateCredentials']);
Route::post('/super_admin/customer_personal_update/{id?}', [App\Http\Controllers\Admin\CustomerController::class, 'updatePersonalInfo']);
Route::post('/super_admin/customer_address_update/{id?}', [App\Http\Controllers\Admin\CustomerController::class, 'updateAddress']);
Route::post('/super_admin/customer_photo_update/{id?}', [App\Http\Controllers\Admin\CustomerController::class, 'updatePhoto']);
Route::post('/customers/update_action', [App\Http\Controllers\Admin\CustomerController::class, 'updateCredentials']);
Route::post('/customers/actionPersonal', [App\Http\Controllers\Admin\CustomerController::class, 'updatePersonalInfo']);
Route::post('/customers/actionAddress', [App\Http\Controllers\Admin\CustomerController::class, 'updateAddress']);
Route::post('/customers/actionPhoto', [App\Http\Controllers\Admin\CustomerController::class, 'updatePhoto']);
Route::get('/super_admin/order', [App\Http\Controllers\Admin\OrderController::class, 'index']);
Route::get('/super_admin/calling_request', [App\Http\Controllers\Admin\CallingRequestController::class, 'index']);
Route::get('/super_admin/seller', [App\Http\Controllers\Admin\VendorController::class, 'index']);
Route::get('/super_admin/delivery_boy', [App\Http\Controllers\Admin\DeliveryBoyController::class, 'index']);
Route::get('/super_admin/agent', [App\Http\Controllers\Admin\AgentController::class, 'index']);
Route::get('/super_admin/global_address', [App\Http\Controllers\Admin\GlobalAddressController::class, 'index']);
Route::get('/super_admin/demo_category', [App\Http\Controllers\Admin\CategoryController::class, 'index']);
Route::get('/super_admin/demo_products', [App\Http\Controllers\Admin\ProductController::class, 'index']);
Route::get('/super_admin/product_color', [App\Http\Controllers\Admin\ProductColorController::class, 'index']);
Route::get('/super_admin/campaign', [App\Http\Controllers\Admin\CampaignController::class, 'index']);
Route::get('/super_admin/group_post', [App\Http\Controllers\Admin\GroupPostController::class, 'index']);
Route::get('/super_admin/sms_panel', [App\Http\Controllers\Admin\SmsPanelController::class, 'index']);
Route::get('/super_admin/gen_settings', [App\Http\Controllers\Admin\SettingController::class, 'index']);

// Catch-all fallback for other unmapped legacy super_admin URLs
Route::get('/super_admin/{any?}', fn($any = null) => redirect('/admin/' . ($any ?? 'shops')))->where('any', '.*');
