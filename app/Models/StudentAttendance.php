<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StudentAttendance extends Model
{
    use HasFactory;
    protected $table = 'students_attendance';
    protected $primaryKey = 'sa_id';

    protected $fillable = [
        'sa_student_id',
        'sa_student_company_id',
        'sa_start_time_latitude',
        'sa_start_time_longitude',
        'sa_description',
        'sa_in_time',
        'sa_end_time_longitude',
        'sa_end_time_latitude',
        'sa_out_time',
        'sa_status',
    ];

    public function report()
    {
        return $this->hasOne(StudentReport::class, 'sr_student_attendance_id', 'sa_id');
    }

    public function training()
    {
        return $this->belongsTo(StudentCompany::class, 'sa_student_company_id', 'sc_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'sa_student_id', 'u_id');
    }
}
