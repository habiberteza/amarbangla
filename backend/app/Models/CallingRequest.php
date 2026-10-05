<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CallingRequest extends Model
{
    use HasFactory;

    protected $table = 'calling_request';
    protected $primaryKey = 'calling_id';
    public $timestamps = false;

    protected $fillable = [
        'customer_id',
        'agent_id',
        'status',
        'description',
        'image',
        'customer_name',
        'phone',
        'notes',
        'admin_notes',
    ];

    public function getIdAttribute()
    {
        return $this->attributes['calling_id'] ?? null;
    }

    public function customer()
    {
        return $this->belongsTo(Customer::class, 'customer_id', 'customer_id');
    }

    public function agent()
    {
        return $this->belongsTo(Agent::class, 'agent_id', 'agent_id');
    }

    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id', 'prod_id');
    }
}
