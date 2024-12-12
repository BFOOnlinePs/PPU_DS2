<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NotificationModel extends Model
{
    use HasFactory;

    protected $table = 'notifications';

    protected $fillable = [
        'title',
        'body',
        'target_type',
        'target_id',
        'related_type',
        'related_id',
        'target_url',
    ];

    public function users()
    {
        return $this->belongsToMany(User::class, 'notifications_users', 'notification_id', 'user_id');
    }
}
