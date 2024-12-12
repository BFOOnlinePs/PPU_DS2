<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MessageModel extends Model
{
    use HasFactory;
    protected $table = 'messages';
    protected $primaryKey = 'm_id';

    protected $fillable = [
        'm_conversation_id',
        'm_sender_id',
        'm_message_text',
        'm_message_file',
    ];

    public function sender() {
        return $this->belongsTo(User::class, 'm_sender_id', 'u_id');
    }

    public function conversation() {
        return $this->belongsTo(ConversationsModel::class, 'm_conversation_id', 'c_id');
    }

    public function conversationMessagesSeen()
{
    return $this->hasMany(ConversationMessagesSeenModel::class, 'cms_message_id', 'm_id');
}
}
