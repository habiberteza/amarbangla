<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ledger extends Model
{
    use HasFactory;

    protected $fillable = [
        'txn_number',
        'type',
        'category',
        'amount',
        'balance_after',
        'party_type',
        'party_name',
        'reference_id',
        'payment_method',
        'note',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'balance_after' => 'decimal:2',
    ];
}
