<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FieldVisitsModel extends Model
{
    use HasFactory;

    protected $table = 'field_visits';

    protected $primaryKey = 'fv_id';

    public function student()
    {
        return $this->belongsTo(User::class, 'fv_student_id', 'u_id');
    }

    public function supervisor()
    {
        return $this->belongsTo(User::class, 'fv_supervisor_id', 'u_id');
    }

    public function company()
    {
        return $this->belongsTo(Company::class, 'fv_company_id', 'c_id');
    }

}
