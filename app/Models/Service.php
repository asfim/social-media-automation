<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Service extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'category',
        'description',
        'features',
        'price',
        'discount_price',
        'availability',
        'url',
        'image',
        'status'
    ];
}
