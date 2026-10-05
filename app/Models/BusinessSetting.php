<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BusinessSetting extends Model
{
    use HasFactory;

    protected $fillable = [
        'business_name',
        'business_description',
        'business_category',
        'business_location',
        'phone',
        'whatsapp',
        'email',
        'website',
        'business_hours',
        'language',
        'tone'
    ];
}
