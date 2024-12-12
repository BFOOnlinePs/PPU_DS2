<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class surveyTargetGroup extends Model
{
    use HasFactory;
    protected $table = 'survey_target_group';
    protected $primaryKey = 'st_id';

    public function survey(){
        return $this->belongsTo(survey::class, 'st_id', 'st_id');
    }
}
