<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FacebookPage extends Model
{
    use HasFactory;

    protected $fillable = [
        'social_account_id',
        'page_id',
        'page_name',
        'page_access_token',
        'is_active'
    ];

    public function account()
    {
        return $this->belongsTo(SocialAccount::class, 'social_account_id');
    }
}
