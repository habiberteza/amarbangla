<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Product extends Model
{
    use HasFactory;

    protected $table = 'products';
    protected $primaryKey = 'prod_id';
    public $timestamps = false;

    public function getIdAttribute()
    {
        return $this->attributes['prod_id'] ?? null;
    }

    public function getSlugAttribute()
    {
        if (!empty($this->attributes['slug'])) {
            return $this->attributes['slug'];
        }
        return \Illuminate\Support\Str::slug($this->attributes['name'] ?? 'product') ?: ('prod-' . ($this->attributes['prod_id'] ?? ''));
    }

    protected static function booted()
    {
        static::saving(function ($product) {
            if (empty($product->slug) && !empty($product->name)) {
                $product->slug = \Illuminate\Support\Str::slug($product->name) ?: ('prod-' . ($product->prod_id ?? \Illuminate\Support\Str::random(6)));
            }
        });
    }

    public function getPriceAttribute()
    {
        return $this->attributes['selling_price'] ?? 0;
    }

    public function demoProduct()
    {
        return $this->belongsTo(DemoProduct::class, 'demo_id', 'id');
    }

    public function getImagesListAttribute(): array
    {
        $pic = $this->attributes['picture'] ?? null;
        if (empty($pic) && !empty($this->attributes['demo_id'])) {
            $pic = $this->demoProduct?->picture;
        }

        if (empty($pic)) {
            return [];
        }

        $decoded = is_string($pic) ? json_decode(html_entity_decode($pic), true) : $pic;
        $items = is_array($decoded) ? array_values($decoded) : [$pic];

        $demoId = !empty($this->attributes['demo_id']) ? (int) $this->attributes['demo_id'] : null;
        $prodId = $this->attributes['prod_id'] ?? null;

        $urls = [];
        foreach ($items as $item) {
            if (empty($item) || !is_string($item)) continue;
            if (str_starts_with($item, 'http://') || str_starts_with($item, 'https://')) {
                $urls[] = $item;
                continue;
            }

            if ($demoId) {
                $p1 = "uploads/demo_product_image/{$demoId}/{$item}";
                $p2 = "uploads/demo_product_image/{$demoId}/150_{$item}";
                $p3 = "uploads/demo_product_image/{$item}";

                if (file_exists(public_path($p1))) {
                    $urls[] = asset($p1);
                } elseif (file_exists(public_path($p2))) {
                    $urls[] = asset($p2);
                } elseif (file_exists(public_path($p3))) {
                    $urls[] = asset($p3);
                }
            } else {
                $p1 = "uploads/product_image/{$prodId}/{$item}";
                $p2 = "uploads/product_image/{$prodId}/150_{$item}";
                $p3 = "uploads/product_image/{$item}";

                if (file_exists(public_path($p1))) {
                    $urls[] = asset($p1);
                } elseif (file_exists(public_path($p2))) {
                    $urls[] = asset($p2);
                } elseif (file_exists(public_path($p3))) {
                    $urls[] = asset($p3);
                }
            }
        }

        // Fallback 1: check demoProduct images_list if empty
        if (empty($urls) && $demoId && $this->demoProduct) {
            $urls = $this->demoProduct->images_list;
        }

        // Fallback 2: scan demo directory for any existing product photo
        if (empty($urls) && $demoId) {
            $dir = public_path("uploads/demo_product_image/{$demoId}");
            if (is_dir($dir)) {
                $files = glob("{$dir}/*.{jpg,jpeg,png,webp,gif,JPG,JPEG,PNG}", GLOB_BRACE);
                if (!empty($files)) {
                    $urls[] = asset("uploads/demo_product_image/{$demoId}/" . basename($files[0]));
                }
            }
        }

        // Fallback 3: scan custom product directory for any existing photo
        if (empty($urls) && $prodId) {
            $dir = public_path("uploads/product_image/{$prodId}");
            if (is_dir($dir)) {
                $files = glob("{$dir}/*.{jpg,jpeg,png,webp,gif,JPG,JPEG,PNG}", GLOB_BRACE);
                if (!empty($files)) {
                    $urls[] = asset("uploads/product_image/{$prodId}/" . basename($files[0]));
                }
            }
        }

        return $urls;
    }

    public function getMainImageAttribute(): string
    {
        $list = $this->images_list;
        if (!empty($list)) {
            return $list[0];
        }

        if (file_exists(public_path('uploads/demo_product_image/noImage.jpg'))) {
            return asset('uploads/demo_product_image/noImage.jpg');
        }

        return 'https://images.unsplash.com/photo-1546069901-ba9599a7e63c?w=600&q=80';
    }

    public function getShopIdAttribute()
    {
        return $this->attributes['sch_id'] ?? null;
    }

    public function getCategoryIdAttribute()
    {
        return $this->attributes['prod_cat_id'] ?? null;
    }

    public function getStockQuantityAttribute()
    {
        return (int) ($this->attributes['quantity'] ?? 10);
    }

    public function getIsActiveAttribute()
    {
        return ($this->attributes['status'] ?? '1') == '1' && empty($this->attributes['deleted']);
    }

    public function getIsExpressAttribute()
    {
        return true;
    }

    public function getIsFeaturedAttribute()
    {
        return true;
    }

    public function getDiscountPriceAttribute()
    {
        return (float) ($this->attributes['selling_price'] ?? 0);
    }

    protected $fillable = [
        'sch_id',
        'store_id',
        'prod_cat_id',
        'name',
        'quantity',
        'unit',
        'product_code',
        'color_family_id',
        'purchase_price',
        'selling_price',
        'purchase_date',
        'purchase_type',
        'supplier_id',
        'size',
        'serial_number',
        'brand_id',
        'demo_id',
        'picture',
        'warranty',
        'barcode',
        'seller_commission',
        'description',
        'meta_title',
        'meta_keyword',
        'meta_description',
        'show_global_price',
        'status',
        'deleted',
    ];

    protected $casts = [
        'selling_price' => 'decimal:2',
        'purchase_price' => 'decimal:2',
    ];

    protected $appends = [
        'id',
        'effective_price',
        'seo_title',
        'seo_description',
        'seo_keywords',
        'localized_url',
        'main_image',
        'images_list',
        'slug',
        'price',
        'shop_id',
        'category_id',
        'stock_quantity',
        'is_active',
        'is_express',
        'is_featured',
    ];

    public function getLocalizedUrlAttribute(): string
    {
        $district = Str::slug($this->shop?->district ?: 'khulna');
        $upazila = Str::slug($this->shop?->upazila ?: 'sonadanga');
        $shopSlug = $this->shop?->slug ?: ('shop-' . ($this->sch_id ?? 1));
        return "/{$district}/{$upazila}/{$shopSlug}/product/{$this->slug}";
    }

    public function shop()
    {
        return $this->belongsTo(Shop::class, 'sch_id', 'sch_id');
    }

    public function category()
    {
        return $this->belongsTo(Category::class, 'prod_cat_id', 'id');
    }

    public function scopeActive($query)
    {
        return $query->where('status', '1')->whereNull('deleted');
    }

    public function images()
    {
        return $this->hasMany(ProductImage::class, 'product_id', 'prod_id')->orderBy('sort_order');
    }

    public function variants()
    {
        return $this->hasMany(ProductVariant::class, 'product_id', 'prod_id');
    }

    public function orderItems()
    {
        return $this->hasMany(OrderItem::class, 'product_id', 'prod_id');
    }

    public function getEffectivePriceAttribute(): float
    {
        return (float) ($this->selling_price ?? 0);
    }

    // Product description with fallback to auto-generated SEO description
    public function getDescriptionAttribute($value)
    {
        if (!empty($value)) {
            return $value;
        }
        if (!empty($this->attributes['meta_description'])) {
            return $this->attributes['meta_description'];
        }
        return $this->seo_description;
    }

    // Dynamic SEO Title (Editable via meta_title, falls back to localized formula)
    public function getSeoTitleAttribute(): string
    {
        if (!empty($this->attributes['meta_title'])) {
            return trim($this->attributes['meta_title']);
        }
        $shopName = $this->shop->name ?? 'Amar Bangla Store';
        $district = $this->shop?->district ?: 'Khulna';
        $upazila = $this->shop?->upazila ?: 'Sonadanga';
        return "{$this->name} - {$district} - {$upazila} - First Delivery Service | {$shopName}";
    }

    // Dynamic SEO Description (Editable via meta_description / description, falls back to localized formula)
    public function getSeoDescriptionAttribute(): string
    {
        if (!empty($this->attributes['meta_description'])) {
            return trim($this->attributes['meta_description']);
        }
        if (!empty($this->attributes['description'])) {
            $plain = trim(strip_tags($this->attributes['description']));
            if (!empty($plain) && strlen($plain) > 10 && strlen($plain) <= 165) {
                return $plain;
            }
        }

        $district = $this->shop?->district ?: 'Khulna';
        $upazila = $this->shop?->upazila ?: 'Sonadanga';
        $shopName = $this->shop?->name ?: 'Amar Bangla Store';
        $price = number_format((float)$this->price, 0);

        return "{$this->name} - {$district} - {$upazila} - first delivery service . Order genuine {$this->name} online from {$shopName} at ৳{$price} with express home delivery and cash on delivery in {$upazila}, {$district}.";
    }

    // Dynamic SEO Keywords
    public function getSeoKeywordsAttribute(): string
    {
        $keywords = [
            $this->name,
            $this->brand,
            $this->item_code,
            $this->category->name ?? 'Products',
            $this->shop->name ?? 'Amar Bangla Shop',
            'online shopping Bangladesh',
            'Amar Bangla bazar',
            'fast delivery',
            'buy online'
        ];
        return implode(', ', array_unique(array_filter($keywords)));
    }

    // Schema.org Product Structured Data for Google Shopping & Rich Snippets
    public function getSchemaJsonLdAttribute(): array
    {
        return [
            '@context'    => 'https://schema.org',
            '@type'       => 'Product',
            'name'        => $this->name,
            'image'       => $this->main_image,
            'description' => $this->seo_description,
            'sku'         => $this->item_code ?? 'AP-' . $this->id,
            'brand'       => [
                '@type' => 'Brand',
                'name'  => $this->brand ?? ($this->shop->name ?? 'Amar Bangla'),
            ],
            'offers'      => [
                '@type'         => 'Offer',
                'url'           => url("/product/{$this->slug}"),
                'priceCurrency' => 'BDT',
                'price'         => number_format($this->effective_price, 2, '.', ''),
                'availability'  => $this->stock_quantity > 0 ? 'https://schema.org/InStock' : 'https://schema.org/OutOfStock',
                'seller'        => [
                    '@type' => 'Organization',
                    'name'  => $this->shop->name ?? 'Amar Bangla',
                ],
            ],
        ];
    }

    public function getSchemaJsonLdScriptAttribute(): string
    {
        return '<script type="application/ld+json">' . json_encode($this->schema_json_ld, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) . '</script>';
    }

    // Scopes
    public function scopeFeatured($query)
    {
        return $query->where('status', '1');
    }

    public function scopeExpress($query)
    {
        return $query->where('status', '1');
    }

    public function supplier()
    {
        return $this->belongsTo(Supplier::class, 'supplier_id', 'supplier_id');
    }
}

