<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Registration extends Model
{
    use HasFactory;
    protected $table = 'registration';
    protected $primaryKey = 'r_id';

    protected $fillable = [
        'r_student_id',
        'r_course_id',
        'r_grade',
        'r_semester',
        'r_year',
        'supervisor_id'
    ];

    public function courses()
    {
        return $this->belongsTo(Course::class, 'r_course_id', 'c_id');
    }

    public function users()
    {
        return $this->belongsTo(User::class, 'r_student_id', 'u_id');
    }
    public function studentCompany()
    {
        return $this->belongsTo(StudentCompany::class, 'r_id', 'sc_registration_id');
    }
 
    public function supervisor()
    {
        return $this->belongsTo(User::class, 'supervisor_id', 'u_id');
    }

    //     public function studentCompany2()
    //     {
    //         return $this->hasOne(StudentCompany::class, 'r_id', 'sc_registration_id');
    //     }
}
