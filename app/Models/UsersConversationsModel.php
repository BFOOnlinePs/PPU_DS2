<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UsersConversationsModel extends Model
{
    use HasFactory;

    protected $table = 'users_conversations';
    protected $primaryKey = 'uc_id';


    public function conversation()
    {
        return $this->belongsTo(ConversationsModel::class, 'uc_conversation_id', 'c_id');
    }
    public function lastMessage()
    {
        return $this->hasOne(MessageModel::class, 'm_conversation_id', 'uc_conversation_id')
            ->latestOfMany('created_at');
        // ->orderBy('created_at', 'desc')
        // ->orderBy('m_id', 'desc');
    }

    public function scopeOrderByMessageDate($query)
    {
        return $query->join('messages', 'messages.m_conversation_id', '=', 'users_conversations.uc_conversation_id')
            ->orderBy('messages.created_at', 'desc');
    }

    public function receive()
    {
        return $this->belongsTo(User::class, 'uc_user_id');
    }
}
