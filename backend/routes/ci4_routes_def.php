<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
//$routes->setAutoRoute(true);

$routes->get('/', 'Home::index');


//super admin login
$routes->get('super_admin', 'Super_admin\Login::index');
$routes->post('super_admin/login_action', 'Super_admin\Login::login_action');
$routes->get('super_admin/logout', 'Super_admin\Login::logout');
$routes->get('super_admin/dashboard', 'Super_admin\Dashboard::index');
//super admin login

//super admin Shops
$routes->get('super_admin/shops', 'Super_admin\Shops::index');
$routes->get('super_admin/shops_create', 'Super_admin\Shops::create');
$routes->post('super_admin/shops_create_action', 'Super_admin\Shops::create_action');
$routes->get('super_admin/shops_view/(:num)', 'Super_admin\Shops::view/$1');
$routes->get('super_admin/shops_update/(:num)', 'Super_admin\Shops::update/$1');
$routes->post('super_admin/shops_general_update', 'Super_admin\Shops::general_update');
$routes->post('super_admin/shops_personal_update', 'Super_admin\Shops::personal_update');
$routes->post('super_admin/shops_photo_update', 'Super_admin\Shops::photo_update');
$routes->post('super_admin/shops_user_update', 'Super_admin\Shops::user_update');
$routes->post('super_admin/shops_category_update', 'Super_admin\Shops::category_update');
$routes->post('super_admin/shops_address_action', 'Super_admin\Shops::address_action');
$routes->get('super_admin/shops_delete(:num)', 'Super_admin\Shops::delete/$1');

$routes->post('super_admin/shops_search', 'Super_admin\Shops::search');
$routes->post('super_admin/shops_sub_category', 'Super_admin\Shops::get_sub_category');

$routes->post('super_admin/search_district', 'Super_admin\Shops::search_district');
$routes->post('super_admin/search_upazila', 'Super_admin\Shops::search_upazila');
//super admin Shops

//super admin shop category
$routes->get('super_admin/shop_category', 'Super_admin\Shopcategory::index');
$routes->get('super_admin/shop_category_create', 'Super_admin\Shopcategory::create');
$routes->post('super_admin/shop_category_create_action', 'Super_admin\Shopcategory::create_action');
$routes->get('super_admin/shop_category_update/(:num)', 'Super_admin\Shopcategory::update/$1');
$routes->post('super_admin/shop_category_update_action', 'Super_admin\Shopcategory::update_action');
$routes->post('super_admin/shop_category_others_action', 'Super_admin\Shopcategory::others_action');
$routes->get('super_admin/shop_category_delete/(:num)', 'Super_admin\Shopcategory::delete/$1');
//super admin shop category

//super admin License
$routes->get('super_admin/license', 'Super_admin\License::index');
$routes->get('super_admin/license_create', 'Super_admin\License::create');
$routes->post('super_admin/license_create_action', 'Super_admin\License::create_action');
$routes->get('super_admin/license_update/(:num)', 'Super_admin\License::update/$1');
$routes->post('super_admin/license_update_action', 'Super_admin\License::update_action');
//super admin License

//super admin Settings
$routes->get('super_admin/settings', 'Super_admin\Settings::index');
$routes->get('super_admin/settings_update/(:num)', 'Super_admin\Settings::update/$1');
$routes->post('super_admin/settings_update_action', 'Super_admin\Settings::update_action');
$routes->post('super_admin/settings_personal_update', 'Super_admin\Settings::personal_action');
$routes->post('super_admin/settings_photo_update', 'Super_admin\Settings::photo_action');
//super admin Settings

//super admin Settings
$routes->get('super_admin/shops_commission', 'Super_admin\Shopscommission::index');
$routes->post('super_admin/shops_commission_address_search', 'Super_admin\Shopscommission::address_search');
$routes->get('super_admin/shops_commission_unpaid_list/(:num)', 'Super_admin\Shopscommission::unpaid_list/$1');
$routes->get('super_admin/shops_commission_paid_list/(:num)', 'Super_admin\Shopscommission::paid_list/$1');
$routes->get('super_admin/shops_commission_pay_list/(:num)', 'Super_admin\Shopscommission::pay_list/$1');
$routes->get('super_admin/shops_commission_cancel/(:num)', 'Super_admin\Shopscommission::cancel/$1');
$routes->post('super_admin/shops_commission_confirm', 'Super_admin\Shopscommission::commission_confirm');
//super admin Settings

//super admin Ledger
$routes->get('super_admin/ledger', 'Super_admin\Ledger::index');
$routes->post('super_admin/ledger_filter', 'Super_admin\Ledger::ledger_filter');
//super admin Ledger

//super admin Ledger
$routes->get('super_admin/customer_type', 'Super_admin\Customertype::index');
$routes->get('super_admin/customer_type_create', 'Super_admin\Customertype::create');
$routes->post('super_admin/customer_type_action', 'Super_admin\Customertype::create_action');
$routes->get('super_admin/customer_type_update/(:num)', 'Super_admin\Customertype::update/$1');
$routes->post('super_admin/customer_type_update_action', 'Super_admin\Customertype::update_action');
$routes->get('super_admin/customer_type_delete/(:num)', 'Super_admin\Customertype::delete/$1');
//super admin Ledger

//super admin customer
$routes->get('super_admin/customer', 'Super_admin\Customer::index');
$routes->get('super_admin/customer_create', 'Super_admin\Customer::create');
$routes->post('super_admin/customer_action', 'Super_admin\Customer::create_action');
$routes->get('super_admin/customer_update/(:num)', 'Super_admin\Customer::update/$1');
$routes->post('super_admin/customer_update_action', 'Super_admin\Customer::update_action');
$routes->post('super_admin/customer_personal_update', 'Super_admin\Customer::personal_update');
$routes->post('super_admin/customer_address_update', 'Super_admin\Customer::address_update');
$routes->post('super_admin/customer_photo_update', 'Super_admin\Customer::photo_update');
$routes->get('super_admin/customer_delete/(:num)', 'Super_admin\Customer::delete/$1');
$routes->get('super_admin/customer_order_list/(:num)', 'Super_admin\Customer::order_list/$1');
$routes->post('super_admin/customer_filter', 'Super_admin\Customer::filter');
//super admin customer

