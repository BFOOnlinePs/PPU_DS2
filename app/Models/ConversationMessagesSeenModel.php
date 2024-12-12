<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ConversationMessagesSeenModel extends Model
{
    use HasFactory;

    protected $table = 'conversation_messages_seen';
    protected $primaryKey = 'cms_id';

    protected $fillable = [
        'cms_message_id',
        'cms_receiver_id',
    ];
}
