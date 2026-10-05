<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ShopCategory extends Model
{
    use HasFactory;

    protected $table = 'shop_category';
    protected $primaryKey = 'shop_cat_id';
    public $timestamps = false;

    protected $fillable = [
        'name',
        'parent_cat_id',
        'show_home',
        'title',
        'image',
        'createdBy',
        'createdDtm',
        'updatedBy',
        'updatedDtm',
        'deleted',
        'deletedRole',
    ];

    protected $casts = [
        'shop_cat_id'   => 'integer',
        'parent_cat_id' => 'integer',
        'show_home'     => 'string',
        'createdBy'     => 'integer',
        'updatedBy'     => 'integer',
        'deleted'       => 'integer',
    ];

    protected $appends = ['image_url', 'display_name'];

    public function parent()
    {
        return $this->belongsTo(ShopCategory::class, 'parent_cat_id', 'shop_cat_id');
    }

    public function children()
    {
        return $this->hasMany(ShopCategory::class, 'parent_cat_id', 'shop_cat_id');
    }

    public function shops()
    {
        return $this->hasMany(Shop::class, 'shop_cat_id', 'shop_cat_id');
    }

    public function getDisplayNameAttribute(): string
    {
        return !empty($this->title) ? $this->title : ($this->name ?? '');
    }

    public function getImageUrlAttribute(): string
    {
        if (empty($this->image)) {
            return asset('uploads/schools/no_image.jpg');
        }

        if (str_starts_with($this->image, 'http://') || str_starts_with($this->image, 'https://')) {
            return $this->image;
        }

        // Check public/uploads/schools
        if (file_exists(public_path('uploads/schools/' . $this->image))) {
            return asset('uploads/schools/' . $this->image);
        }

        // Check public/uploads/shop_category
        if (file_exists(public_path('uploads/shop_category/' . $this->image))) {
            return asset('uploads/shop_category/' . $this->image);
        }

        // Check direct path
        if (file_exists(public_path($this->image))) {
            return asset($this->image);
        }

        return asset('uploads/schools/no_image.jpg');
    }
}