//super admin Order
$routes->get('super_admin/order', 'Super_admin\Order::index');
$routes->get('super_admin/order_invoice/(:num)', 'Super_admin\Order::invoice/$1');
$routes->get('super_admin/order_filter_status/(:num)', 'Super_admin\Order::order_filter_status/$1');
$routes->get('super_admin/order_filter/(:num)', 'Super_admin\Order::order_filter/$1');
$routes->get('super_admin/order_filter_not_accepted', 'Super_admin\Order::order_filter_not_accepted');
$routes->post('super_admin/order_filter_shop', 'Super_admin\Order::order_filter_shop');
$routes->post('super_admin/order_filter_invoice', 'Super_admin\Order::order_filter_invoice');

//super admin Order

//super admin Calling request
$routes->get('super_admin/calling_request', 'Super_admin\Callingrequest::index');
$routes->post('super_admin/calling_status_update', 'Super_admin\Callingrequest::status_update');
$routes->post('super_admin/calling_search_agent', 'Super_admin\Callingrequest::search_agent');
$routes->post('super_admin/calling_search_address', 'Super_admin\Callingrequest::search_address');
//super admin Calling request

//super admin Sellers
$routes->get('super_admin/sellers', 'Super_admin\Sellers::index');
$routes->get('super_admin/sellers_create', 'Super_admin\Sellers::create');
$routes->post('super_admin/sellers_action', 'Super_admin\Sellers::create_action');
$routes->get('super_admin/sellers_update/(:num)', 'Super_admin\Sellers::update/$1');
$routes->post('super_admin/sellers_update_action', 'Super_admin\Sellers::update_action');
$routes->post('super_admin/sellers_personal_update_action', 'Super_admin\Sellers::personal_action');
$routes->post('super_admin/sellers_address_update_action', 'Super_admin\Sellers::address_action');
$routes->post('super_admin/sellers_photo_update_action', 'Super_admin\Sellers::photo_action');
$routes->get('super_admin/sellers_delete/(:num)', 'Super_admin\Sellers::delete/$1');
$routes->get('super_admin/sellers_ledger/(:num)', 'Super_admin\Sellers::ledger/$1');
$routes->get('super_admin/sellers_commission/(:num)', 'Super_admin\Sellers::commission/$1');
$routes->get('super_admin/sellers_order/(:num)', 'Super_admin\Sellers::sellers_order/$1');
$routes->post('super_admin/sellers_order_data', 'Super_admin\Sellers::sellers_order_data');
$routes->post('super_admin/commission_data', 'Super_admin\Sellers::commission_data');
$routes->post('super_admin/commission_data_total', 'Super_admin\Sellers::commission_data_total');
$routes->post('super_admin/commission_all', 'Super_admin\Sellers::commission_all');
$routes->post('super_admin/sellers_filter', 'Super_admin\Sellers::filter');
//super admin Sellers

//super admin Delivery Boy
$routes->get('super_admin/delivery_boy', 'Super_admin\Deliveryboy::index');
$routes->get('super_admin/delivery_boy_create', 'Super_admin\Deliveryboy::create');
$routes->post('super_admin/delivery_boy_action', 'Super_admin\Deliveryboy::create_action');
$routes->get('super_admin/delivery_boy_update/(:num)', 'Super_admin\Deliveryboy::update/$1');
$routes->post('super_admin/delivery_boy_update_action', 'Super_admin\Deliveryboy::update_action');
$routes->post('super_admin/delivery_boy_personal_update', 'Super_admin\Deliveryboy::personal_update');
$routes->post('super_admin/delivery_boy_address_update', 'Super_admin\Deliveryboy::address_update');
$routes->post('super_admin/delivery_boy_photo_update', 'Super_admin\Deliveryboy::photo_update');
$routes->get('super_admin/delivery_boy_delete/(:num)', 'Super_admin\Deliveryboy::delete/$1');
$routes->get('super_admin/delivery_boy_ledger/(:num)', 'Super_admin\Deliveryboy::ledger/$1');
$routes->get('super_admin/delivery_boy_commission/(:num)', 'Super_admin\Deliveryboy::commission/$1');
$routes->get('super_admin/delivery_boy_order/(:num)', 'Super_admin\Deliveryboy::delivery_boy_order/$1');
$routes->post('super_admin/delivery_boy_commission_data', 'Super_admin\Deliveryboy::commission_data');
$routes->post('super_admin/delivery_boy_commission_data_total', 'Super_admin\Deliveryboy::commission_data_total');
$routes->post('super_admin/delivery_boy_commission_all', 'Super_admin\Deliveryboy::commission_all');
$routes->post('super_admin/delivery_boy_filter', 'Super_admin\Deliveryboy::filter');
//super admin Delivery Boy

//super admin Agent
$routes->get('super_admin/agent', 'Super_admin\Agent::index');
$routes->get('super_admin/agent_create', 'Super_admin\Agent::create');
$routes->post('super_admin/agent_create_action', 'Super_admin\Agent::create_action');
$routes->get('super_admin/agent_update/(:num)', 'Super_admin\Agent::update/$1');
$routes->post('super_admin/agent_update', 'Super_admin\Agent::update_action');
$routes->post('super_admin/agent_address_update', 'Super_admin\Agent::address_update');
$routes->post('super_admin/agent_area_update', 'Super_admin\Agent::area_update');
$routes->get('super_admin/agent_delete/(:num)', 'Super_admin\Agent::delete/$1');
$routes->get('super_admin/agent_commission/(:num)', 'Super_admin\Agent::commission/$1');
$routes->get('super_admin/delete_area/(:num)/(:num)', 'Super_admin\Agent::delete_area/$1/$2');
$routes->post('super_admin/agent_filter', 'Super_admin\Agent::filter');
$routes->get('super_admin/agent_login/(:num)', 'Super_admin\Agent::login/$1');
//super admin Agent

