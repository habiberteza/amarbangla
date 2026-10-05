<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $primaryKey = 'user_id';

    protected $appends = ['id'];

    public function getIdAttribute()
    {
        return $this->attributes['user_id'] ?? null;
    }

    protected $fillable = [
        'name',
        'father_name',
        'mother_name',
        'age',
        'agent_id',
        'email',
        'phone',
        'mobile',
        'role',
        'role_id',
        'sch_id',
        'avatar',
        'image',
        'division',
        'district',
        'upazila',
        'pourashava',
        'ward',
        'address',
        'nid_number',
        'status',
        'balance',
        'commission_rate',
        'commission_type',
        'password',
        'pass',
    ];

    protected $hidden = [
        'password',
        'pass',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'balance' => 'decimal:2',
            'commission_rate' => 'decimal:2',
        ];
    }

    public function shops()
    {
        return $this->hasMany(Shop::class, 'user_id', 'user_id');
    }

    public function shop()
    {
        return $this->hasOne(Shop::class, 'user_id', 'user_id');
    }

    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    public function agent()
    {
        return $this->belongsTo(Agent::class);
    }

    // Accessor / Mutator for mobile & phone (synced together)
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

    public function getMobileAttribute()
    {
        if (!empty($this->attributes['mobile'])) {
            return $this->attributes['mobile'];
        }
        if (!empty($this->attributes['phone'])) {
            $digits = preg_replace('/[^0-9]/', '', $this->attributes['phone']);
            return $digits ? (int) ltrim($digits, '0') : null;
        }
        return null;
    }

    public function setMobileAttribute($value)
    {
        $this->attributes['mobile'] = $value;
        if ($value && empty($this->attributes['phone'])) {
            $digits = (string) $value;
            $this->attributes['phone'] = str_starts_with($digits, '0') ? $digits : ('0' . $digits);
        }
    }

    // Accessor / Mutator for image (synced with avatar)
    public function getImageAttribute()
    {
        return $this->attributes['image'] ?? ($this->attributes['avatar'] ?? null);
    }

    public function setImageAttribute($value)
    {
        $this->attributes['image'] = $value;
        if (empty($this->attributes['avatar'])) {
            $this->attributes['avatar'] = $value;
        }
    }

    public function isSuperAdmin(): bool
    {
        return $this->role === 'super_admin';
    }

    public function isVendor(): bool
    {
        return $this->role === 'vendor';
    }
}
