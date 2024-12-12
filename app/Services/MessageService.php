<?php

namespace App\Services;

use App\Enums\NotificationTypeEnum;
use App\Models\ConversationMessagesSeenModel;
use App\Models\MessageModel;
use App\Models\UsersConversationsModel;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class MessageService
{
    protected $fcmService;

    public function __construct(FcmService $fcmService)
    {
        $this->fcmService = $fcmService;
    }

    public function createMessage($conversation_id, $message_text, $file = null): ?MessageModel
    {
        try {
            $message = new MessageModel();

            $message->m_conversation_id = $conversation_id;
            $message->m_sender_id = Auth::user()->u_id;
            $message->m_message_text = $message_text;

            if ($file) {
                $extension = $file->getClientOriginalExtension();
                $file_name = time() . '_' . uniqid() . '.' . $extension;
                $folderPath = 'uploads/mails';
                $file->storeAs($folderPath, $file_name, 'public');

                $message->m_message_file = $file_name;
                Log::info('aseel message file');
            }

            $isSaved = $message->save() ? $message : null;

            if (!$isSaved) {
                return $isSaved;
            }

            // send a notification

            $user_ids_json = UsersConversationsModel::where('uc_conversation_id', $conversation_id)
                ->first()
                ->uc_user_id;

            $user_ids = json_decode($user_ids_json, true);

            // convert to int
            $user_ids = array_map('intval', $user_ids);

            Log::info('aseel user ids', $user_ids);

            $this->fcmService->sendNotification(
                NotificationTypeEnum::MESSAGE,
                $user_ids,
                $conversation_id,
                $message->m_id,
            );

            return $isSaved;
        } catch (\Exception  $e) {
            Log::error('ASEEL NEED FIX: Error while creating message', ['error' => $e->getMessage()]);
            return null;
        }
    }


    public function markMessageAsSeen(int $conversation_id, int $message_id, int $user_id): bool
    {
        // if exists in seen table, update it, else create it
        $last_message_seen = ConversationMessagesSeenModel::where('cms_conversation_id', $conversation_id)
            ->where('cms_receiver_id', $user_id)
            ->first();

        if ($last_message_seen) {
            $last_message_seen->cms_message_id = $message_id;
            return $last_message_seen->save();
        } else {
            $last_message_seen = new ConversationMessagesSeenModel();
            $last_message_seen->cms_conversation_id = $conversation_id;
            $last_message_seen->cms_message_id = $message_id;
            $last_message_seen->cms_receiver_id = $user_id;
            return $last_message_seen->save();
        }
    }

    public function unseenConversationsCount($user_id): int
    {
        $conversation_ids_list = UsersConversationsModel::whereJsonContains('uc_user_id', (string) $user_id)
            ->pluck('uc_conversation_id');

        Log::info('aseel conversation_ids_list ', $conversation_ids_list->toArray());

        if ($conversation_ids_list->isEmpty()) {
            return 0;
        }

        $last_message_ids = MessageModel::whereIn('m_conversation_id', $conversation_ids_list)
            ->selectRaw('MAX(m_id) as last_message_id')
            ->groupBy('m_conversation_id')
            ->pluck('last_message_id')
            ->toArray();

        Log::info('aseel last_message_ids ', $last_message_ids);

        $last_message_seen = ConversationMessagesSeenModel::whereIn('cms_message_id', $last_message_ids)
            ->where('cms_receiver_id', $user_id)
            ->pluck('cms_message_id')
            ->toArray();

        Log::info('Seen Message IDs: ', $last_message_seen);

        $unseen_conversations_count = count(array_diff($last_message_ids, $last_message_seen));

        return $unseen_conversations_count;
    }
}