//super admin Globaladdress
$routes->get('super_admin/global_address', 'Super_admin\Globaladdress::index');
$routes->get('super_admin/global_address_create', 'Super_admin\Globaladdress::create');
$routes->post('super_admin/global_address_create_action', 'Super_admin\Globaladdress::create_action');
$routes->get('super_admin/global_address_update/(:num)', 'Super_admin\Globaladdress::update/$1');
$routes->post('super_admin/global_address_update_action', 'Super_admin\Globaladdress::update_action');
$routes->get('super_admin/global_address_delete/(:num)', 'Super_admin\Globaladdress::delete/$1');
$routes->post('super_admin/global_address_search', 'Super_admin\Globaladdress::search');
//super admin Globaladdress

//super admin demo product category
$routes->get('super_admin/demo_product_category', 'Super_admin\Demoproduccategory::index');
$routes->get('super_admin/demo_product_category_create', 'Super_admin\Demoproduccategory::create');
$routes->post('super_admin/demo_product_category_create_action', 'Super_admin\Demoproduccategory::create_action');
$routes->get('super_admin/demo_product_category_update/(:num)', 'Super_admin\Demoproduccategory::update/$1');
$routes->post('super_admin/demo_product_category_update_action', 'Super_admin\Demoproduccategory::update_action');
$routes->get('super_admin/demo_product_category_delete/(:num)', 'Super_admin\Demoproduccategory::delete/$1');
//super admin demo product category

//super admin demo product category
$routes->get('super_admin/demo_product', 'Super_admin\Demoproducts::index');
$routes->get('super_admin/demo_product_list', 'Super_admin\Demoproducts::product_list');
$routes->post('super_admin/demo_product_create_action', 'Super_admin\Demoproducts::create_action');
$routes->post('super_admin/demo_product_add_cart', 'Super_admin\Demoproducts::addCart');
$routes->post('super_admin/demo_product_remove_cart', 'Super_admin\Demoproducts::remove_cart');
$routes->post('super_admin/demo_product_clearCart', 'Super_admin\Demoproducts::clearCart');
$routes->post('super_admin/demo_product_get_sub_cat', 'Super_admin\Demoproducts::get_sub_cat');
$routes->get('super_admin/demo_product_update/(:num)', 'Super_admin\Demoproducts::update/$1');
$routes->post('super_admin/demo_product_update_action', 'Super_admin\Demoproducts::update_action');
$routes->post('super_admin/demo_product_photo_action', 'Super_admin\Demoproducts::photo_action');
$routes->get('super_admin/demo_product_delete/(:num)', 'Super_admin\Demoproducts::delete/$1');
$routes->post('super_admin/demo_product_search_by_key', 'Super_admin\Demoproducts::search_keyword');
$routes->post('super_admin/demo_product_search_by_cat', 'Super_admin\Demoproducts::search_category');

$routes->post('super_admin/demo_product_price_update', 'Super_admin\Demoproducts::price_update');
$routes->get('super_admin/demo_product_bulk_upload', 'Super_admin\Demoproducts::bulk_upload');
$routes->post('super_admin/demo_product_bulk_upload_action', 'Super_admin\Demoproducts::bulk_upload_action');
//super admin demo product category

//super admin demo product color
$routes->get('super_admin/product_color', 'Super_admin\Productcolor::index');
$routes->get('super_admin/product_color_create', 'Super_admin\Productcolor::create');
$routes->post('super_admin/product_color_create_action', 'Super_admin\Productcolor::create_action');
$routes->get('super_admin/product_color_update/(:num)', 'Super_admin\Productcolor::update/$1');
$routes->post('super_admin/product_color_update_action', 'Super_admin\Productcolor::update_action');
$routes->get('super_admin/product_color_delete/(:num)', 'Super_admin\Productcolor::delete/$1');
//super admin demo product color

//super admin demo Campaign
$routes->get('super_admin/campaign', 'Super_admin\Campaign::index');
$routes->post('super_admin/campaign_status_update', 'Super_admin\Campaign::status_update');
$routes->get('super_admin/campaign_update/(:num)', 'Super_admin\Campaign::update/$1');
$routes->post('super_admin/campaign_update_action', 'Super_admin\Campaign::update_action');
//super admin demo Campaign

//super admin demo Campaign
$routes->get('super_admin/group_post', 'Super_admin\Grouppost::index');
$routes->get('super_admin/group_post_update/(:num)', 'Super_admin\Grouppost::update/$1');
$routes->post('super_admin/group_post_update_action', 'Super_admin\Grouppost::update_action');
$routes->get('super_admin/group_post_delete/(:num)', 'Super_admin\Grouppost::delete/$1');
//super admin demo Campaign

