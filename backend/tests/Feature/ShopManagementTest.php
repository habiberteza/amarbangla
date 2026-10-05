<?php

namespace Tests\Feature;

use App\Models\Shop;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class ShopManagementTest extends TestCase
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

    public function test_shops_index_page_loads_with_filters(): void
    {
        $admin = $this->getAdmin();

        $response = $this->actingAs($admin)->get('/super_admin/shops?division=Dhaka&district=Dhaka');

        $response->assertStatus(200);
        $response->assertSee('Shop Management System');
        $response->assertSee('CSV');
        $response->assertSee('xl');
        $response->assertSee('pdf');
        $response->assertSee('print');
    }

    public function test_shops_create_legacy_and_standard_page_loads(): void
    {
        $admin = $this->getAdmin();

        $res1 = $this->actingAs($admin)->get('/super_admin/shops_create');
        $res1->assertStatus(200);
        $res1->assertSee('Name');
        $res1->assertSee('Email');
        $res1->assertSee('Password');
        $res1->assertSee('Confirm Password');
        $res1->assertSee('Status');

        $res2 = $this->actingAs($admin)->get('/super_admin/shops/create');
        $res2->assertStatus(200);
    }

    public function test_shops_update_legacy_and_standard_page_loads(): void
    {
        $admin = $this->getAdmin();
        $shop = Shop::create([
            'name' => 'Test Outlet',
            'slug' => 'test-outlet',
            'type' => 'regular',
            'status' => 'active',
            'commission_rate' => 5.0,
            'balance' => 1500.0,
        ]);

        $res1 = $this->actingAs($admin)->get('/super_admin/shops_update/' . $shop->id);
        $res1->assertStatus(200);
        $res1->assertSee('Registration Info / Personal');
        $res1->assertSee('Photo');
        $res1->assertSee('User Update');
        $res1->assertSee('Address');
        $res1->assertSee('Shop category');
        $res1->assertSee('Sale commission');
        $res1->assertSee('Max. file size 1024KB and (width=350px) x (height=100px)');
        $res1->assertSee('Max. file size 1024KB and (width=160px) x (height=160px)');

        $res2 = $this->actingAs($admin)->get('/super_admin/shops/' . $shop->id . '/edit');
        $res2->assertStatus(200);
    }

    public function test_shops_exports_work(): void
    {
        $admin = $this->getAdmin();

        $csvRes = $this->actingAs($admin)->get('/super_admin/shops/export/csv');
        $csvRes->assertStatus(200);
        $this->assertStringContainsString('text/csv', $csvRes->headers->get('content-type'));

        $excelRes = $this->actingAs($admin)->get('/super_admin/shops/export/excel');
        $excelRes->assertStatus(200);

        $printRes = $this->actingAs($admin)->get('/super_admin/shops/export/print');
        $printRes->assertStatus(200);
        $printRes->assertSee('AMAR BANGLA');
    }

    public function test_shop_store_and_update_functionality(): void
    {
        $admin = $this->getAdmin();

        // 1. Test Store
        $postRes = $this->actingAs($admin)->post('/super_admin/shops', [
            'name' => 'Green Grocery Express',
            'email' => 'green@amarbangla.com.bd',
            'password' => 'secret123',
            'con_password' => 'secret123',
            'status' => 'active',
            'mobile' => '01711223344',
            'sale_commission' => 7.5,
            'division' => 'Dhaka',
            'district' => 'Dhaka',
            'upazila' => 'Mirpur',
            'pourashava' => 'Mirpur Pourashava',
            'ward' => 'Ward 10',
            'house_address' => 'House 5, Road 2',
            'shop_type' => 'grocery',
            'priority_type' => 'high',
            'home_feature' => 1,
        ]);

        $postRes->assertRedirect('/super_admin/shops');

        $shop = Shop::where('name', 'Green Grocery Express')->first();
        $this->assertNotNull($shop);
        $this->assertEquals('green@amarbangla.com.bd', $shop->email);
        $this->assertEquals(7.5, (float)$shop->commission_rate);
        $this->assertEquals('Mirpur', $shop->upazila);
        $this->assertEquals('Ward 10', $shop->ward);
        $this->assertTrue((bool)$shop->home_feature);

        // Associated user created
        $user = User::where('email', 'green@amarbangla.com.bd')->first();
        $this->assertNotNull($user);
        $this->assertEquals('vendor', $user->role);

        // 2. Test Toggle Status
        $toggleRes = $this->actingAs($admin)->post('/super_admin/shops/' . $shop->id . '/status');
        $toggleRes->assertStatus(302);
        $shop->refresh();
        $this->assertEquals('inactive', $shop->status);
    }
}
