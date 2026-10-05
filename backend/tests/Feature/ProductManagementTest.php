<?php

namespace Tests\Feature;

use App\Models\Product;
use App\Models\User;
use Tests\TestCase;

class ProductManagementTest extends TestCase
{
    protected function getAdmin()
    {
        return User::firstOrCreate(
            ['email' => 'admin@amarbangla.com.bd'],
            [
                'name'     => 'Super Administrator',
                'password' => bcrypt('password123'),
                'role'     => 'super_admin',
            ]
        );
    }

    public function test_products_index_page_loads_with_kpi_and_data(): void
    {
        $admin = $this->getAdmin();

        $response = $this->actingAs($admin)->get('/super_admin/products');

        $response->assertStatus(200);
        $response->assertSee('Product Catalog & Inventory');
        $response->assertSee('Total Products');
        $response->assertSee('Active Status');
        $response->assertSee('CSV');
        $response->assertSee('xl');
        $response->assertSee('pdf');
        $response->assertSee('print');
    }

    public function test_legacy_demo_product_list_url_works(): void
    {
        $admin = $this->getAdmin();

        $response = $this->actingAs($admin)->get('/super_admin/demo_product_list');

        $response->assertStatus(200);
        $response->assertSee('Product Catalog & Inventory');
    }

    public function test_live_product_data_integrity(): void
    {
        $count = Product::count();
        $this->assertGreaterThanOrEqual(1500, $count, 'Expected at least 1500 live products');

        // Check product with image
        $sample = Product::whereNotNull('main_image')->where('main_image', 'like', '%uploads/demo_product_image%')->first();
        $this->assertNotNull($sample, 'Expected products with uploads/demo_product_image/ from running business');
        $this->assertNotEmpty($sample->name);
        $this->assertGreaterThan(0, (float)$sample->price);
    }

    public function test_product_seo_attributes(): void
    {
        $product = Product::first();
        $this->assertNotNull($product);

        $this->assertNotEmpty($product->seo_title);
        $this->assertStringContainsString($product->name, $product->seo_title);

        $this->assertNotEmpty($product->seo_description);

        $this->assertNotEmpty($product->seo_keywords);
        $this->assertStringContainsString('Bangladesh', $product->seo_keywords);

        // Schema.org Product JSON-LD
        $schema = $product->schema_json_ld;
        $this->assertIsArray($schema);
        $this->assertEquals('https://schema.org', $schema['@context']);
        $this->assertEquals('Product', $schema['@type']);
        $this->assertEquals($product->name, $schema['name']);
        $this->assertArrayHasKey('offers', $schema);

        $scriptTag = $product->schema_json_ld_script;
        $this->assertStringStartsWith('<script type="application/ld+json">', $scriptTag);
    }

    public function test_product_search_and_export(): void
    {
        $admin = $this->getAdmin();

        // Search test
        $searchRes = $this->actingAs($admin)->get('/super_admin/products?search=Radhuni');
        $searchRes->assertStatus(200);
        $searchRes->assertSee('Radhuni');

        // Export CSV test
        $csvRes = $this->actingAs($admin)->get('/super_admin/products/export/csv');
        $csvRes->assertStatus(200);
        $this->assertStringContainsString('text/csv', $csvRes->headers->get('content-type'));

        // Export Print test
        $printRes = $this->actingAs($admin)->get('/super_admin/products/export/print');
        $printRes->assertStatus(200);
        $printRes->assertSee('AMAR BANGLA BAZAR');
    }
}
