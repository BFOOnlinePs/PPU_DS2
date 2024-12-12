<?php

namespace App\Imports;

use App\Models\Course;
use App\Models\SemesterCourse;
use Maatwebsite\Excel\Concerns\ToModel;

class CoursesImport implements ToModel
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    protected $additionalData , $startRow , $cnt , $courses_array;
    public function __construct($additionalData)
    {
        $this->additionalData = $additionalData;
        $this->startRow = 1;
        $this->cnt = 0;
        $this->courses_array = array();
    }
    public function model(array $row)
    {
        if ($this->startRow == 1) {
            $this->startRow++;
            return null;
        }
        $course = Course::where('c_course_code' , $row[$this->additionalData['course_id']])
                        ->where('c_name' , $row[$this->additionalData['course_name']])
                        ->exists();
        if($course) {
            $new_course = Course::where('c_course_code' , $row[$this->additionalData['course_id']])
                ->where('c_name' , $row[$this->additionalData['course_name']])
                ->first();
            $exist_semester_coures = SemesterCourse::where('sc_course_id' , $new_course->c_id)
                ->where('sc_semester' , $row[$this->additionalData['semester']])
                ->where('sc_year' , $row[$this->additionalData['year']])
                ->exists();
            if(!($exist_semester_coures)) {
                $semester_coures = new SemesterCourse();
                $semester_coures->sc_course_id = $new_course->c_id;
                $semester_coures->sc_semester = $row[$this->additionalData['semester']] ?? 1;
                $semester_coures->sc_year = $row[$this->additionalData['year']] ?? 2024;
                $semester_coures->save();
            }
            return null;

        }
        if (empty($row[$this->additionalData['course_id']]) || empty($row[$this->additionalData['course_name']])) {
            return null; // Skip this row if title or description is empty
        }
        $return_course = new Course([
            'c_course_code' => $row[$this->additionalData['course_id']],
            'c_name' => $row[$this->additionalData['course_name']],
            'c_hours' => 3,
            'c_description' => '',
            'c_course_type' => 1,
            'c_reference_code' => ''
        ]);
        if($return_course->save()) {
            $this->cnt++;
            $course = Course::where('c_course_code' , $row[$this->additionalData['course_id']])
                ->where('c_name' , $row[$this->additionalData['course_name']])
                ->first();
            $exist_semester_coures = SemesterCourse::where('sc_course_id' , $course->c_id)
                ->where('sc_semester' , $row[$this->additionalData['semester']])
                ->where('sc_year' , $row[$this->additionalData['year']])
                ->exists();
            if(!($exist_semester_coures)) {
                $semester_coures = new SemesterCourse();
                $semester_coures->sc_course_id = $course->c_id;
                $semester_coures->sc_semester = $row[$this->additionalData['semester']];
                $semester_coures->sc_year = $row[$this->additionalData['year']];
                $semester_coures->save();
            }
            array_push($this->courses_array , $row[$this->additionalData['course_id']]);
            array_push($this->courses_array , $row[$this->additionalData['course_name']]);
            return $return_course;
        }
        else {
            return null;
        }
    }
    public function getCount()
    {
        return $this->cnt;
    }
    public function getCoursesArray()
    {
        return $this->courses_array;
    }
}
