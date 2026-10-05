<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'id',
        'invoice_id',
        'order_number',
        'user_id',
        'shop_id',
        'agent_id',
        'delivery_boy_id',
        'customer_name',
        'customer_phone',
        'customer_email',
        'shipping_address',
        'division',
        'district',
        'upazila',
        'pourashava',
        'ward',
        'global_address_id',
        'notes',
        'subtotal',
        'discount',
        'shipping_fee',
        'total_amount',
        'payment_method',
        'payment_status',
        'order_status',
        'delivery_status',
        'transaction_id',
        'created_at',
        'updated_at',
    ];

    protected $casts = [
        'subtotal' => 'decimal:2',
        'discount' => 'decimal:2',
        'shipping_fee' => 'decimal:2',
        'total_amount' => 'decimal:2',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'user_id');
    }

    public function items()
    {
        return $this->hasMany(OrderItem::class, 'order_id');
    }

    public function shop()
    {
        return $this->belongsTo(Shop::class, 'shop_id', 'sch_id');
    }

    public function agent()
    {
        return $this->belongsTo(Agent::class, 'agent_id', 'id');
    }

    public function deliveryBoy()
    {
        return $this->belongsTo(DeliveryBoy::class, 'delivery_boy_id', 'id');
    }

    public function globalAddress()
    {
        return $this->belongsTo(LegacyGlobalAddress::class, 'global_address_id', 'global_address_id');
    }

    public function invoice()
    {
        return $this->belongsTo(Invoice::class, 'invoice_id', 'invoice_id');
    }

    public function customer()
    {
        return $this->belongsTo(Customer::class, 'customer_phone', 'phone');
    }

    public function getCustomerIdAttribute()
    {
        if ($this->relationLoaded('invoice') && $this->invoice) {
            return $this->invoice->customer_id;
        }
        if ($this->invoice_id) {
            $inv = Invoice::select('customer_id')->where('invoice_id', $this->invoice_id)->first();
            if ($inv && $inv->customer_id) {
                return $inv->customer_id;
            }
        }
        if ($this->relationLoaded('customer') && $this->customer) {
            return $this->customer->customer_id;
        }
        if ($this->customer_phone) {
            $digits = preg_replace('/[^0-9]/', '', $this->customer_phone);
            $cleanMobile = ltrim($digits, '0');
            $c = Customer::select('customer_id')
                ->where('phone', $this->customer_phone)
                ->orWhere('mobile', $cleanMobile)
                ->first();
            if ($c) {
                return $c->customer_id;
            }
        }
        return $this->user_id;
    }

    public static function generateOrderNumber(): string
    {
        return 'AB-' . date('Ymd') . '-' . strtoupper(substr(uniqid(), -5));
    }
}