//super admin Website settings
$routes->get('super_admin/website_settings/logo', 'Super_admin\Websitesettings::logo');
$routes->post('super_admin/website_settings/logo_action', 'Super_admin\Websitesettings::logo_action');
$routes->get('super_admin/website_settings/banner', 'Super_admin\Websitesettings::banner');
$routes->post('super_admin/website_settings/banner_action_left', 'Super_admin\Websitesettings::banner_action_left');
$routes->post('super_admin/website_settings/banner_action_right', 'Super_admin\Websitesettings::banner_action_right');
$routes->post('super_admin/website_settings/banner_action_top', 'Super_admin\Websitesettings::banner_action_top');
$routes->post('super_admin/website_settings/banner_action_bottom', 'Super_admin\Websitesettings::banner_action_bottom');
$routes->get('super_admin/website_settings/banner_product', 'Super_admin\Websitesettings::banner_product');
$routes->post('super_admin/website_settings/banner_product_action', 'Super_admin\Websitesettings::banner_product_action');
$routes->get('super_admin/website_settings/slider', 'Super_admin\Websitesettings::slider');
$routes->post('super_admin/website_settings/slider_action', 'Super_admin\Websitesettings::slider_action');
$routes->get('super_admin/website_settings/footer', 'Super_admin\Websitesettings::footer');
$routes->post('super_admin/website_settings/footer_action', 'Super_admin\Websitesettings::footer_action');
$routes->get('super_admin/website_settings/mobile_slider', 'Super_admin\Websitesettings::mobile_slider');
$routes->get('super_admin/website_settings/home_banner', 'Super_admin\Websitesettings::home_banner');
$routes->post('super_admin/website_settings/home_banner_action', 'Super_admin\Websitesettings::home_banner_action');
$routes->post('super_admin/website_settings/home_banner_small_action', 'Super_admin\Websitesettings::home_banner_small_action');
//super admin Website settings

//super admin Sms panel
$routes->get('super_admin/sms_panel', 'Super_admin\Smspanel::index');
$routes->post('super_admin/update_status', 'Super_admin\Smspanel::update_status');
//super admin Sms panel

//super admin Sms panel
$routes->get('super_admin/general_settings', 'Super_admin\Generalsettings::index');
$routes->post('super_admin/update_action', 'Super_admin\Generalsettings::update_action');










$routes->group('shop_admin',['filter' => 'AlreadyLoggedIn'], function ($routes) {
    // shop admin login
    $routes->get('/', 'Shop_admin\Login::index');
    $routes->get('login', 'Shop_admin\Login::index');
    $routes->post('login_action', 'Shop_admin\Login::login_action');

    $routes->get('forgot_password', 'Shop_admin\Login::forgotPassword');
    $routes->post('reset_link', 'Shop_admin\Login::reset_link');
    $routes->get('otp', 'Shop_admin\Login::otp');
    $routes->post('otp_action', 'Shop_admin\Login::otp_action');
    $routes->get('otp_action', 'Shop_admin\Login::reset_password');
    $routes->get('reset_password', 'Shop_admin\Login::reset_password');
    $routes->post('reset_password_action', 'Shop_admin\Login::reset_password_action');
    // shop admin login end
});


