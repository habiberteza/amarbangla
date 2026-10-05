<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DeliveryBoy extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'agent_id',
        'name',
        'father_name',
        'mother_name',
        'age',
        'phone',
        'password',
        'email',
        'nid_number',
        'image',
        'vehicle_type',
        'division',
        'district',
        'upazila',
        'pourashava',
        'ward',
        'address',
        'cash_in_hand',
        'balance',
        'status',
    ];

    protected $hidden = [
        'password',
    ];

    protected $casts = [
        'cash_in_hand' => 'decimal:2',
        'balance'      => 'decimal:2',
        'age'          => 'integer',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function agent()
    {
        return $this->belongsTo(Agent::class);
    }

    public function orders()
    {
        return $this->hasMany(Order::class, 'delivery_boy_id');
    }
}
