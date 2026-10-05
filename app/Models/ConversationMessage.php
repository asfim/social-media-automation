<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ConversationMessage extends Model
{
    use HasFactory;

    protected $fillable = [
        'conversation_id',
        'external_message_id',
        'message_text',
        'sender_type',
        'sender_id',
        'ai_confidence',
        'is_read'
    ];

    public function conversation()
    {
        return $this->belongsTo(Conversation::class);
    }
}
