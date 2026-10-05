<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AutoReplyRule extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'trigger_intent',
        'response_template',
        'is_active'
    ];
}
