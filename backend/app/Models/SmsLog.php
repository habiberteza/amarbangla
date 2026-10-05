<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SmsLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'recipient_phone',
        'recipient_name',
        'recipient_type',
        'message',
        'gateway',
        'status',
        'response_payload',
    ];
}
