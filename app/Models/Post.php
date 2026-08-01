<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Post extends Model
{
    use HasFactory;

    //fillable data try here
     protected $fillable = [
        'user_id',
        'title',
        'description',
        'image_url'
    ];

    //user
    public function user() : BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function getImageUrlAttribute($value)
    {
        return $value
            ? asset($value)
            : null;
    }
}
