<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\AdminAuthController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\CustomerController;
use App\Http\Controllers\Admin\CustomerTypeController;
use App\Http\Controllers\Admin\ShopController;
use App\Http\Controllers\Admin\ShopCategoryController;
use App\Http\Controllers\Admin\ShopCommissionController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Admin\ProductColorController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\OrderController;
use App\Http\Controllers\Admin\VendorController;
use App\Http\Controllers\Admin\AgentController;
use App\Http\Controllers\Admin\DeliveryBoyController;
use App\Http\Controllers\Admin\LicenseController;
use App\Http\Controllers\Admin\LedgerController;
use App\Http\Controllers\Admin\CampaignController;
use App\Http\Controllers\Admin\CallingRequestController;
use App\Http\Controllers\Admin\GroupPostController;
use App\Http\Controllers\Admin\SmsPanelController;
use App\Http\Controllers\Admin\SettingController;
use App\Http\Controllers\Admin\WebsiteSettingController;
use App\Http\Controllers\Admin\GlobalAddressController;
use App\Http\Controllers\Admin\BannerController;

Route::prefix('admin')->name('admin.')->group(function () {
    // Auth
    Route::get('/login', [AdminAuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AdminAuthController::class, 'login'])->name('login.post');
    Route::post('/login/submit', [AdminAuthController::class, 'login'])->name('login.submit');
    Route::post('/logout', [AdminAuthController::class, 'logout'])->name('logout');

    // Dashboard
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/dashboard', [DashboardController::class, 'index']);

    // Customers
    Route::get('customers/export', [CustomerController::class, 'export'])->name('customers.export');
    Route::get('customers/{customer}/orders', [CustomerController::class, 'orders'])->name('customers.orders');
    Route::get('customer_order_list/{customer}', [CustomerController::class, 'orders'])->name('customers.orders.legacy');
    Route::post('customers/{customer}/update-credentials', [CustomerController::class, 'updateCredentials'])->name('customers.update_credentials');
    Route::post('customers/{customer}/update-personal', [CustomerController::class, 'updatePersonalInfo'])->name('customers.update_personal');
    Route::post('customers/{customer}/update-address', [CustomerController::class, 'updateAddress'])->name('customers.update_address');
    Route::post('customers/{customer}/update-photo', [CustomerController::class, 'updatePhoto'])->name('customers.update_photo');
    Route::resource('customers', CustomerController::class);
    Route::resource('customer_types', CustomerTypeController::class);

    // Users & Roles
    Route::get('users', [VendorController::class, 'index'])->name('users.index');
    Route::get('roles', [VendorController::class, 'index'])->name('roles.index');

    // Shops
    Route::get('shops/export', [ShopController::class, 'export'])->name('shops.export');
    Route::get('shops/create-legacy', [ShopController::class, 'create'])->name('shops.create_legacy');
    Route::get('shops/{shop}/edit-legacy', [ShopController::class, 'edit'])->name('shops.edit_legacy');
    Route::post('shops/{shop}/status', [ShopController::class, 'updateStatus'])->name('shops.update-status');
    Route::post('shops/{shop}/toggle-status', [ShopController::class, 'updateStatus'])->name('shops.toggle-status');
    Route::post('shops/{shop}/update-personal', [ShopController::class, 'updatePersonalInfo'])->name('shops.update_personal');
    Route::post('shops/{shop}/update-photo', [ShopController::class, 'updatePhoto'])->name('shops.update_photo');
    Route::post('shops/{shop}/update-user', [ShopController::class, 'updateUser'])->name('shops.update_user');
    Route::post('shops/{shop}/update-address', [ShopController::class, 'updateAddress'])->name('shops.update_address');
    Route::post('shops/{shop}/update-category', [ShopController::class, 'updateCategory'])->name('shops.update_category');
    Route::resource('shops', ShopController::class);
    Route::resource('shop_categories', ShopCategoryController::class);
    Route::get('shops_commission', [ShopCommissionController::class, 'index'])->name('shops_commission.index');
    Route::post('shops_commission/update-global', [ShopCommissionController::class, 'updateGlobal'])->name('shops_commission.update-global');
    Route::post('shops_commission/{shop}/update-shop', [ShopCommissionController::class, 'updateShop'])->name('shops_commission.update-shop');

    // Products
    Route::get('products/export', [ProductController::class, 'export'])->name('products.export');
    Route::get('products/create-legacy', [ProductController::class, 'create'])->name('products.create_legacy');
    Route::get('products/{product}/edit-legacy', [ProductController::class, 'edit'])->name('products.edit_legacy');
    Route::get('products/{product}/barcode', [ProductController::class, 'barcode'])->name('products.barcode');
    Route::post('products/{product}/status', [ProductController::class, 'updateStatus'])->name('products.update-status');
    Route::post('products/{product}/toggle-status', [ProductController::class, 'updateStatus'])->name('products.toggle-status');
    Route::resource('products', ProductController::class);
    Route::resource('product_colors', ProductColorController::class);
    Route::resource('product_color', ProductColorController::class);
    Route::resource('categories', CategoryController::class);

    // Orders
    Route::get('orders/export', [OrderController::class, 'export'])->name('orders.export');
    Route::get('orders/{order}/invoice', [OrderController::class, 'invoice'])->name('orders.invoice');
    Route::get('orders/{order}/print', [OrderController::class, 'invoice'])->name('orders.print');
    Route::get('orders/{order}/pos', [OrderController::class, 'pos'])->name('orders.pos');
    Route::post('orders/{order}/status', [OrderController::class, 'updateStatus'])->name('orders.update-status');
    Route::resource('orders', OrderController::class);

    // Vendors & Sellers
    Route::get('vendors/export', [VendorController::class, 'export'])->name('vendors.export');
    Route::get('sellers/create-legacy', [VendorController::class, 'create'])->name('sellers.create_legacy');
    Route::get('sellers/create-legacy-alias', [VendorController::class, 'create'])->name('sellers.store_legacy');
    Route::post('sellers/create-legacy', [VendorController::class, 'store'])->name('sellers.store_legacy.post');
    Route::get('sellers/{vendor}/edit-legacy', [VendorController::class, 'edit'])->name('sellers.edit_legacy');
    Route::get('sellers/{vendor}/orders', [VendorController::class, 'orders'])->name('sellers.orders');
    Route::get('sellers/{vendor}/orders/export', [VendorController::class, 'exportSellerOrders'])->name('sellers.orders.export');
    Route::get('sellers/{vendor}/commission', [VendorController::class, 'commission'])->name('sellers.commission');
    Route::post('sellers/{vendor}/commission', [VendorController::class, 'updateCommission'])->name('sellers.commission.update');
    Route::get('sellers/{vendor}/ledger', [VendorController::class, 'ledger'])->name('sellers.ledger');
    Route::post('sellers/{vendor}/ledger', [VendorController::class, 'storeSellerLedger'])->name('sellers.ledger.store');
    Route::get('sellers/{vendor}/ledger/export', [VendorController::class, 'exportSellerLedger'])->name('sellers.ledger.export');
    Route::post('sellers/{vendor}/update-credentials', [VendorController::class, 'updateCredentials'])->name('sellers.update_credentials');
    Route::post('sellers/{vendor}/update-personal', [VendorController::class, 'updatePersonalInfo'])->name('sellers.update_personal');
    Route::post('sellers/{vendor}/update-address', [VendorController::class, 'updateAddress'])->name('sellers.update_address');
    Route::post('sellers/{vendor}/update-photo', [VendorController::class, 'updatePhoto'])->name('sellers.update_photo');
    Route::post('sellers/{vendor}/toggle-status', [VendorController::class, 'toggleStatus'])->name('sellers.toggle-status');
    Route::post('vendors/{vendor}/toggle-status', [VendorController::class, 'toggleStatus'])->name('vendors.toggle-status');
    Route::resource('vendors', VendorController::class);
    Route::resource('sellers', VendorController::class);

    // Agents
    Route::get('agents/export', [AgentController::class, 'export'])->name('agents.export');
    Route::resource('agents', AgentController::class);

    // Delivery Boys & Riders
    Route::get('delivery_boys/export', [DeliveryBoyController::class, 'export'])->name('delivery_boys.export');
    Route::get('riders/export', [DeliveryBoyController::class, 'export'])->name('riders.export');
    Route::get('delivery_boys/{delivery_boy}/orders', [DeliveryBoyController::class, 'orders'])->name('delivery_boys.orders');
    Route::post('delivery_boys/{delivery_boy}/assign-order', [DeliveryBoyController::class, 'assignOrder'])->name('delivery_boys.assign_order');
    Route::get('delivery_boys/{delivery_boy}/commissions', [DeliveryBoyController::class, 'commissions'])->name('delivery_boys.commissions');
    Route::get('delivery_boys/{delivery_boy}/ledger', [DeliveryBoyController::class, 'ledger'])->name('delivery_boys.ledger');
    Route::post('delivery_boys/{delivery_boy}/add-transaction', [DeliveryBoyController::class, 'addTransaction'])->name('delivery_boys.add_transaction');
    Route::post('delivery_boys/{delivery_boy}/update-credentials', [DeliveryBoyController::class, 'updateCredentials'])->name('delivery_boys.update_credentials');
    Route::post('delivery_boys/{delivery_boy}/update-personal', [DeliveryBoyController::class, 'updatePersonalInfo'])->name('delivery_boys.update_personal');
    Route::post('delivery_boys/{delivery_boy}/update-address', [DeliveryBoyController::class, 'updateAddress'])->name('delivery_boys.update_address');
    Route::post('delivery_boys/{delivery_boy}/update-photo', [DeliveryBoyController::class, 'updatePhoto'])->name('delivery_boys.update_photo');
    Route::resource('delivery_boys', DeliveryBoyController::class);
    Route::resource('riders', DeliveryBoyController::class);

    // Licenses
    Route::get('licenses/export', [LicenseController::class, 'export'])->name('licenses.export');
    Route::get('licenses/{license?}/print', [LicenseController::class, 'print'])->name('licenses.print');
    Route::get('licenses/create-legacy', [LicenseController::class, 'create'])->name('licenses.create_legacy');
    Route::get('licenses/{license}/edit-legacy', [LicenseController::class, 'edit'])->name('licenses.edit_legacy');
    Route::put('licenses/{license}/update-legacy', [LicenseController::class, 'update'])->name('licenses.update_legacy_put');
    Route::post('licenses/{license}/status', [LicenseController::class, 'updateStatus'])->name('licenses.update-status');
    Route::resource('licenses', LicenseController::class);

    // Ledgers & Finance
    Route::resource('ledgers', LedgerController::class);
    Route::resource('ledger', LedgerController::class);

    // Campaigns & Requests & Posts & Banners
    Route::resource('campaigns', CampaignController::class);
    Route::get('calling_requests/poll', [CallingRequestController::class, 'poll'])->name('calling_requests.poll');
    Route::resource('calling_requests', CallingRequestController::class);
    Route::resource('group_posts', GroupPostController::class);
    Route::resource('banners', BannerController::class);

    // SMS Panel
    Route::get('sms_panel', [SmsPanelController::class, 'index'])->name('sms_panel.index');
    Route::post('sms_panel/send', [SmsPanelController::class, 'send'])->name('sms_panel.send');
    Route::post('sms_panel/update-config', [SmsPanelController::class, 'updateConfig'])->name('sms_panel.update_config');

    // Settings
    Route::get('general_settings', [SettingController::class, 'index'])->name('general_settings.index');
    Route::post('general_settings', [SettingController::class, 'update'])->name('general_settings.update');
    Route::resource('settings', SettingController::class)->except(['update']);
    Route::match(['post', 'put', 'patch'], 'settings', [SettingController::class, 'update'])->name('settings.update');

    // Website Settings
    Route::get('website_settings/logo', [WebsiteSettingController::class, 'logo'])->name('website_settings.logo');
    Route::get('website_settings/slider', [WebsiteSettingController::class, 'slider'])->name('website_settings.slider');
    Route::get('website_settings/mobile_slider', [WebsiteSettingController::class, 'mobileSlider'])->name('website_settings.mobile_slider');
    Route::get('website_settings/banner', [WebsiteSettingController::class, 'banner'])->name('website_settings.banner');
    Route::get('website_settings/home_banner', [WebsiteSettingController::class, 'homeBanner'])->name('website_settings.home_banner');
    Route::get('website_settings/banner_product', [WebsiteSettingController::class, 'bannerProduct'])->name('website_settings.banner_product');
    Route::get('website_settings/footer', [WebsiteSettingController::class, 'footer'])->name('website_settings.footer');
    Route::post('website_settings/update', [WebsiteSettingController::class, 'updateSettings'])->name('website_settings.update');
    Route::post('website_settings/banner/store', [WebsiteSettingController::class, 'storeBanner'])->name('website_settings.store_banner');
    Route::post('website_settings/banner/{banner}/destroy', [WebsiteSettingController::class, 'destroyBanner'])->name('website_settings.destroy_banner');
    Route::resource('website_settings', WebsiteSettingController::class);

    // Global Addresses
    Route::resource('global_address', GlobalAddressController::class);
    Route::resource('global_addresses', GlobalAddressController::class);
});
