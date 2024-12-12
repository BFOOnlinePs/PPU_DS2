<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EvaluationCriteriaModel extends Model
{
    use HasFactory;

    protected $table = 'evaluation_criteria';

    protected $primaryKey = 'ec_id';

    public function criteria()
    {
        return $this->belongsTo(CriteriaModel::class, 'ec_criteria_id', 'c_id');
    }
}
