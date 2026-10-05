<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Conversation extends Model
{
    use HasFactory;

    protected $fillable = [
        'social_account_id',
        'platform',
        'external_conversation_id',
        'customer_name',
        'customer_id',
        'customer_avatar',
        'ai_active',
        'status',
        'lead_score',
        'last_message_at'
    ];

    public function account()
    {
        return $this->belongsTo(SocialAccount::class, 'social_account_id');
    }

    public function messages()
    {
        return $this->hasMany(ConversationMessage::class);
    }
}
