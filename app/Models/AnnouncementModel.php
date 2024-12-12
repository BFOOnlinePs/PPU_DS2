<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AnnouncementModel extends Model
{
    use HasFactory;

    protected $table = 'announcements';
    protected $primaryKey = 'a_id';

    public function addedBy(){
        return $this->hasOne(user::class, 'u_id', 'a_added_by');
    }
}
