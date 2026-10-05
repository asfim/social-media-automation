<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AutoCommentRule extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'hide_spam',
        'reply_to_leads',
        'generic_reply',
        'is_active'
    ];
}
