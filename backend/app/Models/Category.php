<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Category extends Model
{
    use HasFactory;

    protected $table = 'categories';
    protected $primaryKey = 'id';
    public $timestamps = true;

    protected $fillable = [
        'parent_id',
        'name',
        'slug',
        'icon',
        'image',
        'sort_order',
        'is_active',
        'is_featured',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'is_featured' => 'boolean',
        'sort_order' => 'integer',
    ];

    protected $appends = ['image_url'];

    public function parent()
    {
        return $this->belongsTo(Category::class, 'parent_id', 'id');
    }

    public function children()
    {
        return $this->hasMany(Category::class, 'parent_id', 'id');
    }

    public function products()
    {
        return $this->hasMany(Product::class, 'prod_cat_id', 'id');
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function getImageUrlAttribute(): string
    {
        $rawImage = $this->attributes['image'] ?? null;
        if (!empty($rawImage)) {
            if (str_starts_with($rawImage, 'http://') || str_starts_with($rawImage, 'https://')) {
                return $rawImage;
            }
            if (file_exists(public_path('uploads/schools/' . $rawImage))) {
                return asset('uploads/schools/' . $rawImage);
            }
            if (file_exists(public_path($rawImage))) {
                return asset($rawImage);
            }
        }

        // 1. Check shop_category images in uploads/schools/
        $name = $this->name ?? '';
        $sc = \Illuminate\Support\Facades\DB::table('shop_category')
            ->where(function($q) use ($name) {
                $q->where('name', 'like', "%{$name}%")
                  ->orWhere('title', 'like', "%{$name}%");
            })
            ->whereNotNull('image')
            ->where('image', '!=', '')
            ->first();

        if ($sc && !empty($sc->image) && file_exists(public_path('uploads/schools/' . $sc->image))) {
            return asset('uploads/schools/' . $sc->image);
        }

        // 2. Try demo_products for this category or its subcategories
        $catIds = [$this->id];
        $childrenIds = Category::where('parent_id', $this->id)->pluck('id')->toArray();
        $allIds = array_merge($catIds, $childrenIds);

        $dp = \Illuminate\Support\Facades\DB::table('demo_products')
            ->whereIn('prod_cat_id', $allIds)
            ->whereNotNull('picture')
            ->where('picture', '!=', '')
            ->where('picture', '!=', '[]')
            ->first();

        if ($dp) {
            $decoded = is_string($dp->picture) ? json_decode(html_entity_decode($dp->picture), true) : $dp->picture;
            $items = is_array($decoded) ? array_values($decoded) : [$dp->picture];
            $item = $items[0] ?? null;
            if ($item) {
                $p1 = "uploads/demo_product_image/{$dp->id}/{$item}";
                $p2 = "uploads/demo_product_image/{$dp->id}/150_{$item}";
                $p3 = "uploads/demo_product_image/{$item}";
                if (file_exists(public_path($p1))) return asset($p1);
                if (file_exists(public_path($p2))) return asset($p2);
                if (file_exists(public_path($p3))) return asset($p3);
            }
        }

        // 3. Fallback based on name/slug
        $lower = strtolower(($this->name ?? '') . ' ' . ($this->slug ?? ''));
        if (str_contains($lower, 'vegetable') || str_contains($lower, 'সবজি')) {
            return 'https://images.unsplash.com/photo-1540420773420-3366772f4999?w=300&q=80';
        }
        if (str_contains($lower, 'grocer') || str_contains($lower, 'মুদি')) {
            return 'https://images.unsplash.com/photo-1542838132-92c53300491e?w=300&q=80';
        }
        if (str_contains($lower, 'fruit') || str_contains($lower, 'ফল')) {
            return 'https://images.unsplash.com/photo-1619566636858-adf3ef46400b?w=300&q=80';
        }
        if (str_contains($lower, 'egg') || str_contains($lower, 'ডিম')) {
            return 'https://images.unsplash.com/photo-1582722872445-44dc5f7e3c8f?w=300&q=80';
        }
        if (str_contains($lower, 'meat') || str_contains($lower, 'fish') || str_contains($lower, 'মাছ') || str_contains($lower, 'মাংস')) {
            return 'https://images.unsplash.com/photo-1607623814075-e51df1bdc82f?w=300&q=80';
        }
        if (str_contains($lower, 'dairy') || str_contains($lower, 'milk') || str_contains($lower, 'দুধ')) {
            return 'https://images.unsplash.com/photo-1528750997573-59b89d56f4f7?w=300&q=80';
        }
        if (str_contains($lower, 'bread') || str_contains($lower, 'bakery') || str_contains($lower, 'কেক')) {
            return 'https://images.unsplash.com/photo-1509440159596-0249088772ff?w=300&q=80';
        }
        if (str_contains($lower, 'snack') || str_contains($lower, 'নাস্তা')) {
            return 'https://images.unsplash.com/photo-1621996346565-e3d5d6281290?w=300&q=80';
        }
        if (str_contains($lower, 'beverage') || str_contains($lower, 'পানীয়') || str_contains($lower, 'tea')) {
            return 'https://images.unsplash.com/photo-1544787219-7f47ccb76574?w=300&q=80';
        }
        if (str_contains($lower, 'phone') || str_contains($lower, 'mobile') || str_contains($lower, 'gadget')) {
            return 'https://images.unsplash.com/photo-1511707171634-5f897ff025a0?w=300&q=80';
        }
        if (str_contains($lower, 'baby')) {
            return 'https://images.unsplash.com/photo-1515488042361-ee00e0ddd4e4?w=300&q=80';
        }
        if (str_contains($lower, 'personal') || str_contains($lower, 'beauty') || str_contains($lower, 'care')) {
            return 'https://images.unsplash.com/photo-1522337360788-8b13dee7a37e?w=300&q=80';
        }

        return 'https://images.unsplash.com/photo-1542838132-92c53300491e?w=300&q=80';
    }

    public function getImageAttribute($value): string
    {
        if (!empty($value)) {
            if (str_starts_with($value, 'http://') || str_starts_with($value, 'https://')) {
                return $value;
            }
            if (file_exists(public_path('uploads/schools/' . $value))) {
                return asset('uploads/schools/' . $value);
            }
            if (file_exists(public_path($value))) {
                return asset($value);
            }
        }
        return $this->image_url;
    }
}
