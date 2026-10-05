<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GroupPost extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'title',
        'slug',
        'content',
        'image',
        'category',
        'likes_count',
        'views_count',
        'status',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
