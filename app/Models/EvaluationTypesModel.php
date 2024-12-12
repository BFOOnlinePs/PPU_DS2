<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EvaluationTypesModel extends Model
{
    use HasFactory;

    protected $table = 'evaluation_types';

    protected $primaryKey = 'et_id';
}
