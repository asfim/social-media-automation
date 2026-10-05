<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ScheduledPost extends Model
{
    use HasFactory;

    protected $fillable = [
        'content_post_id',
        'scheduled_at',
        'status'
    ];
}
