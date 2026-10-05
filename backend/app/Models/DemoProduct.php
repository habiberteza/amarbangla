<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DemoProduct extends Model
{
    use HasFactory;

    protected $table = 'demo_products';
    protected $primaryKey = 'id';
    public $timestamps = false;

    protected $fillable = [
        'name',
        'unit',
        'quantity',
        'purchase_price',
        'selling_price',
        'size',
        'picture',
        'prod_cat_id',
        'description',
    ];

    protected $appends = [
        'main_image',
        'images_list',
    ];

    public function products()
    {
        return $this->hasMany(Product::class, 'demo_id', 'id');
    }

    public function category()
    {
        return $this->belongsTo(Category::class, 'prod_cat_id', 'cat_id');
    }

    public function getMainImageAttribute(): string
    {
        $images = $this->images_list;
        return !empty($images) ? $images[0] : asset('uploads/demo_product_image/noImage.jpg');
    }

    public function getImagesListAttribute(): array
    {
        $pic = $this->attributes['picture'] ?? null;
        if (empty($pic)) {
            return [];
        }

        $decoded = is_string($pic) ? json_decode(html_entity_decode($pic), true) : $pic;
        $items = is_array($decoded) ? array_values($decoded) : [$pic];

        $urls = [];
        foreach ($items as $item) {
            if (empty($item)) continue;
            if (str_starts_with($item, 'http://') || str_starts_with($item, 'https://')) {
                $urls[] = $item;
                continue;
            }

            // Check if file exists on disk
            $basePath = "uploads/demo_product_image/{$this->id}/{$item}";
            $thumbPath = "uploads/demo_product_image/{$this->id}/150_{$item}";
            $rootPath = "uploads/demo_product_image/{$item}";

            if (file_exists(public_path($basePath))) {
                $urls[] = asset($basePath);
            } elseif (file_exists(public_path($thumbPath))) {
                $urls[] = asset($thumbPath);
            } elseif (file_exists(public_path($rootPath))) {
                $urls[] = asset($rootPath);
            } else {
                $urls[] = asset($basePath);
            }
        }

        return $urls;
    }
}
