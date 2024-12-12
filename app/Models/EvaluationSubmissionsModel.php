<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EvaluationSubmissionsModel extends Model
{
    use HasFactory;

    protected $table = 'evaluation_submissions';
    protected $primaryKey = 'es_id';

}