$routes->group('shop_admin',['filter' => 'AuthCheck'], function ($routes) {
    $routes->get('logout', 'Shop_admin\Login::logout');

    // shop admin Dashboard
    $routes->get('dashboard', 'Shop_admin\Dashboard::index');
    $routes->post('opening_status', 'Shop_admin\Dashboard::opening_status');
    // shop admin Dashboard end

    //shop admin Bakir hishab
    $routes->get('bakir_hishab', 'Shop_admin\BakirHishab::index');
    $routes->get('bakir_hishab_create','Shop_admin\BakirHishab::create');
    $routes->post('bakir_hishab_create_action', 'Shop_admin\BakirHishab::create_action');
    $routes->post('bakir_hishab/lonProvData', 'Shop_admin\BakirHishab::lonProvData');
    // shop admin Dashboard end

    //Bank route
    $routes->get('bank', 'Shop_admin\Bank::index');
    $routes->get('bank_create', 'Shop_admin\Bank::create');
    $routes->post('bank_create_action', 'Shop_admin\Bank::create_action');
    $routes->get('bank_update/(:num)', 'Shop_admin\Bank::update/$1');
    $routes->post('bank_update_action', 'Shop_admin\Bank::update_action');
    $routes->get('bank_delete/(:num)', 'Shop_admin\Bank::delete/$1');
    //Bank route

    //Bank Deposit
    $routes->get('bank_deposit', 'Shop_admin\BankDeposit::index');
    $routes->get('bank_deposit_create', 'Shop_admin\BankDeposit::create');
    $routes->post('bank_deposit_create_action', 'Shop_admin\BankDeposit::create_action');
    //Bank Deposit

    //Bank Deposit
    $routes->get('bank_withdraw', 'Shop_admin\BankWithdraw::index');
    $routes->get('bank_withdraw_create', 'Shop_admin\BankWithdraw::create');
    $routes->post('bank_withdraw_create_action', 'Shop_admin\BankWithdraw::create_action');
    //Bank Deposit

    //Chaque
    $routes->get('chaque', 'Shop_admin\Chaque::index');
    $routes->post('chaque_paid', 'Shop_admin\Chaque::paid');
    //Chaque

    //Suppliers route
    $routes->get('suppliers', 'Shop_admin\Suppliers::index');
    $routes->get('suppliers_create', 'Shop_admin\Suppliers::create');
    $routes->post('suppliers_create_action', 'Shop_admin\Suppliers::create_action');
    $routes->get('suppliers_update/(:num)', 'Shop_admin\Suppliers::update/$1');
    $routes->post('suppliers_update_action', 'Shop_admin\Suppliers::update_action');
    $routes->get('suppliers_delete/(:num)', 'Shop_admin\Suppliers::delete/$1');
    $routes->get('suppliers_products/(:num)', 'Shop_admin\Suppliers::products/$1');
    $routes->get('suppliers_transaction/(:num)', 'Shop_admin\Suppliers::transaction/$1');
    //Suppliers route

    //Loan provider route
    $routes->get('loan_provider', 'Shop_admin\Loan_provider::index');
    $routes->get('loan_provider_create', 'Shop_admin\Loan_provider::create');
    $routes->post('loan_provider_create_action', 'Shop_admin\Loan_provider::create_action');
    $routes->get('loan_provider_update/(:num)', 'Shop_admin\Loan_provider::update/$1');
    $routes->post('loan_provider_update_action', 'Shop_admin\Loan_provider::update_action');
    $routes->get('loan_provider_delete/(:num)', 'Shop_admin\Loan_provider::delete/$1');
    //Loan provider route

    //warranty manage route
    $routes->get('warranty_manage', 'Shop_admin\WarrantyManage::index');
    $routes->get('warranty_manage_create', 'Shop_admin\WarrantyManage::create');
    $routes->post('warranty_manage_create_action', 'Shop_admin\WarrantyManage::create_action');
    $routes->get('warranty_manage_update/(:num)', 'Shop_admin\WarrantyManage::update/$1');
    $routes->post('warranty_manage_update_action', 'Shop_admin\WarrantyManage::update_action');
    $routes->get('warranty_manage_delete/(:num)', 'Shop_admin\WarrantyManage::delete/$1');
    //warranty manage route

    //Employee route
    $routes->get('employee', 'Shop_admin\Employee::index');
    $routes->get('employee_create', 'Shop_admin\Employee::create');
    $routes->post('employee_create_action', 'Shop_admin\Employee::create_action');
    $routes->get('employee_update/(:num)', 'Shop_admin\Employee::update/$1');
    $routes->post('employee_update_action', 'Shop_admin\Employee::update_action');
    $routes->get('employee_delete/(:num)', 'Shop_admin\Employee::delete/$1');
    //Employee route

    //Ledger Employee route
    $routes->get('ledger_employee', 'Shop_admin\LedgerEmployee::index');
    $routes->post('ledger_employee_search', 'Shop_admin\LedgerEmployee::search');
    $routes->post('ledger_employee_search_date', 'Shop_admin\LedgerEmployee::search_date');
    //Ledger Employee route

    //Ledger Bank route
    $routes->get('ledger_bank', 'Shop_admin\LedgerBank::index');
    $routes->post('ledger_bank_search', 'Shop_admin\LedgerBank::search');
    $routes->post('ledger_bank_search_date', 'Shop_admin\LedgerBank::search_date');
    //Ledger Bank route

    //Ledger Loan route
    $routes->get('ledger_loan', 'Shop_admin\LedgerLoan::index');
    $routes->post('ledger_loan_search', 'Shop_admin\LedgerLoan::search');
    $routes->post('ledger_loan_search_date', 'Shop_admin\LedgerLoan::search_date');
    //Ledger Loan route

    //Ledger Loan route
    $routes->get('ledger_nagodan', 'Shop_admin\LedgerCash::index');
    $routes->post('ledger_nagodan_search', 'Shop_admin\LedgerCash::search_date');
    //Ledger Loan route

    //Ledger Loan route
    $routes->get('ledger_suppliers', 'Shop_admin\LedgerSuppliers::index');
    $routes->post('ledger_suppliers_search', 'Shop_admin\LedgerSuppliers::search');
    $routes->post('ledger_suppliers_search_date', 'Shop_admin\LedgerSuppliers::search_date');
    //Ledger Loan route

    //Ledger Vat route
    $routes->get('ledger_vat', 'Shop_admin\LedgerVat::index');
    //Ledger Vat route

    //Balance Report route
    $routes->get('balance_report', 'Shop_admin\BalanceReport::index');
    //Balance Report route

    //Stock Report route
    $routes->get('stock_report', 'Shop_admin\StockReport::index');
    $routes->post('stock_report_search', 'Shop_admin\StockReport::search');
    //Stock Report route

    //sales Report route
    $routes->get('sales_report', 'Shop_admin\SalesReport::index');
    $routes->post('sales_report_search', 'Shop_admin\SalesReport::search');
    //Sales Report route

    //purchase Report route
    $routes->get('purchase_report', 'Shop_admin\PurchaseReport::index');
    $routes->post('purchase_report_search', 'Shop_admin\PurchaseReport::search');
    //purchase Report route

    //Seller Commission route
    $routes->get('seller_commission', 'Shop_admin\SellerCommission::index');
    $routes->post('seller_commission_search', 'Shop_admin\SellerCommission::search');
    $routes->get('seller_commission_pay/(:num)/(:num)', 'Shop_admin\SellerCommission::pay/$1/$2');
    $routes->get('seller_commission_multi_pay', 'Shop_admin\SellerCommission::multi_pay');
    $routes->post('seller_commission_pay_action', 'Shop_admin\SellerCommission::pay_action');
    $routes->get('seller_commission_report', 'Shop_admin\SellerCommission::report');
    $routes->post('seller_commission_report_search', 'Shop_admin\SellerCommission::reportSearch');
    //Seller Commission route

    //Delivery Boy Commission route
    $routes->get('delivery_boy_commission', 'Shop_admin\DeliveryBoyCommission::index');
    $routes->post('delivery_boy_commission_search', 'Shop_admin\DeliveryBoyCommission::search');
    $routes->get('delivery_boy_commission_pay/(:num)/(:num)', 'Shop_admin\DeliveryBoyCommission::pay/$1/$2');
    $routes->get('delivery_boy_commission_multi_pay', 'Shop_admin\DeliveryBoyCommission::multi_pay');
    $routes->post('delivery_boy_commission_pay_action', 'Shop_admin\DeliveryBoyCommission::pay_action');
    $routes->get('delivery_boy_commission_report', 'Shop_admin\DeliveryBoyCommission::report');
    $routes->post('delivery_boy_commission_report_search', 'Shop_admin\DeliveryBoyCommission::reportSearch');
    //Delivery Boy Commission route

    //Shop Commission route
    $routes->get('shop_commission', 'Shop_admin\ShopCommission::index');
    $routes->get('shop_commission_pay_action/(:num)', 'Shop_admin\ShopCommission::pay_action/$1');
    $routes->get('shop_commission_paid', 'Shop_admin\ShopCommission::paid');
    $routes->get('shop_commission_unpaid', 'Shop_admin\ShopCommission::unpaid');
    $routes->get('shop_commission_multipay', 'Shop_admin\ShopCommission::multipay');
    $routes->post('shop_commission_multipay_action', 'Shop_admin\ShopCommission::multipay_action');
    //Shop Commission route

    //Invoice route
    $routes->get('invoice', 'Shop_admin\Invoice::index');
    $routes->get('invoice_view/(:num)', 'Shop_admin\Invoice::view/$1');
    $routes->get('invoice_a4_print/(:num)', 'Shop_admin\Invoice::a4_print/$1');
    $routes->get('invoice_pos_print/(:num)', 'Shop_admin\Invoice::pos_print/$1');
    $routes->get('invoice_package_action/(:num)', 'Shop_admin\Invoice::package_action/$1');
    $routes->post('invoice_package_create_action', 'Shop_admin\Invoice::package_create_action');
    $routes->get('invoice_cancel/(:num)', 'Shop_admin\Invoice::cancel/$1');
    //Invoice route

    //User route
    $routes->get('user', 'Shop_admin\User::index');
    $routes->get('user_create', 'Shop_admin\User::create');
    $routes->post('user_create_action', 'Shop_admin\User::create_action');
    $routes->get('user_update/(:num)', 'Shop_admin\User::update/$1');
    $routes->post('user_update_action', 'Shop_admin\User::update_action');
    $routes->post('user_personal_action', 'Shop_admin\User::personal_action');
    $routes->post('user_photo_action', 'Shop_admin\User::photo_action');
    $routes->get('user_delete/(:num)', 'Shop_admin\User::delete/$1');
    //User route

    //Role route
    $routes->get('role', 'Shop_admin\Role::index');
    $routes->get('role_create', 'Shop_admin\Role::create');
    $routes->post('role_create_action', 'Shop_admin\Role::create_action');
    $routes->get('role_update/(:num)', 'Shop_admin\Role::update/$1');
    $routes->post('role_update_action', 'Shop_admin\Role::update_action');
    $routes->get('role_delete/(:num)', 'Shop_admin\Role::delete/$1');
    //Role route

    //Campaign route
    $routes->get('campaign', 'Shop_admin\Campaign::index');
    $routes->get('campaign_create', 'Shop_admin\Campaign::create');
    $routes->post('campaign_create_action', 'Shop_admin\Campaign::create_action');
    $routes->get('campaign_update/(:num)', 'Shop_admin\Campaign::update/$1');
    $routes->post('campaign_update_action', 'Shop_admin\Campaign::update_action');
    //Campaign route

    //MyPost route
    $routes->get('my_post', 'Shop_admin\MyPost::index');
    $routes->get('my_post_create', 'Shop_admin\MyPost::create');
    $routes->post('my_post_create_action', 'Shop_admin\MyPost::create_action');
    $routes->get('my_post_update/(:num)', 'Shop_admin\MyPost::update/$1');
    $routes->post('my_post_update_action', 'Shop_admin\MyPost::update_action');
    $routes->get('my_post_delete/(:num)', 'Shop_admin\MyPost::delete/$1');
    //MyPost route

    //Group post route
    $routes->get('group_post', 'Shop_admin\GroupPost::index');
    $routes->post('group_post_like_submit', 'Shop_admin\GroupPost::like_submit');
    $routes->post('group_post_show_comment', 'Shop_admin\GroupPost::show_comment');
    $routes->post('group_post_comment_action', 'Shop_admin\GroupPost::comment_action');
    //Group post route

    //Message route
    $routes->get('message', 'Shop_admin\Message::index');
    $routes->get('message_view/(:num)', 'Shop_admin\Message::view/$1');
    //Message route

    //SmsPanel route
    $routes->get('sms_panel', 'Shop_admin\SmsPanel::index');
    $routes->get('sms_panel_update/(:num)', 'Shop_admin\SmsPanel::update/$1');
    $routes->post('sms_panel_update_action', 'Shop_admin\SmsPanel::update_action');
    //SmsPanel route

    //Settings route
    $routes->get('settings', 'Shop_admin\Settings::index');
    $routes->get('settings_update/(:num)', 'Shop_admin\Settings::update/$1');
    $routes->post('settings_update_action', 'Shop_admin\Settings::update_action');
    $routes->post('settings_photo_action', 'Shop_admin\Settings::photo_action');
    $routes->post('settings_photo_action', 'Shop_admin\Settings::photo_action');
    $routes->post('settings_general_action', 'Shop_admin\Settings::general_action');
    $routes->post('settings_vat_action', 'Shop_admin\Settings::vat_action');
    $routes->post('settings_address_action', 'Shop_admin\Settings::address_action');
    $routes->post('settings_customer_action', 'Shop_admin\Settings::customer_action');
    $routes->post('settings_customer_action', 'Shop_admin\Settings::customer_action');
    $routes->get('settings_database_backup', 'Shop_admin\Settings::database_backup');

    //Settings route

    //Get Product route
    $routes->get('get_product', 'Shop_admin\GetProduct::index');
    $routes->post('get_product_account_holder', 'Shop_admin\GetProduct::account_holder_action');
    $routes->post('get_product_suppliers', 'Shop_admin\GetProduct::suppliers_action');
    $routes->post('get_product_bank', 'Shop_admin\GetProduct::bank_action');
    $routes->post('get_product_cash', 'Shop_admin\GetProduct::cash_action');
    $routes->post('get_product_update', 'Shop_admin\GetProduct::update_action');
    $routes->post('get_product_product_show_key_search', 'Shop_admin\GetProduct::product_show_key_search');
    $routes->post('get_product_product_show_by_category', 'Shop_admin\GetProduct::product_show_by_category');
    //Get Product route

    //Transaction route
    $routes->get('transaction', 'Shop_admin\Transaction::index');
    $routes->get('transaction_create', 'Shop_admin\Transaction::create');

    $routes->post('transaction_supplier', 'Shop_admin\Transaction::supplierTransaction');
    $routes->post('transaction_supplier_action', 'Shop_admin\Transaction::supplier_action');

    $routes->post('transaction_account_holder', 'Shop_admin\Transaction::account_holder_transaction');
    $routes->post('transaction_account_holder_action', 'Shop_admin\Transaction::account_holder_action');

    $routes->post('transaction_bank', 'Shop_admin\Transaction::bank_transaction');
    $routes->post('transaction_bank', 'Shop_admin\Transaction::bank_transaction');
    $routes->post('transaction_bank_balance', 'Shop_admin\Transaction::bank_balance');
    $routes->post('transaction_bank_action', 'Shop_admin\Transaction::bank_action');
    $routes->post('transaction_expense_action', 'Shop_admin\Transaction::expense_action');
    $routes->post('transaction_others_action', 'Shop_admin\Transaction::others_action');
    $routes->post('transaction_search_employee_salary', 'Shop_admin\Transaction::search_employee_salary');
    $routes->post('transaction_employee_action', 'Shop_admin\Transaction::employee_action');
    $routes->post('transaction_vat_ledger', 'Shop_admin\Transaction::vat_ledger');
    $routes->post('transaction_vat_action', 'Shop_admin\Transaction::vat_action');
    $routes->post('transaction_sale_commission_action', 'Shop_admin\Transaction::sale_commission_action');
    //Transaction route

    //Brand route
    $routes->get('brand', 'Shop_admin\Brand::index');
    $routes->get('brand_create', 'Shop_admin\Brand::create');
    $routes->post('brand_create_action', 'Shop_admin\Brand::create_action');
    $routes->get('brand_update/(:num)', 'Shop_admin\Brand::update/$1');
    $routes->post('brand_update_action', 'Shop_admin\Brand::update_action');
    $routes->get('brand_delete/(:num)', 'Shop_admin\Brand::delete/$1');
    //Brand route

    //Product Category route
    $routes->get('product_category', 'Shop_admin\ProductCategory::index');
    $routes->get('product_category_create', 'Shop_admin\ProductCategory::create');
    $routes->post('product_category_create_action', 'Shop_admin\ProductCategory::create_action');
    $routes->get('product_category_update/(:num)', 'Shop_admin\ProductCategory::update/$1');
    $routes->post('product_category_update_action', 'Shop_admin\ProductCategory::update_action');
    $routes->get('product_category_delete/(:num)', 'Shop_admin\ProductCategory::delete/$1');
    //Product Category route


    //Stores route
    $routes->get('stores', 'Shop_admin\Stores::index');
    $routes->get('stores_create', 'Shop_admin\Stores::create');
    $routes->post('stores_create_action', 'Shop_admin\Stores::create_action');
    $routes->get('stores_update/(:num)', 'Shop_admin\Stores::update/$1');
    $routes->post('stores_update_action', 'Shop_admin\Stores::update_action');
    $routes->get('stores_delete/(:num)', 'Shop_admin\Stores::delete/$1');
    //Stores route

    //Products route
    $routes->get('products', 'Shop_admin\Products::index');
    $routes->get('products_create', 'Shop_admin\Products::create');
    $routes->post('products_create_action', 'Shop_admin\Products::create_action');
    $routes->get('products_update/(:num)', 'Shop_admin\Products::update/$1');
    $routes->post('products_update_action', 'Shop_admin\Products::update_action');
    $routes->post('products_update_detail_action', 'Shop_admin\Products::update_detail_action');
    $routes->post('products_update_meta_data_action', 'Shop_admin\Products::update_meta_data_action');
    $routes->post('products_update_related_product_action', 'Shop_admin\Products::update_related_product_action');
    $routes->post('products_update_image_action', 'Shop_admin\Products::update_image_action');
    $routes->post('products_update_product_features_action', 'Shop_admin\Products::update_product_features_action');
    $routes->post('products_update_product_special_action', 'Shop_admin\Products::product_special_action');
    $routes->get('products_delete/(:num)', 'Shop_admin\Products::delete/$1');
    $routes->get('products_status_update/(:num)/(:num)', 'Shop_admin\Products::status_update/$1/$2');
    $routes->post('products_get_sub_category', 'Shop_admin\Products::check_sub_cat');
    $routes->get('products_price_update', 'Shop_admin\Products::price_update');
    $routes->post('products_search_price_update', 'Shop_admin\Products::search_price_update');
    $routes->post('products_price_update_action', 'Shop_admin\Products::price_update_action');
    $routes->post('products_price_update_super_action', 'Shop_admin\Products::price_update_super_action');
    $routes->get('products_short_list', 'Shop_admin\Products::short_list');
    $routes->get('products_print_list', 'Shop_admin\Products::print_list');
    $routes->post('products_barcode', 'Shop_admin\Products::barcode');
    //Products route

    //Purchase route
    $routes->get('purchase', 'Shop_admin\Purchase::index');
    $routes->get('purchase_create', 'Shop_admin\Purchase::create');
    $routes->post('purchase_create_action', 'Shop_admin\Purchase::create_action');
    $routes->get('purchase_view/(:num)', 'Shop_admin\Purchase::view/$1');
    $routes->get('purchase_new_product', 'Shop_admin\Purchase::new_product');
    $routes->get('purchase_existing_product', 'Shop_admin\Purchase::existing_product');
    $routes->post('get_sub_category', 'Shop_admin\Purchase::get_sub_category');
    $routes->post('purchase_add_to_cart', 'Shop_admin\Purchase::add_to_cart');
    $routes->post('purchase_remove_cart', 'Shop_admin\Purchase::remove_cart');
    $routes->get('purchase_clear_cart', 'Shop_admin\Purchase::clear_cart');
    $routes->post('purchase_check_shop_balance', 'Shop_admin\Purchase::check_shop_balance');
    $routes->post('purchase_check_bank_balance', 'Shop_admin\Purchase::check_bank_balance');
    $routes->post('purchase_action', 'Shop_admin\Purchase::action');
    $routes->post('purchase_existing_action', 'Shop_admin\Purchase::existing_action');
    //Purchase route

    //Return Purchase route
    $routes->get('return_purchase', 'Shop_admin\ReturnPurchase::index');
    $routes->get('return_purchase_create', 'Shop_admin\ReturnPurchase::create');
    $routes->post('return_purchase_product', 'Shop_admin\ReturnPurchase::return_product');
    $routes->post('return_purchase_create_action', 'Shop_admin\ReturnPurchase::create_action');
    $routes->get('return_purchase_view/(:num)', 'Shop_admin\ReturnPurchase::view/$1');
    //Return Purchase route

    //Daily book route
    $routes->get('daily_book', 'Shop_admin\DailyBook::index');
    $routes->post('daily_book_search_bank_ledger', 'Shop_admin\DailyBook::search_bank_ledger');
    $routes->post('daily_book_search', 'Shop_admin\DailyBook::search');
    //Daily book route





});




