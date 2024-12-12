<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SemesterCourseAssistant extends Model
{
    use HasFactory;
    protected $table = 'semester_courses_assistants';
    protected $primaryKey = 'sca_id';
}
