<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class survey extends Model
{
    use HasFactory;
    protected $table = 'survey';
    protected $primaryKey = 's_id';

    public function targets(){
        return $this->belongsTo(surveyTargetGroup::class, 'st_id', 'st_id');
    }
    public function users(){
        return $this->hasOne(user::class, 'u_id', 's_added_by');
    }

    public function questions(){
        return $this->hasMany(surveyQuestions::class, 'sq_s_id', 's_id');
    }
    public function submissions(){
      
        return $this->hasMany(surveySumbission::class, 'ss_s_id', 's_id');

    }

    
}
