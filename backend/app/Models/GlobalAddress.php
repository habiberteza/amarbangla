<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GlobalAddress extends Model
{
    use HasFactory;

    protected $table = 'global_addresses';
    protected $primaryKey = 'id';
    public $timestamps = true;

    protected $fillable = [
        'division',
        'district',
        'upazila',
        'pourashava',
        'ward',
        'post_code',
        'delivery_charge',
        'is_active',
    ];

    public function getDivisionNameAttribute()
    {
        return $this->division;
    }

    public function getDistrictNameAttribute()
    {
        return $this->district;
    }

    public function getUpazilaNameAttribute()
    {
        return $this->upazila;
    }

    public function getPourashavaAttribute($value)
    {
        return match((string)$value) {
            '0' => 'City Corporation',
            '1' => 'Pourashava',
            '2' => 'Union',
            default => $value,
        };
    }

    public function setPourashavaAttribute($value)
    {
        $this->attributes['pourashava'] = match((string)$value) {
            '0' => 'City Corporation',
            '1' => 'Pourashava',
            '2' => 'Union',
            default => $value,
        };
    }

    public function getFormattedWardAttribute()
    {
        $val = trim((string)($this->ward ?? ''));
        if ($val === '' || $val === '—') {
            return null;
        }
        if (preg_match('/^(?:ward\s*)?(\d+.*)$/i', $val, $m)) {
            return 'Ward ' . $m[1];
        }
        return str_starts_with(strtolower($val), 'ward') ? $val : 'Ward ' . $val;
    }
}
