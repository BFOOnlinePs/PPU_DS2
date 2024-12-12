<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SupervisorAssistant extends Model
{
    use HasFactory;
    protected $table = 'supervisor_assistants';
    protected $primaryKey = 'sa_id';
    public function assistantUser(){
        return $this->belongsTo(User::class, 'sa_assistant_id', 'u_id');
    }
    public function supervisorUser(){
        return $this->belongsTo(User::class, 'sa_supervisor_id', 'u_id');
    }
}
