<?php

namespace Tests\Feature;

use App\Models\Shop;
use Tests\TestCase;

class ShopSeoAndNationwideDataTest extends TestCase
{
    public function test_nationwide_shops_data_integrity(): void
    {
        $count = Shop::count();
        $this->assertGreaterThanOrEqual(3000, $count, 'Expected at least 3,000 nationwide shops');

        // Verify all 8 Divisions exist
        $divisions = Shop::select('division')->distinct()->pluck('division')->toArray();
        $expectedDivisions = ['Dhaka', 'Chattagram', 'Rajshahi', 'Khulna', 'Barisal', 'Sylhet', 'Rangpur', 'Mymensingh'];
        foreach ($expectedDivisions as $div) {
            $this->assertContains($div, $divisions, "Missing division: {$div}");
        }

        // Verify 64 districts represented
        $districtCount = Shop::select('district')->distinct()->get(['district'])->count();
        $this->assertEquals(64, $districtCount, 'Expected all 64 districts to have registered shops');
    }

    public function test_seo_attributes_and_schema_json_ld(): void
    {
        $shop = Shop::first();
        $this->assertNotNull($shop);

        // SEO Title
        $this->assertNotEmpty($shop->seo_title);
        $this->assertStringContainsString($shop->name, $shop->seo_title);

        // SEO Description
        $this->assertNotEmpty($shop->seo_description);

        // SEO Keywords
        $this->assertNotEmpty($shop->seo_keywords);
        $this->assertStringContainsString('Bangladesh', $shop->seo_keywords);

        // Schema.org LocalBusiness JSON-LD
        $schema = $shop->schema_json_ld;
        $this->assertIsArray($schema);
        $this->assertEquals('https://schema.org', $schema['@context']);
        $this->assertEquals('LocalBusiness', $schema['@type']);
        $this->assertEquals($shop->name, $schema['name']);
        $this->assertArrayHasKey('address', $schema);

        // Schema.org Script Tag
        $scriptTag = $shop->schema_json_ld_script;
        $this->assertStringStartsWith('<script type="application/ld+json">', $scriptTag);
        $this->assertStringEndsWith('</script>', $scriptTag);
    }

    public function test_fast_geo_filtering_performance(): void
    {
        $start = microtime(true);
        $filtered = Shop::where('division', 'Dhaka')
            ->where('district', 'Dhaka')
            ->where(function($q) {
                $q->where('status', 'active')->orWhere('status', '1');
            })
            ->take(25)
            ->get();
        $duration = (microtime(true) - $start) * 1000;

        $this->assertLessThan(200, $duration, 'Indexed query should execute in under 200ms');
        $this->assertNotEmpty($filtered);
    }

    public function test_public_api_v1_shops_endpoint(): void
    {
        $response = $this->getJson('/api/v1/shops?per_page=5&division=Dhaka');

        $response->assertStatus(200);
        $response->assertJsonPath('status', 'success');
        $this->assertCount(5, $response->json('data.data'));

        // Verify SEO fields appended in API response
        $first = $response->json('data.data.0');
        $this->assertArrayHasKey('seo_title', $first);
        $this->assertArrayHasKey('seo_keywords', $first);
        $this->assertArrayHasKey('full_address', $first);
    }
}
