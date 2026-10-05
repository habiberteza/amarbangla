<?php

namespace Tests\Feature;

use App\Models\Customer;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class AdminCustomerSidebarTest extends TestCase
{
    use DatabaseTransactions;

    public function test_customer_edit_page_renders_with_super_admin_sidebar_order()
    {
        $admin = User::first() ?? User::factory()->create();
        $customer = Customer::first() ?? Customer::factory()->create();

        $response = $this->actingAs($admin)->get(route('admin.customers.edit', $customer->id));

        $response->assertStatus(200);

        // Verify key menu items in order
        $content = $response->getContent();

        $items = [
            'Dashboard',
            'Shops category',
            'Shops',
            'License',
            'Users',
            'User Role',
            'Settings',
            'Shop commission',
            'ledger',
            'Customer',
            'Customer type',
            'Customers',
            'All Order',
            'Calling Request',
            'Seller',
            'Delivery Boy',
            'Agent',
            'Global Address',
            'Product Category',
            'Products',
            'Product Color',
            'Campaign',
            'Group Post',
            'Website settings',
            'Logo',
            'Banner',
            'Banner product page',
            'Slider',
            'Footer',
            'Mobile Slider',
            'Home banner',
            'Sms panel',
            'Gen settings'
        ];

        $lastPos = 0;
        foreach ($items as $item) {
            $pos = strpos($content, $item, $lastPos);
            $this->assertNotFalse($pos, "Expected to find '{$item}' in sidebar after previous item");
            $lastPos = $pos;
        }
    }
}
