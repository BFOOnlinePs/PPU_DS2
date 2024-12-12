<?php

namespace App\Services;

use App\Models\NotificationUserModel;

class NotificationsService
{

    public function unseenNotificationsCount($user_id): int
    {
        return NotificationUserModel::where('user_id', $user_id)
            ->where('is_read', 0)
            ->count();
    }
}
