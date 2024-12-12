<?php

namespace App\Http\Controllers\apisControllers\students;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\Registration;
use App\Models\SemesterCourse;
use App\Models\SystemSetting;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class StudentCoursesController extends Controller
{
    // get a student courses by its id
    // we used that for supervisor
    public function getStudentCoursesById(Request $request)
    {
        $validator = Validator::make(
            $request->all(),
            ['student_id' => 'required'],
            ['student_id.required' => trans('messages.student_id_required')]
        );

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                "message" => $validator->errors()->first(),
            ]);
        }

        $student_id = $request->input('student_id');

        $user = User::where('u_id', $student_id)->where('u_role_id', 2)->first();

        if (!$user) {
            return response()->json([
                'status' => false,
                "message" => trans('messages.student_id_required'),
            ]);
        }

        $system_settings = SystemSetting::first();

        $current_year = $system_settings->ss_year;
        $current_semester = $system_settings->ss_semester_type;

        $student_courses_id_registered = Registration::where('r_student_id', $student_id)
            ->where('r_year', $current_year)
            ->where('r_semester', $current_semester)
            ->pluck('r_course_id');

        $student_registered_courses = Course::whereIn('c_id', $student_courses_id_registered)->get();

        return response()->json([
            'status' => true,
            'courses' => $student_registered_courses,
        ]);
    }


    // add course for student
    public function addStudentCourse(Request $request)
    {
        $validator = Validator::make(
            $request->all(),
            [
                'student_id' => 'required',
                'course_id' => 'required'
            ],
            [
                'student_id.required' => trans('messages.student_id_required'),
                'course_id.required' => trans('messages.course_id_required'),
            ]
        );

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                "message" => $validator->errors()->first(),
            ]);
        }

        $student_id = $request->input('student_id');

        $user = User::where('u_id', $student_id)->where('u_role_id', 2)->first();

        if (!$user) {
            return response()->json([
                'status' => false,
                'message' =>  trans('messages.student_id_required'),
            ]);
        }

        $course_id = $request->input('course_id');

        $course = Course::where('c_id', $course_id)->first();

        if (!$course) {
            return response()->json([
                'status' => false,
                'message' => trans('messages.course_id_not_exists'),
            ]);
        }

        $system_settings = SystemSetting::first();

        $current_year = $system_settings->ss_year;
        $current_semester = $system_settings->ss_semester_type;


        $register_course = Registration::create([
            'r_student_id' => $student_id,
            'r_course_id' => $course_id,
            'r_semester' => $current_semester,
            'r_year' => $current_year
        ]);

        return response()->json([
            'status' => true,
            'message' => trans('messages.course_added_for_student'),
            'course' => $course
        ]);
    }


    public function deleteStudentCourse(Request $request)
    {
        $validator = Validator::make(
            $request->all(),
            [
                'student_id' => 'required',
                'course_id' => 'required'
            ],
            [
                'student_id.required' => trans('messages.student_id_required'),
                'course_id.required' => trans('messages.course_id_required'),
            ]
        );

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                "message" => $validator->errors()->first(),
            ]);
        }

        $student_id = $request->input('student_id');

        $user = User::where('u_id', $student_id)->where('u_role_id', 2)->first();

        if (!$user) {
            return response()->json([
                'status' => false,
                'message' => trans('messages.student_id_required'),
            ]);
        }

        $course_id = $request->input('course_id');

        $course = Course::where('c_id', $course_id)->first();

        if (!$course) {
            return response()->json([
                'status' => false,
                'message' => trans('messages.course_id_not_exists'),
            ]);
        }

        $register_course = Registration::where('r_student_id', $student_id)->where('r_course_id', $course_id)->first();

        if (!$register_course) {
            return response()->json([
                'status' => true,
                'message' => trans('messages.student_not_in_course'),
            ]);
        }

        $register_course->delete();
        return response()->json([
            'status' => true,
            'message' => trans('messages.delete_course_for_student'),
        ]);
    }

    // courses available for student based on semester and course
    // courses that he did not registered for
    public function availableCoursesForStudent(Request $request)
    {
        $validator = Validator::make(
            $request->all(),
            [
                'student_id' => 'required',
            ],
            [
                'student_id.required' => trans('messages.student_id_required'),
            ]
        );

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                "message" => $validator->errors()->first(),
            ]);
        }

        $student_id = $request->input('student_id');

        $user = User::where('u_id', $student_id)->where('u_role_id', 2)->first();

        if (!$user) {
            return response()->json([
                'status' => false,
                'message' => trans('messages.student_id_required'),
            ]);
        }

        $system_settings = SystemSetting::first();

        $current_year = $system_settings->ss_year;
        $current_semester = $system_settings->ss_semester_type;

        $semester_courses_id = SemesterCourse::where('sc_semester', $current_semester)
            ->where('sc_year', $current_year)
            ->pluck('sc_course_id');


        $student_courses_id_registered = Registration::where('r_student_id', $student_id)
            ->where('r_year', $current_year)
            ->where('r_semester', $current_semester)
            ->pluck('r_course_id');


        $available_courses_for_student = Course::whereIn('c_id', $semester_courses_id)
            ->whereNotIn('c_id', $student_courses_id_registered)
            ->get();

        if ($available_courses_for_student->isEmpty()) {
            return response()->json([
                'status' => false,
                'message' => trans('messages.no_courses_available'),
            ]);
        }

        return response()->json([
            'status' => true,
            'available_courses' => $available_courses_for_student
        ]);
    }

    public function getStudentCourseRegistrations(Request $request)
    {
        $validator = Validator::make(
            $request->all(),
            ['student_id' => 'required'],
            ['student_id.required' => trans('messages.student_id_required'),]
        );

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                "message" => $validator->errors()->first(),
            ]);
        }

        $student_id = $request->input('student_id');

        $user = User::where('u_id', $student_id)->where('u_role_id', 2)->first();

        if (!$user) {
            return response()->json([
                'status' => false,
                "message" => trans('messages.student_id_not_exists'),
            ]);
        }

        $system_settings = SystemSetting::first();

        $current_year = $system_settings->ss_year;
        $current_semester = $system_settings->ss_semester_type;

        $student_courses_id_registered = Registration::where('r_student_id', $student_id)
            ->where('r_year', $current_year)
            ->where('r_semester', $current_semester)
            ->with('courses')
            ->get();

        return response()->json([
            'status' => true,
            'courses_registrations' => $student_courses_id_registered,
        ]);
    }
}
