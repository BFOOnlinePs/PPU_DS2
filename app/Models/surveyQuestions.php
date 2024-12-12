<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class surveyQuestions extends Model
{
    use HasFactory;
    protected $table = 'survey_questions';
    protected $primaryKey = 'sq_id';
    
    public function options(){
        $x=$this->hasMany(surveyQuestionsOptions::class, 'sqo_sq_id', 'sq_id');
       if($x!=null) return $this->hasMany(surveyQuestionsOptions::class, 'sqo_sq_id', 'sq_id');
       else return $this->hasMany(surveySumbission::class, 'ss_q_id', 'sq_id');

    }
    public function answers(){
      
        return $this->hasMany(surveySumbission::class, 'ss_q_id', 'sq_id');

    }
    public function survey(){
      
        return $this->belongsTo(survey::class, 's_id', 'sq_id');

    }
 

    
}
