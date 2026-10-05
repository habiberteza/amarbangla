<?php

namespace Tests\Feature;

use App\Models\Agent;
use App\Models\Ledger;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Shop;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class SellerManagementTest extends TestCase
{
    use DatabaseTransactions;

    protected function getAdmin()
    {
        return User::firstOrCreate(
            ['email' => 'admin@amarbangla.com.bd'],
            [
                'name'     => 'Super Administrator',
                'password' => bcrypt('password'),
                'role'     => 'super_admin',
            ]
        );
    }

    public function test_seller_index_page_loads_with_required_columns_and_buttons(): void
    {
        $admin = $this->getAdmin();

        $uniquePhone = '017' . rand(10000000, 99999999);
        $seller = User::create([
            'name'     => 'Rahim Traders Seller',
            'phone'    => $uniquePhone,
            'email'    => 'rahim_' . uniqid() . '@seller.test',
            'password' => bcrypt('secret123'),
            'role'     => 'vendor',
            'status'   => 'active',
            'division' => 'Dhaka',
            'district' => 'Dhaka',
            'upazila'  => 'Mirpur',
            'pourashava' => 'Mirpur Pourashava',
            'ward'     => 'Ward 10',
        ]);

        // Test primary endpoint /super_admin/vendors
        $response = $this->actingAs($admin)->get('/super_admin/vendors');
        $response->assertStatus(200);

        // Required Table Columns
        $response->assertSee('No');
        $response->assertSee('Name');
        $response->assertSee('Phone');
        $response->assertSee('Action');

        // Required Action / Export Buttons
        $response->assertSee('copy');
        $response->assertSee('CSV');
        $response->assertSee('xl');
        $response->assertSee('pdf');
        $response->assertSee('print');

        // Geographic Filters
        $response->assertSee('Division');
        $response->assertSee('District');
        $response->assertSee('Upazila');
        $response->assertSee('Pourashava/Union');
        $response->assertSee('Ward');

        // Seller data in table
        $response->assertSee('Rahim Traders Seller');
        $response->assertSee($uniquePhone);

        // Test alias endpoint /super_admin/sellers
        $responseAlias = $this->actingAs($admin)->get('/super_admin/sellers');
        $responseAlias->assertStatus(200);
        $responseAlias->assertSee('Rahim Traders Seller');
    }

    public function test_seller_create_page_has_exact_required_fields(): void
    {
        $admin = $this->getAdmin();

        $response = $this->actingAs($admin)->get('/super_admin/sellers_create');
        $response->assertStatus(200);

        // 5 Exact Fields required on live create page
        $response->assertSee('Name');
        $response->assertSee('Phone');
        $response->assertSee('Password');
        $response->assertSee('Confirm Password');
        $response->assertSee('Status');

        // Also check alias /super_admin/vendors/create
        $responseAlias = $this->actingAs($admin)->get('/super_admin/vendors/create');
        $responseAlias->assertStatus(200);
        $responseAlias->assertSee('Create Seller');
    }

    public function test_seller_can_be_created_successfully(): void
    {
        $admin = $this->getAdmin();

        $testPhone = '018' . rand(10000000, 99999999);
        $postData = [
            'name'                  => 'Karim Enterprise',
            'phone'                 => $testPhone,
            'password'              => 'password123',
            'password_confirmation' => 'password123',
            'status'                => 'active',
        ];

        $response = $this->actingAs($admin)->post('/super_admin/sellers_create', $postData);
        $response->assertRedirect('/super_admin/vendors');
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('users', [
            'name'   => 'Karim Enterprise',
            'phone'  => $testPhone,
            'role'   => 'vendor',
            'status' => 'active',
        ]);
    }

    public function test_seller_update_page_has_four_tabs_and_fields(): void
    {
        $admin = $this->getAdmin();

        $agent = Agent::create([
            'name'   => 'Test Agent Kamal',
            'phone'  => '019' . rand(10000000, 99999999),
            'status' => 'active',
        ]);

        $testPhone = '015' . rand(10000000, 99999999);
        $seller = User::create([
            'name'        => 'Test Update Seller',
            'phone'       => $testPhone,
            'email'       => 'update_test_' . uniqid() . '@seller.com',
            'password'    => bcrypt('password123'),
            'role'        => 'vendor',
            'status'      => 'active',
            'father_name' => 'Abdur Rashid',
            'mother_name' => 'Fatema Begum',
            'age'         => 35,
            'agent_id'    => $agent->id,
            'division'    => 'Chattogram',
            'district'    => 'Cox\'s Bazar',
            'upazila'     => 'Teknaf',
            'pourashava'  => 'Teknaf Pourashava',
            'ward'        => 'Ward 03',
        ]);

        $response = $this->actingAs($admin)->get("/super_admin/sellers_update/{$seller->id}");
        $response->assertStatus(200);

        // 4 Tabs
        $response->assertSee('Registration Info');
        $response->assertSee('Personal');
        $response->assertSee('Address');
        $response->assertSee('Photo');

        // Tab 1: Registration Info fields
        $response->assertSee('Name');
        $response->assertSee('Mobile');
        $response->assertSee('Password');
        $response->assertSee('Confirm Password');

        // Tab 2: Personal fields
        $response->assertSee('Father Name');
        $response->assertSee('Mother Name');
        $response->assertSee('Age');
        $response->assertSee('Agent');
        $response->assertSee('Test Agent Kamal');

        // Tab 3: Address fields
        $response->assertSee('Division');
        $response->assertSee('District');
        $response->assertSee('Upazila');
        $response->assertSee('Pourashava/Union');
        $response->assertSee('Ward');

        // Tab 4: Photo specs
        $response->assertSee('Image');
        $response->assertSee('Max. file size 1024KB and (width=300px) x (height=300px)');
    }

    public function test_seller_modular_registration_credentials_update(): void
    {
        $admin = $this->getAdmin();

        $seller = User::create([
            'name'     => 'Credentials User',
            'phone'    => '013' . rand(10000000, 99999999),
            'email'    => 'credentials_' . uniqid() . '@seller.test',
            'password' => bcrypt('oldpass123'),
            'role'     => 'vendor',
            'status'   => 'active',
        ]);

        $newPhone = '013' . rand(10000000, 99999999);
        $response = $this->actingAs($admin)->post("/super_admin/sellers_update_action/{$seller->id}", [
            'name'                  => 'Credentials User Updated',
            'mobile'                => $newPhone,
            'password'              => 'newpass123',
            'password_confirmation' => 'newpass123',
        ]);

        $response->assertSessionHas('success');

        $seller->refresh();
        $this->assertEquals('Credentials User Updated', $seller->name);
        $this->assertEquals($newPhone, $seller->phone);
    }

    public function test_seller_modular_personal_info_update(): void
    {
        $admin = $this->getAdmin();

        $seller = User::create([
            'name'     => 'Personal User',
            'phone'    => '014' . rand(10000000, 99999999),
            'email'    => 'personal_' . uniqid() . '@seller.test',
            'password' => bcrypt('pass123'),
            'role'     => 'vendor',
            'status'   => 'active',
        ]);

        $response = $this->actingAs($admin)->post("/super_admin/sellers_personal_update/{$seller->id}", [
            'father_name' => 'Rafiqul Islam',
            'mother_name' => 'Rokeya Begum',
            'age'         => 42,
        ]);

        $response->assertSessionHas('success');

        $seller->refresh();
        $this->assertEquals('Rafiqul Islam', $seller->father_name);
        $this->assertEquals('Rokeya Begum', $seller->mother_name);
        $this->assertEquals(42, $seller->age);
    }

    public function test_seller_modular_address_update(): void
    {
        $admin = $this->getAdmin();

        $seller = User::create([
            'name'     => 'Address User',
            'phone'    => '016' . rand(10000000, 99999999),
            'email'    => 'address_' . uniqid() . '@seller.test',
            'password' => bcrypt('pass123'),
            'role'     => 'vendor',
            'status'   => 'active',
        ]);

        $response = $this->actingAs($admin)->post("/super_admin/sellers_address_update/{$seller->id}", [
            'division'   => 'Rajshahi',
            'district'   => 'Bogra',
            'upazila'    => 'Sadar',
            'pourashava' => 'Bogra Pourashava',
            'ward'       => 'Ward 05',
        ]);

        $response->assertSessionHas('success');

        $seller->refresh();
        $this->assertEquals('Rajshahi', $seller->division);
        $this->assertEquals('Bogra', $seller->district);
        $this->assertEquals('Sadar', $seller->upazila);
        $this->assertEquals('Bogra Pourashava', $seller->pourashava);
        $this->assertEquals('Ward 05', $seller->ward);
    }

    public function test_seller_status_toggle(): void
    {
        $admin = $this->getAdmin();

        $seller = User::create([
            'name'     => 'Status User',
            'phone'    => '017' . rand(10000000, 99999999),
            'email'    => 'status_' . uniqid() . '@seller.test',
            'password' => bcrypt('pass123'),
            'role'     => 'vendor',
            'status'   => 'active',
        ]);

        $response = $this->actingAs($admin)->post("/super_admin/sellers/{$seller->id}/status");
        $response->assertSessionHas('success');

        $seller->refresh();
        $this->assertEquals('suspended', $seller->status);

        // Toggle back to active
        $this->actingAs($admin)->post("/super_admin/sellers/{$seller->id}/status");
        $seller->refresh();
        $this->assertEquals('active', $seller->status);
    }

    public function test_seller_export_formats(): void
    {
        $admin = $this->getAdmin();

        // 1. CSV
        $csvResponse = $this->actingAs($admin)->get('/super_admin/sellers/export/csv');
        $csvResponse->assertStatus(200);
        $csvResponse->assertHeader('Content-Disposition');

        // 2. Excel (xl)
        $xlResponse = $this->actingAs($admin)->get('/super_admin/sellers/export/excel');
        $xlResponse->assertStatus(200);

        // 3. Print
        $printResponse = $this->actingAs($admin)->get('/super_admin/sellers/export/print');
        $printResponse->assertStatus(200);
        $printResponse->assertSee('Seller Management System');

        // 4. PDF
        $pdfResponse = $this->actingAs($admin)->get('/super_admin/sellers/export/pdf');
        $pdfResponse->assertStatus(200);
        $pdfResponse->assertSee('Seller Management System');
    }

    public function test_sidebar_menu_seller_management_system(): void
    {
        $admin = $this->getAdmin();

        $response = $this->actingAs($admin)->get('/super_admin/dashboard');
        $response->assertStatus(200);
        $response->assertSee('Seller Management System');
    }

    public function test_seller_index_action_column_includes_order_commission_ledger_links(): void
    {
        $admin = $this->getAdmin();

        $seller = User::create([
            'name'     => 'Action Seller Links Test',
            'phone'    => '017' . rand(10000000, 99999999),
            'email'    => 'action_seller_' . uniqid() . '@test.com',
            'password' => bcrypt('pass123'),
            'role'     => 'vendor',
            'status'   => 'active',
        ]);

        $response = $this->actingAs($admin)->get('/super_admin/vendors');
        $response->assertStatus(200);

        // Required Action links on /super_admin/vendors
        $response->assertSee("/super_admin/sellers_order/{$seller->id}");
        $response->assertSee("/super_admin/sellers_commission/{$seller->id}");
        $response->assertSee("/super_admin/sellers_ledger/{$seller->id}");
    }

    public function test_seller_orders_page_loads_with_metrics_and_table(): void
    {
        $admin = $this->getAdmin();

        $seller = User::create([
            'name'     => 'Order Seller Test',
            'phone'    => '017' . rand(10000000, 99999999),
            'email'    => 'order_seller_' . uniqid() . '@test.com',
            'password' => bcrypt('pass123'),
            'role'     => 'vendor',
            'status'   => 'active',
        ]);

        $shop = Shop::create([
            'user_id' => $seller->id,
            'name'    => 'Seller Test Outlet 1',
            'status'  => 'active',
        ]);

        $order = Order::create([
            'order_number'    => 'ORD-TEST-' . rand(1000, 9999),
            'customer_name'   => 'Tanvir Ahmed',
            'customer_phone'  => '01811223344',
            'shipping_address'=> 'House 12, Road 4, Dhanmondi, Dhaka',
            'subtotal'        => 1200.00,
            'total_amount'    => 1260.00,
            'payment_method'  => 'cod',
            'payment_status'  => 'paid',
            'order_status'    => 'delivered',
        ]);

        OrderItem::create([
            'order_id'     => $order->id,
            'shop_id'      => $shop->id,
            'product_name' => 'Premium Men Shirt',
            'price'        => 1200.00,
            'quantity'     => 1,
            'subtotal'     => 1200.00,
            'status'       => 'delivered',
        ]);

        $response = $this->actingAs($admin)->get("/super_admin/sellers_order/{$seller->id}");
        $response->assertStatus(200);

        // Verification of seller summary & metrics
        $response->assertSee('Order Seller Test');
        $response->assertSee($order->order_number);
        $response->assertSee('Tanvir Ahmed');
        $response->assertSee('Premium Men Shirt');
        $response->assertSee('1,260.00');

        // Verification of navigation tabs
        $response->assertSee("/super_admin/sellers_update/{$seller->id}");
        $response->assertSee("/super_admin/sellers_commission/{$seller->id}");
        $response->assertSee("/super_admin/sellers_ledger/{$seller->id}");
    }

    public function test_seller_commission_page_loads_and_updates_rate(): void
    {
        $admin = $this->getAdmin();

        $seller = User::create([
            'name'            => 'Commission Seller Test',
            'phone'           => '017' . rand(10000000, 99999999),
            'email'           => 'comm_seller_' . uniqid() . '@test.com',
            'password'        => bcrypt('pass123'),
            'role'            => 'vendor',
            'status'          => 'active',
            'commission_rate' => 7.50,
        ]);

        $shop = Shop::create([
            'user_id'         => $seller->id,
            'name'            => 'Seller Comm Shop 1',
            'commission_rate' => 7.50,
            'status'          => 'active',
        ]);

        // GET page test
        $response = $this->actingAs($admin)->get("/super_admin/sellers_commission/{$seller->id}");
        $response->assertStatus(200);
        $response->assertSee('Commission Seller Test');
        $response->assertSee('Seller Comm Shop 1');
        $response->assertSee('7.50');

        // POST update commission test
        $updateResponse = $this->actingAs($admin)->post("/super_admin/sellers_commission/{$seller->id}", [
            'commission_rate' => 12.00,
            'commission_type' => 'percentage',
            'apply_to_shops'  => 1,
        ]);

        $updateResponse->assertSessionHas('success');

        $seller->refresh();
        $this->assertEquals(12.00, (float)$seller->commission_rate);

        $shop->refresh();
        $this->assertEquals(12.00, (float)$shop->commission_rate);
    }

    public function test_seller_ledger_page_loads_and_stores_transaction(): void
    {
        $admin = $this->getAdmin();

        $seller = User::create([
            'name'     => 'Ledger Seller Test',
            'phone'    => '017' . rand(10000000, 99999999),
            'email'    => 'ledger_seller_' . uniqid() . '@test.com',
            'password' => bcrypt('pass123'),
            'role'     => 'vendor',
            'status'   => 'active',
            'balance'  => 5000.00,
        ]);

        // GET ledger page
        $response = $this->actingAs($admin)->get("/super_admin/sellers_ledger/{$seller->id}");
        $response->assertStatus(200);
        $response->assertSee('Ledger Seller Test');
        $response->assertSee('5,000.00');

        // POST record transaction (debit payout of 1500)
        $postResponse = $this->actingAs($admin)->post("/super_admin/sellers_ledger/{$seller->id}", [
            'type'           => 'debit',
            'category'       => 'shop_payout',
            'amount'         => 1500.00,
            'payment_method' => 'bKash',
            'reference_id'   => 'BKASH-TXN-9988',
            'note'           => 'Weekly merchant payout transfer',
        ]);

        $postResponse->assertSessionHas('success');

        $seller->refresh();
        $this->assertEquals(3500.00, (float)$seller->balance);

        $this->assertDatabaseHas('ledgers', [
            'party_type'   => 'vendor',
            'party_name'   => 'Ledger Seller Test',
            'amount'       => 1500.00,
            'type'         => 'debit',
            'category'     => 'shop_payout',
            'reference_id' => 'BKASH-TXN-9988',
        ]);
    }

    public function test_seller_orders_and_ledger_export_formats(): void
    {
        $admin = $this->getAdmin();

        $seller = User::create([
            'name'     => 'Export Formats Seller',
            'phone'    => '017' . rand(10000000, 99999999),
            'email'    => 'export_seller_' . uniqid() . '@test.com',
            'password' => bcrypt('pass123'),
            'role'     => 'vendor',
            'status'   => 'active',
        ]);

        // Orders exports
        $this->actingAs($admin)->get("/super_admin/sellers_order/{$seller->id}/export/csv")->assertStatus(200);
        $this->actingAs($admin)->get("/super_admin/sellers_order/{$seller->id}/export/excel")->assertStatus(200);
        $this->actingAs($admin)->get("/super_admin/sellers_order/{$seller->id}/export/print")->assertStatus(200);
        $this->actingAs($admin)->get("/super_admin/sellers_order/{$seller->id}/export/pdf")->assertStatus(200);

        // Ledger exports
        $this->actingAs($admin)->get("/super_admin/sellers_ledger/{$seller->id}/export/csv")->assertStatus(200);
        $this->actingAs($admin)->get("/super_admin/sellers_ledger/{$seller->id}/export/excel")->assertStatus(200);
        $this->actingAs($admin)->get("/super_admin/sellers_ledger/{$seller->id}/export/print")->assertStatus(200);
        $this->actingAs($admin)->get("/super_admin/sellers_ledger/{$seller->id}/export/pdf")->assertStatus(200);
    }
}
