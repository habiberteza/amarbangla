<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Invoice extends Model
{
    use HasFactory;

    protected $table = 'invoice';
    protected $primaryKey = 'invoice_id';
    public $timestamps = false;

    protected $fillable = [
        'invoice_id',
        'customer_id',
        'pymnt_type_id',
        'customer_name',
        'amount',
        'entire_sale_discount',
        'vat',
        'delivery_charge',
        'final_amount',
        'profit',
        'nagad_paid',
        'bank_paid',
        'bank_id',
        'chaque_paid',
        'chaque_id',
        'seller_id',
        'due',
        'global_address_id',
        'address',
        'delivery_type',
        'creation_timestamp',
        'payment_timestamp',
        'payment_method',
        'payment_details',
        'status',
        'timestamp',
        'year',
        'createdDtm',
        'createdBy',
        'updatedBy',
        'updatedDtm',
        'deleted',
        'deletedRole',
    ];

    protected $casts = [
        'amount'       => 'decimal:2',
        'final_amount' => 'decimal:2',
        'due'          => 'decimal:2',
        'profit'       => 'decimal:2',
    ];

    public function customer()
    {
        return $this->belongsTo(Customer::class, 'customer_id', 'customer_id');
    }

    public function getStatusLabelAttribute(): string
    {
        return match ((string) $this->status) {
            '1'     => 'Pending',
            '2'     => 'Processing / Confirmed',
            '3'     => 'Completed / Delivered',
            '4'     => 'Cancelled',
            default => 'Order #' . $this->status,
        };
    }

    public function getStatusBadgeClassAttribute(): string
    {
        return match ((string) $this->status) {
            '1'     => 'bg-amber-100 text-amber-800 border-amber-200',
            '2'     => 'bg-blue-100 text-blue-800 border-blue-200',
            '3'     => 'bg-emerald-100 text-emerald-800 border-emerald-200',
            '4'     => 'bg-rose-100 text-rose-800 border-rose-200',
            default => 'bg-gray-100 text-gray-800 border-gray-200',
        };
    }
}
