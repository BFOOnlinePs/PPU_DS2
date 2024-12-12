<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Course extends Model
{
    use HasFactory;
    protected $table = 'courses';
    protected $primaryKey = "c_id";
    protected $fillable = [
        'c_course_code',
        'c_name',
        'c_hours',
        'c_description',
        'c_course_type',
        'c_reference_code'
    ];

    public function registrations(){
        return $this->hasMany(Registration::class, 'r_course_id', 'c_id');
    }

    public function semeste_courses(){
        return $this->belongsTo(SemesterCourse::class, 'c_id','sc_course_id');
    }
}
