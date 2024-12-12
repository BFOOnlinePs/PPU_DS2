<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StudentCompanyNominationModel extends Model
{
    use HasFactory;

    protected $table = 'student_company_nomination';

    protected $primaryKey = 'scn_id';
}
