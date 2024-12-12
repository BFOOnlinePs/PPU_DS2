<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NotificationUserModel extends Model
{
    use HasFactory;

    protected $table = 'notifications_users';

    protected $fillable = [
        'user_id',
        'notification_id',
        'is_read',
    ];
}
