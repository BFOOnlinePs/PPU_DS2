<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ConversationsModel extends Model
{
    use HasFactory;
    protected $table = 'conversations';
    protected $primaryKey = 'c_id';

    public function addedByUser()
    {
        return $this->belongsTo(User::class, 'added_by', 'u_id');
    }
    public function participants()
    {
        return $this->hasMany(UsersConversationsModel::class , 'uc_conversation_id' , 'c_id');
    }
    public function messages()
    {
        // return $this->hasMany(Message::class);
    }

    public function user(){
        return $this->belongsTo(User::class,'added_by','u_id');
    }

    public function getLastMessage(){
        return $this->hasMany(MessageModel::class,'m_conversation_id','c_id')->orderBy('m_id','desc')->first();
    }
}
