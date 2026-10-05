<?php

namespace Tests\Feature;

use App\Models\License;
use App\Models\Shop;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class LicenseManagementTest extends TestCase
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

    public function test_license_index_page_loads_with_required_columns_and_buttons(): void
    {
        $admin = $this->getAdmin();

        $license = License::create([
            'shop_name'      => 'Apex Footwear Test Shop',
            'license_key'    => 'AB-LIC-2026-TEST01',
            'license_number' => 'AB-LIC-2026-TEST01',
            'start_date'     => '2026-01-01',
            'end_date'       => '2027-01-01',
            'status'         => 'approved',
            'license_type'   => 'Trade License',
        ]);

        $response = $this->actingAs($admin)->get('/super_admin/license');

        $response->assertStatus(200);

        // Required Table Columns
        $response->assertSee('No');
        $response->assertSee('Shop Name');
        $response->assertSee('License Key');
        $response->assertSee('Start Date');
        $response->assertSee('End Date');
        $response->assertSee('Action');

        // Required Action / Export Buttons
        $response->assertSee('copy');
        $response->assertSee('csv');
        $response->assertSee('xl');
        $response->assertSee('pdf');
        $response->assertSee('print');

        // Record content
        $response->assertSee('Apex Footwear Test Shop');
        $response->assertSee('AB-LIC-2026-TEST01');
    }

    public function test_license_create_page_loads(): void
    {
        $admin = $this->getAdmin();

        // Test both legacy /super_admin/license_create and standard /super_admin/license/create
        $res1 = $this->actingAs($admin)->get('/super_admin/license_create');
        $res1->assertStatus(200);
        $res1->assertSee('Create Shop License');
        $res1->assertSee('super_admin/license_create');
        $res1->assertSee('Select Registered Shop');
        $res1->assertSee('License Key');
        $res1->assertSee('Start Date (Issue Date)');
        $res1->assertSee('End Date (Expiry Date)');

        $res2 = $this->actingAs($admin)->get('/super_admin/license/create');
        $res2->assertStatus(200);
    }

    public function test_license_store_action_creates_record(): void
    {
        $admin = $this->getAdmin();
        $shop = Shop::create([
            'name'  => 'Demo Super Store',
            'slug'  => 'demo-super-store-' . time(),
            'phone' => '01700998877',
        ]);

        $payload = [
            'shop_id'      => $shop->id,
            'shop_name'    => 'Demo Super Store',
            'license_key'  => 'AB-LIC-STORE-999',
            'start_date'   => '2026-03-01',
            'end_date'     => '2027-03-01',
            'license_type' => 'Trade License',
            'status'       => 'approved',
            'notes'        => 'Verified by Dhaka North City Corp',
        ];

        $response = $this->actingAs($admin)->post('/super_admin/license_create', $payload);
        $response->assertRedirect('/super_admin/license');

        $this->assertDatabaseHas('licenses', [
            'license_key' => 'AB-LIC-STORE-999',
            'shop_name'   => 'Demo Super Store',
            'status'      => 'approved',
        ]);
    }

    public function test_license_edit_page_loads_via_legacy_and_standard_url(): void
    {
        $admin = $this->getAdmin();
        $license = License::create([
            'shop_name'   => 'Green Supermarket',
            'license_key' => 'AB-LIC-GREEN-123',
            'start_date'  => '2026-02-01',
            'end_date'    => '2027-02-01',
            'status'      => 'approved',
        ]);

        $res1 = $this->actingAs($admin)->get('/super_admin/license_update/' . $license->id);
        $res1->assertStatus(200);
        $res1->assertSee('Green Supermarket');
        $res1->assertSee('AB-LIC-GREEN-123');

        $res2 = $this->actingAs($admin)->get('/super_admin/license/' . $license->id . '/edit');
        $res2->assertStatus(200);
    }

    public function test_license_update_saves_changes(): void
    {
        $admin = $this->getAdmin();
        $license = License::create([
            'shop_name'   => 'Original Name',
            'license_key' => 'AB-LIC-ORIG-001',
            'start_date'  => '2026-01-01',
            'end_date'    => '2026-12-31',
            'status'      => 'pending',
        ]);

        $updatePayload = [
            'shop_name'    => 'Updated Name',
            'license_key'  => 'AB-LIC-UPD-002',
            'start_date'   => '2026-01-01',
            'end_date'     => '2027-12-31',
            'status'       => 'approved',
            'license_type' => 'Fire & Safety License',
        ];

        $response = $this->actingAs($admin)->put('/super_admin/license_update/' . $license->id, $updatePayload);
        $response->assertRedirect('/super_admin/license');

        $this->assertDatabaseHas('licenses', [
            'id'          => $license->id,
            'shop_name'   => 'Updated Name',
            'license_key' => 'AB-LIC-UPD-002',
            'status'      => 'approved',
        ]);
    }

    public function test_license_export_routes(): void
    {
        $admin = $this->getAdmin();

        // CSV export
        $csvRes = $this->actingAs($admin)->get('/super_admin/license/export/csv');
        $csvRes->assertStatus(200);
        $this->assertTrue(str_contains($csvRes->headers->get('content-disposition'), 'attachment'));

        // Excel / xl export
        $xlRes = $this->actingAs($admin)->get('/super_admin/license/export/xl');
        $xlRes->assertStatus(200);
        $this->assertTrue(str_contains($xlRes->headers->get('content-disposition'), 'attachment'));

        // Print export
        $printRes = $this->actingAs($admin)->get('/super_admin/license/export/print');
        $printRes->assertStatus(200);
        $printRes->assertSee('Shop Licenses');
        $printRes->assertSee('Print / Save as PDF');

        // PDF export
        $pdfRes = $this->actingAs($admin)->get('/super_admin/license/export/pdf');
        $pdfRes->assertStatus(200);
    }

    public function test_license_status_toggle(): void
    {
        $admin = $this->getAdmin();
        $license = License::create([
            'shop_name'   => 'Pending Shop',
            'license_key' => 'AB-LIC-PEND-001',
            'status'      => 'pending',
        ]);

        $response = $this->actingAs($admin)->patch('/super_admin/license/' . $license->id . '/status', [
            'status' => 'approved',
        ]);

        $response->assertStatus(302);
        $this->assertEquals('approved', $license->fresh()->status);
    }
}
