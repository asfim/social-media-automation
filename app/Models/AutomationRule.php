<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AutomationRule extends Model
{
    use HasFactory;

    protected $fillable = [
        'rule_name',
        'platform',
        'trigger_type',
        'keywords',
        'response_type',
        'static_response',
        'ai_enabled',
        'delay_seconds',
        'is_active'
    ];
}