// All agent route (start)
$routes->group('agent',['filter' => 'AgentLoggedIn'], function ($routes) {
    $routes->get('/', 'Agent\Login::index');
    $routes->get('login', 'Agent\Login::index');
    $routes->post('login_action', 'Agent\Login::login_action');
});

$routes->group('agent',['filter' => 'AgentAuthCheck'], function ($routes) {
    $routes->get('logout', 'Agent\Login::logout');
    $routes->get('dashboard', 'Agent\Dashboard::index');

    //SuperCommission
    $routes->get('super_commission', 'Agent\SuperCommission::index');
    $routes->get('unpaid_list/(:num)', 'Agent\SuperCommission::unpaid_list/$1');
    $routes->get('paid_list/(:num)', 'Agent\SuperCommission::paid_list/$1');

    //customers
    $routes->get('customers', 'Agent\Customers::index');
    $routes->get('order_list/(:num)', 'Agent\Customers::order_list/$1');
    $routes->get('invoice/(:num)', 'Agent\Customers::invoice/$1');
    $routes->get('customer_update/(:num)', 'Agent\Customers::customer_update/$1');
    $routes->post('customer_update_action', 'Agent\Customers::customer_update_action');
    $routes->post('customer_personal_update', 'Agent\Customers::customer_personal_update');
    $routes->post('customer_address_update', 'Agent\Customers::customer_address_update');
    $routes->post('customer_photo_update', 'Agent\Customers::customer_photo_update');

    //order
    $routes->get('order', 'Agent\Order::index');

    //Calling request
    $routes->get('calling_request', 'Agent\Callingrequest::index');
    $routes->post('calling_status_update', 'Agent\Callingrequest::status_update');

    //Sellers
    $routes->get('sellers', 'Agent\Sellers::index');
    $routes->get('sellers_create', 'Agent\Sellers::create');
    $routes->post('sellers_action', 'Agent\Sellers::create_action');
    $routes->get('sellers_update/(:num)', 'Agent\Sellers::update/$1');
    $routes->post('sellers_update_action', 'Agent\Sellers::update_action');
    $routes->post('sellers_personal_update_action', 'Agent\Sellers::personal_action');
    $routes->post('sellers_address_update_action', 'Agent\Sellers::address_action');
    $routes->post('sellers_photo_update_action', 'Agent\Sellers::photo_action');
    $routes->get('sellers_ledger/(:num)', 'Agent\Sellers::ledger/$1');
    $routes->get('sellers_commission/(:num)', 'Agent\Sellers::commission/$1');
    $routes->get('sellers_order/(:num)', 'Agent\Sellers::sellers_order/$1');

    //Delivery Boy
    $routes->get('delivery_boy', 'Agent\DeliveryBoy::index');
    $routes->get('delivery_boy_create', 'Agent\DeliveryBoy::create');
    $routes->post('delivery_boy_action', 'Agent\DeliveryBoy::create_action');
    $routes->get('delivery_boy_update/(:num)', 'Agent\DeliveryBoy::update/$1');
    $routes->post('delivery_boy_update_action', 'Agent\DeliveryBoy::update_action');
    $routes->post('delivery_boy_personal_update', 'Agent\DeliveryBoy::personal_update');
    $routes->post('delivery_boy_address_update', 'Agent\DeliveryBoy::address_update');
    $routes->post('delivery_boy_photo_update', 'Agent\DeliveryBoy::photo_update');
    $routes->get('delivery_boy_ledger/(:num)', 'Agent\DeliveryBoy::ledger/$1');
    $routes->get('delivery_boy_commission/(:num)', 'Agent\DeliveryBoy::commission/$1');
    $routes->get('delivery_boy_order/(:num)', 'Agent\DeliveryBoy::delivery_boy_order/$1');

    //campaign
    $routes->get('campaign', 'Agent\Campaign::index');
    $routes->post('campaign_status_update', 'Agent\Campaign::status_update');
    $routes->get('campaign_update/(:num)', 'Agent\Campaign::update/$1');
    $routes->post('campaign_update_action', 'Agent\Campaign::update_action');

    //campaign
    $routes->get('group_post', 'Agent\Grouppost::index');
    $routes->get('group_post_update/(:num)', 'Agent\Grouppost::update/$1');
    $routes->post('group_post_update_action', 'Agent\Grouppost::update_action');

    //campaign
    $routes->get('settings', 'Agent\Settings::index');
    $routes->post('settings_update_action', 'Agent\Settings::update_action');



});