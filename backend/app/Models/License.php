<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class License extends Model
{
    use HasFactory;

    protected $fillable = [
        'shop_id',
        'shop_name',
        'vendor_name',
        'license_type',
        'license_number',
        'license_key',
        'document_file',
        'issue_date',
        'expiry_date',
        'start_date',
        'end_date',
        'status',
        'notes',
    ];

    protected $casts = [
        'issue_date'  => 'date',
        'expiry_date' => 'date',
        'start_date'  => 'date',
        'end_date'    => 'date',
    ];

    public function shop()
    {
        return $this->belongsTo(Shop::class);
    }

    // Accessor / Mutator for license_key (synced with license_number)
    public function getLicenseKeyAttribute()
    {
        return $this->attributes['license_key'] ?? ($this->attributes['license_number'] ?? null);
    }

    public function setLicenseKeyAttribute($value)
    {
        $this->attributes['license_key'] = $value;
        if (empty($this->attributes['license_number'])) {
            $this->attributes['license_number'] = $value;
        }
    }

    // Accessor / Mutator for start_date (synced with issue_date)
    public function getStartDateAttribute()
    {
        $val = $this->attributes['start_date'] ?? ($this->attributes['issue_date'] ?? null);
        return $val ? $this->asDate($val) : null;
    }

    public function setStartDateAttribute($value)
    {
        $this->attributes['start_date'] = $value;
        if (empty($this->attributes['issue_date'])) {
            $this->attributes['issue_date'] = $value;
        }
    }

    // Accessor / Mutator for end_date (synced with expiry_date)
    public function getEndDateAttribute()
    {
        $val = $this->attributes['end_date'] ?? ($this->attributes['expiry_date'] ?? null);
        return $val ? $this->asDate($val) : null;
    }

    public function setEndDateAttribute($value)
    {
        $this->attributes['end_date'] = $value;
        if (empty($this->attributes['expiry_date'])) {
            $this->attributes['expiry_date'] = $value;
        }
    }
}
