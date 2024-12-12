<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EvaluationSubmissionScoresModel extends Model
{
    use HasFactory;

    protected $table = 'evaluation_submission_scores';
    protected $primaryKey = 'ss_id';
}
