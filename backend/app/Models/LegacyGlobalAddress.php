<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LegacyGlobalAddress extends Model
{
    use HasFactory;

    protected $table = 'global_address';
    protected $primaryKey = 'global_address_id';
    public $timestamps = false;

    protected $fillable = [
        'global_address_id',
        'division',
        'zila',
        'district',
        'upazila',
        'pourashava',
        'ward',
        'is_active',
        'delivery_charge',
    ];
}
