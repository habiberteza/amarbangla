<?php

namespace Tests\Feature;

use App\Models\Customer;
use App\Models\CustomerType;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Http\UploadedFile;
use Tests\TestCase;

class AdminCustomerEditTabsTest extends TestCase
{
    use DatabaseTransactions;

    public function test_customer_edit_page_contains_four_tabs_matching_super_admin()
    {
        $admin = User::first() ?? User::factory()->create();
        $customer = Customer::first() ?? Customer::factory()->create();

        $response = $this->actingAs($admin)->get(route('admin.customers.edit', $customer->customer_id ?? $customer->id));

        $response->assertStatus(200);

        // Check 4 tabs
        $response->assertSee('custom-tabs-four-home');
        $response->assertSee('Registraion Info');

        $response->assertSee('custom-tabs-four-profile');
        $response->assertSee('Personal');

        $response->assertSee('custom-tabs-four-settings');
        $response->assertSee('Address');

        $response->assertSee('custom-tabs-four-messages');
        $response->assertSee('Photo');

        // Check key fields in each tab
        $response->assertSee('CustomerName');
        $response->assertSee('Mobile');
        $response->assertSee('cus_type_id');

        $response->assertSee('FatherName');
        $response->assertSee('MotherName');
        $response->assertSee('Age');
        $response->assertSee('NID');

        $response->assertSee('division');
        $response->assertSee('district');
        $response->assertSee('upazila');
        $response->assertSee('pourashava');
        $response->assertSee('ward');

        $response->assertSee('pic');
    }

    public function test_legacy_super_admin_customer_update_url_serves_edit_view()
    {
        $admin = User::first() ?? User::factory()->create();
        $customer = Customer::first() ?? Customer::factory()->create();

        $customerId = $customer->customer_id ?? $customer->id;
        $response = $this->actingAs($admin)->get('/super_admin/customer_update/' . $customerId);

        $response->assertStatus(200);
        $response->assertSee('Registraion Info');
        $response->assertSee('Personal');
        $response->assertSee('Address');
        $response->assertSee('Photo');
    }

    public function test_modular_tab_updates_work()
    {
        $admin = User::first() ?? User::factory()->create();
        $customer = Customer::first() ?? Customer::factory()->create();
        $customerId = $customer->customer_id ?? $customer->id;

        // 1. Update Registration Info
        $regResponse = $this->actingAs($admin)->post(route('admin.customers.update_credentials', $customerId), [
            'customer_name' => 'Updated Test Customer',
            'mobile' => '01799887766',
        ]);
        $regResponse->assertRedirect();
        $this->assertDatabaseHas('customers', [
            'customer_id' => $customerId,
            'customer_name' => 'Updated Test Customer',
            'phone' => '01799887766',
        ]);

        // 2. Update Personal Info
        $personalResponse = $this->actingAs($admin)->post(route('admin.customers.update_personal', $customerId), [
            'father_name' => 'Test Father',
            'mother_name' => 'Test Mother',
            'age' => 35,
            'nid' => '1234567890123',
        ]);
        $personalResponse->assertRedirect();
        $this->assertDatabaseHas('customers', [
            'customer_id' => $customerId,
            'father_name' => 'Test Father',
            'mother_name' => 'Test Mother',
            'age' => 35,
            'nid' => '1234567890123',
        ]);

        // 3. Update Address Info
        $addressResponse = $this->actingAs($admin)->post(route('admin.customers.update_address', $customerId), [
            'division' => '6',
            'district' => '47',
            'upazila' => '1',
            'pourashava' => '1',
            'ward' => '3',
            'address' => 'Road 5, Block C, Dhaka',
        ]);
        $addressResponse->assertRedirect();
        $this->assertDatabaseHas('customers', [
            'customer_id' => $customerId,
            'division' => '6',
            'district' => '47',
            'address' => 'Road 5, Block C, Dhaka',
        ]);
    }
}
