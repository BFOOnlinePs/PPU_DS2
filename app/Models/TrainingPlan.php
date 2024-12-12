<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TrainingPlan extends Model
{
    use HasFactory;
    protected $table = 'trainings_plans';
    protected $primaryKey = 'tp_id';
}
