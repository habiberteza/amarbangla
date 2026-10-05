<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_id',
        'shop_id',
        'product_id',
        'product_name',
        'variant_info',
        'price',
        'quantity',
        'subtotal',
        'status',
        'created_at',
        'updated_at',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'quantity' => 'integer',
        'subtotal' => 'decimal:2',
    ];

    public function order()
    {
        return $this->belongsTo(Order::class, 'order_id');
    }

    public function shop()
    {
        return $this->belongsTo(Shop::class, 'shop_id', 'sch_id');
    }

    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id', 'prod_id');
    }

    public function getThumbnailUrlAttribute(): string
    {
        if ($this->product) {
            $prod = $this->product;
            $demoId = $prod->demo_id;
            $prodId = $prod->prod_id;

            $pic = $prod->picture;
            $decoded = is_string($pic) ? json_decode(html_entity_decode($pic), true) : $pic;
            $singleimg = is_array($decoded) && !empty($decoded[0]) ? $decoded[0] : (is_string($decoded) ? $decoded : null);

            if ($demoId && $singleimg) {
                $p70 = "uploads/demo_product_image/{$demoId}/70_{$singleimg}";
                $pOrig = "uploads/demo_product_image/{$demoId}/{$singleimg}";
                if (file_exists(public_path($p70))) return asset($p70);
                if (file_exists(public_path($pOrig))) return asset($pOrig);
            }

            if ($prodId && $singleimg) {
                $p70 = "uploads/product_image/{$prodId}/70_{$singleimg}";
                $pOrig = "uploads/product_image/{$prodId}/{$singleimg}";
                if (file_exists(public_path($p70))) return asset($p70);
                if (file_exists(public_path($pOrig))) return asset($pOrig);
            }

            return $prod->main_image;
        }

        if (file_exists(public_path('uploads/demo_product_image/noImage.jpg'))) {
            return asset('uploads/demo_product_image/noImage.jpg');
        }

        return 'https://images.unsplash.com/photo-1546069901-ba9599a7e63c?w=150&q=80';
    }
}
