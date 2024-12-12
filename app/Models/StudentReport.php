<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StudentReport extends Model
{
    use HasFactory;
    protected $table = 'student_reports';
    protected $primaryKey = 'sr_id';

    protected $fillable = [
        'sr_student_attendance_id',
        'sr_student_id',
        'sr_report_text',
        'sr_attached_file',
        'sr_notes',
        'sr_notes_company',
        'sr_submit_longitude',
        'sr_submit_latitude'
    ];

    public function attendance()
    {
        return $this->belongsTo(StudentAttendance::class, 'sr_student_attendance_id', 'sa_id');
    }

    public function reportAttendance()
    {
        return $this->belongsTo(StudentAttendance::class, 'sr_student_attendance_id', 'sa_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'sr_student_id', 'u_id');
    }

}
