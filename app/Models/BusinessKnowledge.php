<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BusinessKnowledge extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'category',
        'content',
        'is_active'
    ];
}
