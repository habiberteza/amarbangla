<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    protected $primaryKey = 'customer_id';

    protected $appends = ['id'];

    public function getIdAttribute()
    {
        return $this->attributes['customer_id'] ?? null;
    }

    protected $fillable = [
        'user_id',
        'customer_type_id',
        'cus_type_id',
        'customer_name',
        'name',
        'father_name',
        'mother_name',
        'age',
        'phone',
        'mobile',
        'email',
        'password',
        'pass',
        'pic',
        'nid',
        'address',
        'division',
        'district',
        'upazila',
        'pourashava',
        'ward',
        'balance',
        'status',
        'notes',
        'mac_address',
        'global_address_id',
    ];

    protected $casts = [
        'balance' => 'decimal:2',
    ];

    public function getNameAttribute()
    {
        return $this->attributes['name'] ?? ($this->attributes['customer_name'] ?? '');
    }

    public function setNameAttribute($value)
    {
        $this->attributes['name'] = $value;
        $this->attributes['customer_name'] = $value;
    }

    public function getPhoneAttribute()
    {
        if (!empty($this->attributes['phone'])) {
            return $this->attributes['phone'];
        }
        if (!empty($this->attributes['mobile'])) {
            $digits = (string) $this->attributes['mobile'];
            return str_starts_with($digits, '0') ? $digits : ('0' . $digits);
        }
        return null;
    }

    public function setPhoneAttribute($value)
    {
        $this->attributes['phone'] = $value;
        if ($value) {
            $digits = preg_replace('/[^0-9]/', '', $value);
            if ($digits) {
                $this->attributes['mobile'] = (int) ltrim($digits, '0');
            }
        }
    }

    public function customerType()
    {
        return $this->belongsTo(CustomerType::class, 'cus_type_id', 'cus_type_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function orders()
    {
        return $this->hasMany(Order::class, 'customer_phone', 'phone');
    }

    public function globalAddress()
    {
        return $this->belongsTo(LegacyGlobalAddress::class, 'global_address_id', 'global_address_id');
    }

    public function getResolvedDivisionAttribute()
    {
        $val = $this->division ?: ($this->globalAddress->division ?? null);
        return is_numeric($val) ? divisionname($val) : $val;
    }

    public function getResolvedDistrictAttribute()
    {
        $val = $this->district ?: ($this->globalAddress->district ?? ($this->globalAddress->zila ?? null));
        return is_numeric($val) ? districtname($val) : $val;
    }

    public function getResolvedUpazilaAttribute()
    {
        $val = $this->upazila ?: ($this->globalAddress->upazila ?? null);
        return is_numeric($val) ? upazilaname($val) : $val;
    }

    public function getResolvedPourashavaAttribute()
    {
        $val = $this->pourashava ?: ($this->globalAddress->pourashava ?? null);
        return pourashavaUnionName($val);
    }

    public function getResolvedWardAttribute()
    {
        return $this->ward ?: ($this->globalAddress->ward ?? null);
    }

    public function invoices()
    {
        return $this->hasMany(Invoice::class, 'customer_id', 'customer_id');
    }

    public function getTotalOrdersCountAttribute(): int
    {
        $invCount = $this->invoices()->count();
        if ($invCount > 0) {
            return $invCount;
        }
        return $this->orders()->count();
    }

    public function getTotalSpentAttribute(): float
    {
        $invSpent = (float) $this->invoices()->sum('final_amount');
        if ($invSpent > 0) {
            return $invSpent;
        }
        return (float) $this->orders()->where('payment_status', 'paid')->sum('total_amount');
    }
}
