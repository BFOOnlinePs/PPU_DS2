<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EvaluationsModel extends Model
{
    use HasFactory;

    protected $table = 'evaluations';

    protected $primaryKey = 'e_id';

    public function evaluation_type()
    {
        return $this->belongsTo(EvaluationTypesModel::class , 'e_type_id' , 'et_id');
    }

    public function role()
    {
        return $this->belongsTo(Role::class , 'e_evaluator_role_id' , 'r_id');
    }

}
