<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    use HasFactory;

    protected $fillable = [
        'social_account_id',
        'platform',
        'external_comment_id',
        'external_post_id',
        'customer_name',
        'customer_id',
        'comment_text',
        'ai_classification',
        'reply_status',
        'ai_reply_text',
        'lead_score'
    ];

    public function account()
    {
        return $this->belongsTo(SocialAccount::class, 'social_account_id');
    }
}
