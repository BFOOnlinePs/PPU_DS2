<?php

namespace App\Imports;

use App\Models\Course;
use App\Models\Registration;
use App\Models\User;
use Maatwebsite\Excel\Concerns\ToModel;

class RegistrationsImport implements ToModel
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    protected $additionalData , $startRow , $cnt , $registration_array;
    public function __construct($additionalData)
    {
        $this->additionalData = $additionalData;
        $this->startRow = 1;
        $this->cnt = 0;
        $this->registration_array = array();
    }
    public function model(array $row)
    {
        return 'Mohamad Maraqa';
        if ($this->startRow == 1) {
            $this->startRow++;
            return null;
        }

        $student = User::where('u_username' , $row[$this->additionalData['student_id']])
                        ->where('name' , $row[$this->additionalData['student_name']])
                        ->first();
        $course = Course::where('c_course_code' , $row[$this->additionalData['course_id']])
                        ->where('c_name' , $row[$this->additionalData['course_name']])
                        ->first();
        if (empty($student->u_id) || empty($course->c_id) || empty($row[$this->additionalData['semester']]) || empty($row[$this->additionalData['year']])) {
            return null; // Skip this
        }
        $registration = Registration::where('r_student_id', $student->u_id)
                                    ->where('r_course_id', $course->c_id)
                                    ->where('r_semester' , $row[$this->additionalData['semester']])
                                    ->where('r_year' , $row[$this->additionalData['year']])
                                    ->exists();
        if($registration) {
            return null; // Skip this
        }
        $return_registration = new Registration([
            'r_student_id' => $student->u_id,
            'r_course_id' => $course->c_id,
            'r_semester' => $row[$this->additionalData['semester']],
            'r_year' => $row[$this->additionalData['year']]
        ]);
        if($return_registration->save()) {
            $this->cnt++;
            array_push($this->registration_array , $student->name);
            array_push($this->registration_array , $student->u_username);
            array_push($this->registration_array , $course->c_name);
            array_push($this->registration_array , $row[$this->additionalData['semester']]);
            array_push($this->registration_array , $row[$this->additionalData['year']]);
            return $return_registration;
        }
        else {
            return null;
        }
    }
    public function getCount()
    {
        return $this->cnt;
    }
    public function getRegistrationArray()
    {
        return $this->registration_array;
    }
}
