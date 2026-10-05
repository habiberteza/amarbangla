<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Shop extends Model
{
    use HasFactory;

    protected $table = 'shops';
    protected $primaryKey = 'sch_id';
    public $timestamps = false;

    public function getIdAttribute()
    {
        return $this->attributes['sch_id'] ?? null;
    }

    public function getSlugAttribute()
    {
        return \Illuminate\Support\Str::slug($this->attributes['name'] ?? 'shop') ?: ('shop-' . ($this->attributes['sch_id'] ?? ''));
    }

    protected $fillable = [
        'user_id',
        'category_id',
        'sub_category_id',
        'name',
        'slug',
        'type', // regular, express, local, food
        'shop_type',
        'priority_type',
        'category',
        'sub_category',
        'logo',
        'image',
        'banner',
        'phone',
        'email',
        'password',
        'address',
        'house_address',
        'division',
        'district',
        'upazila',
        'pourashava',
        'ward',
        'balance',
        'commission_rate',
        'rating',
        'is_featured',
        'home_feature',
        'status', // active, pending, suspended, inactive
        'description',
        'comment',
    ];

    protected $casts = [
        'is_featured' => 'boolean',
        'home_feature' => 'boolean',
        'commission_rate' => 'decimal:2',
        'rating' => 'decimal:2',
        'balance' => 'decimal:2',
    ];

    protected $appends = [
        'id',
        'slug',
        'logo_url',
        'banner_url',
        'division',
        'district',
        'upazila',
        'full_address',
        'seo_title',
        'seo_description',
        'seo_keywords',
    ];

    protected static function booted()
    {
        static::creating(function ($shop) {
            if (empty($shop->slug)) {
                $shop->slug = \Illuminate\Support\Str::slug($shop->name ?? 'shop') . '-' . uniqid();
            }
        });
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function vendor()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function categoryRel()
    {
        return $this->belongsTo(Category::class, 'category_id');
    }

    public function subCategoryRel()
    {
        return $this->belongsTo(Category::class, 'sub_category_id');
    }

    public function products()
    {
        return $this->hasMany(Product::class, 'sch_id', 'sch_id');
    }

    public function globalAddress()
    {
        return $this->belongsTo(GlobalAddress::class, 'global_address_id', 'id');
    }

    public function getDivisionAttribute()
    {
        if (!empty($this->attributes['division'])) {
            return $this->attributes['division'];
        }
        $addr = $this->globalAddress;
        return $addr ? ($addr->division ?? 'Khulna') : 'Khulna';
    }

    public function getDistrictAttribute()
    {
        if (!empty($this->attributes['district'])) {
            return $this->attributes['district'];
        }
        $addr = $this->globalAddress;
        return $addr ? ($addr->district ?? 'Khulna') : 'Khulna';
    }

    public function getUpazilaAttribute()
    {
        if (!empty($this->attributes['upazila'])) {
            return $this->attributes['upazila'];
        }
        $addr = $this->globalAddress;
        return $addr ? ($addr->upazila ?? 'Sonadanga') : 'Sonadanga';
    }

    public function getCategoryAttribute()
    {
        if (!empty($this->attributes['shop_cat_id'])) {
            return get_data_by_id('name', 'shop_category', 'shop_cat_id', $this->attributes['shop_cat_id']) ?: 'Local Store';
        }
        return 'Local Store';
    }

    public function getPhoneAttribute()
    {
        return $this->attributes['mobile'] ?? null;
    }

    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }

    // Helper for Logo URL — returns a full accessible URL from the raw logo filename
    public function getLogoUrlAttribute()
    {
        $logo = $this->attributes['logo'] ?? null;
        if (!empty($logo)) {
            if (str_starts_with($logo, 'http://') || str_starts_with($logo, 'https://')) {
                return $logo;
            }
            // Check various upload paths
            foreach (['uploads/schools/', 'uploads/shops/', 'uploads/seller_image/', 'uploads/'] as $dir) {
                if (file_exists(public_path($dir . $logo))) {
                    return asset($dir . $logo);
                }
                // Also check if logo already contains the dir prefix
                if (str_starts_with($logo, $dir) && file_exists(public_path($logo))) {
                    return asset($logo);
                }
            }
            // If logo already contains a path prefix like uploads/...
            if (str_starts_with($logo, 'uploads/') && file_exists(public_path($logo))) {
                return asset($logo);
            }
            return asset('uploads/schools/' . $logo);
        }
        return 'https://ui-avatars.com/api/?name=' . urlencode($this->attributes['name'] ?? 'Shop') . '&background=059669&color=fff&size=150&font-size=0.4&bold=true';
    }

    // Helper for Banner URL — returns a full accessible URL from the raw banner filename
    public function getBannerUrlAttribute()
    {
        $banner = $this->attributes['banner'] ?? null;
        if (!empty($banner)) {
            if (str_starts_with($banner, 'http://') || str_starts_with($banner, 'https://')) {
                return $banner;
            }
            // Check various upload paths
            foreach (['uploads/schools/', 'uploads/shops/', 'uploads/seller_image/', 'uploads/'] as $dir) {
                if (file_exists(public_path($dir . $banner))) {
                    return asset($dir . $banner);
                }
                if (str_starts_with($banner, $dir) && file_exists(public_path($banner))) {
                    return asset($banner);
                }
            }
            if (str_starts_with($banner, 'uploads/') && file_exists(public_path($banner))) {
                return asset($banner);
            }
            return asset('uploads/schools/' . $banner);
        }
        return null;
    }

    // Helper for Shop Profile Image URL
    public function getImageUrlAttribute()
    {
        $img = $this->attributes['image'] ?? null;
        if (!empty($img)) {
            if (str_starts_with($img, 'http://') || str_starts_with($img, 'https://')) {
                return $img;
            }
            if (str_starts_with($img, 'uploads/')) {
                return asset($img);
            }
            if (file_exists(public_path('uploads/schools/' . $img))) {
                return asset('uploads/schools/' . $img);
            }
            return asset('uploads/schools/' . $img);
        }
        return $this->logo_url;
    }

    // Full Address Helper
    public function getFullAddressAttribute(): string
    {
        $parts = array_filter([
            $this->house_address,
            $this->address,
            $this->ward,
            $this->pourashava,
            $this->upazila,
            $this->district,
            $this->division,
            'Bangladesh'
        ]);
        return implode(', ', $parts);
    }

    // SEO Meta Title
    public function getSeoTitleAttribute(): string
    {
        $loc = array_filter([$this->upazila, $this->district, $this->division]);
        $locStr = !empty($loc) ? implode(', ', $loc) . ', Bangladesh' : 'Bangladesh';
        return "{$this->name} - Buy Online at Amar Bangla ({$locStr})";
    }

    // SEO Meta Description
    public function getSeoDescriptionAttribute(): string
    {
        $cleanDesc = $this->description ? strip_tags($this->description) : '';
        if (!empty($cleanDesc)) {
            return \Illuminate\Support\Str::limit($cleanDesc, 155);
        }
        $category = $this->category ?? 'Retail & Services';
        $location = $this->upazila ?? ($this->district ?? 'Bangladesh');
        return "Order from {$this->name} in {$location}. Fast delivery, cash on delivery, best prices for {$category} across Bangladesh at Amar Bangla.";
    }

    // SEO Meta Keywords
    public function getSeoKeywordsAttribute(): string
    {
        $keywords = [
            $this->name,
            $this->category,
            $this->sub_category,
            $this->shop_type,
            $this->upazila,
            $this->district,
            $this->division,
            'online shopping Bangladesh',
            'Amar Bangla bazar',
            'fast delivery'
        ];
        return implode(', ', array_unique(array_filter($keywords)));
    }

    // Schema.org LocalBusiness JSON-LD Structured Data for Google Rich Snippets
    public function getSchemaJsonLdAttribute(): array
    {
        return [
            '@context'   => 'https://schema.org',
            '@type'      => 'LocalBusiness',
            'name'       => $this->name,
            'image'      => $this->image_url,
            'logo'       => $this->logo_url,
            'telephone'  => $this->phone ?? '+880 1714070770',
            'email'      => $this->email ?? 'support@amarbangla.com.bd',
            'url'        => url("/shop/{$this->slug}"),
            'priceRange' => '৳ - ৳৳৳',
            'address'    => [
                '@type'           => 'PostalAddress',
                'streetAddress'   => $this->house_address ?? ($this->address ?? $this->upazila),
                'addressLocality' => $this->upazila ?? $this->district,
                'addressRegion'   => $this->division ?? 'Dhaka',
                'addressCountry'  => 'BD',
            ],
            'geo' => [
                '@type'     => 'GeoCoordinates',
                'latitude'  => '23.8103',
                'longitude' => '90.4125',
            ]
        ];
    }

    public function getSchemaJsonLdScriptAttribute(): string
    {
        return '<script type="application/ld+json">' . json_encode($this->schema_json_ld, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) . '</script>';
    }

    // Scopes for Fast and Clean Queries
    public function scopeActive($query)
    {
        return $query->where('status', '1')->whereNull('deleted');
    }

    public function scopeFeatured($query)
    {
        return $query->where(function ($q) {
            $q->where('home_feature', true)->orWhere('is_featured', true);
        });
    }

    public function scopeFilterGeo($query, array $filters)
    {
        if (!empty($filters['division'])) {
            $query->where('division', $filters['division']);
        }
        if (!empty($filters['district'])) {
            $query->where('district', $filters['district']);
        }
        if (!empty($filters['upazila'])) {
            $query->where('upazila', $filters['upazila']);
        }
        if (!empty($filters['pourashava'])) {
            $query->where('pourashava', $filters['pourashava']);
        }
        if (!empty($filters['ward'])) {
            $query->where('ward', $filters['ward']);
        }
        return $query;
    }
}

