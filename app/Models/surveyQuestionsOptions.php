<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class surveyQuestionsOptions extends Model
{
    use HasFactory;
    protected $table = 'survey_question_options';
    protected $primaryKey = 'sqo_id'; 
    
    public function questions(){
        return $this->belongsTo(surveyQuestions::class, 'sqo_sq_id', 'sq_id');
    }
    public function answers(){
      
        return $this->hasMany(surveySumbission::class, 'ss_q_id', 'sqo_sq_id');

    }
}
