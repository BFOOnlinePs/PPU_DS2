<?php

namespace App\Helpers;

use App\Models\ConversationMessagesSeenModel;
use App\Models\MessageModel;

class ConversationMessageHelper
{
    public static function lastMessage($conversationId)
    {
        return MessageModel::where('m_conversation_id', $conversationId)->orderBy('created_at', 'desc')->first();
    }

    public static function isMessageSeen($messageId, $userId)
    {
        $seen = ConversationMessagesSeenModel::where('cms_message_id', $messageId)->where('cms_receiver_id', $userId)->first();
        if ($seen) {
            return true;
        }
        return false;
    }
}
