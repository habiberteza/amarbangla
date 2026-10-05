<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CustomerType extends Model
{
    use HasFactory;

    protected $table = 'customer_type';
    protected $primaryKey = 'cus_type_id';
    public $timestamps = false;

    protected $fillable = [
        'type_name',
        'name',
        'slug',
        'discount_percentage',
        'description',
        'is_active',
    ];

    protected $appends = [
        'id',
        'name',
        'is_active',
    ];

    public function getIdAttribute()
    {
        return $this->attributes['cus_type_id'] ?? null;
    }

    public function getNameAttribute()
    {
        return $this->attributes['name'] ?? ($this->attributes['type_name'] ?? '');
    }

    public function getIsActiveAttribute(): bool
    {
        return empty($this->attributes['deleted']);
    }

    public function customers()
    {
        return $this->hasMany(Customer::class, 'cus_type_id', 'cus_type_id');
    }
}
