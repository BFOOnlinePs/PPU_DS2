<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class surveySumbission extends Model
{
    use HasFactory;
    protected $table = 'survey_submission';
    protected $primaryKey = 'ss_id';

    public function questions(){
        return $this->belongsTo(surveyQuestions::class, 'ss_q_id', 'sq_id');
    }
    public function options(){
        return $this->belongsTo(surveyQuestionsOptions::class, 'sqo_sq_id', 'ss_q_id');
    }
    public function survey(){
        return $this->belongsTo(survey::class, 'ss_s_id', 'ss_id');
    }
    public function sumbission(){
        return $this->belongsTo(survey::class, 'ss_s_id', 'ss_id');
    }

    
}
