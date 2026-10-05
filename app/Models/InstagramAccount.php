<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InstagramAccount extends Model
{
    use HasFactory;

    protected $fillable = [
        'facebook_page_id',
        'ig_account_id',
        'username',
        'is_active'
    ];

    public function facebookPage()
    {
        return $this->belongsTo(FacebookPage::class, 'facebook_page_id');
    }
}
