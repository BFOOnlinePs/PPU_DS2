<?php

namespace App\Http\Controllers\project\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\SemesterCourse;
use App\Models\SystemSetting;
use App\Models\Registration;
use App\Models\Course;
use App\Models\Major;
use App\Models\User;

class RegistrationController extends Controller
{
    public function index(){
        $systemSettings = SystemSetting::first();

        $semester = $systemSettings->ss_semester_type;
        $year = $systemSettings->ss_year;

        $data = SemesterCourse::with('courses')->where('sc_semester',$systemSettings->ss_semester_type)->where('sc_year',$systemSettings->ss_year)->get();
        return view('project.admin.registration.index',['data'=>$data,'semester'=>$semester, 'year'=>$year]);
    }

    public function CourseStudents($id){
        $systemSettings = SystemSetting::first();

        $semester = $systemSettings->ss_semester_type;
        $year = $systemSettings->ss_year;


        $data = Registration::with('users','courses')->where('r_course_id',$id)
        ->where('r_year',$year)
        ->where('r_semester',$semester)
        ->get();

        $course = Course::where('c_id',$id)->first();

        return view('project.admin.registration.courseStudents',['data'=>$data,'course'=>$course]);

    }

    public function SemesterStudents()
    {
        $systemSettings = SystemSetting::first();
        $semester = $systemSettings->ss_semester_type;
        $year = $systemSettings->ss_year;

        // To get majors
        $majors = Major::get();
        // To get semester courses for this semester
        $semester_courses = SemesterCourse::where('sc_semester' , $semester)
        ->where('sc_year' , $year)
        ->get();
        $supervisors = User::where('u_role_id',10)->get();
        return view('project.admin.registration.semesterStudents',[
            'semester_courses'=>$semester_courses ,
            'majors' => $majors ,
            'supervisors' => $supervisors
        ]);
    }
    public function FilterSemesterStudents(Request $request)
    {
        // To get all users with filtering
        $users = User::where('name', 'like', '%' . $request->user_name . '%')
        ->where('u_role_id' , 2)
        ->pluck('u_id')
        ->toArray();
        if($request->user_gender != null && $request->user_major != null) {
            $users = User::where('name', 'like', '%' . $request->user_name . '%')
            ->where('u_gender', $request->user_gender)
            ->where('u_major_id' , $request->user_major)
            ->where('u_role_id' , 2)
            ->pluck('u_id')
            ->toArray();
        }
        else if($request->user_gender != null) {
            $users = User::where('name', 'like', '%' . $request->user_name . '%')
            ->where('u_gender', $request->user_gender)
            ->where('u_role_id' , 2)
            ->pluck('u_id')
            ->toArray();
        }
        else if($request->user_major != null) {
            $users = User::where('name', 'like', '%' . $request->user_name . '%')
            ->where('u_major_id' , $request->user_major)
            ->where('u_role_id' , 2)
            ->pluck('u_id')
            ->toArray();
        }
        // To get all users on this semester
        $systemSettings = SystemSetting::first();
        $semester = $systemSettings->ss_semester_type;
        $year = $systemSettings->ss_year;
        $data = Registration::with('users','courses')
        ->where('r_year', $year)
        ->where('r_semester', $semester)
        ->whereIn('r_student_id' , $users)
        ->distinct()
        ->get();
        if($request->user_course != null) {
            $data = Registration::with('users','courses')
            ->where('r_year', $year)
            ->where('r_semester', $semester)
            ->whereIn('r_student_id' , $users)
            ->where('r_course_id', $request->user_course)
            ->select('r_student_id')
            ->distinct()
            ->get();
        }

        $supervisors = User::where('u_role_id',10)->get();
        $html = view('project.admin.registration.includes.semesterStudentsList' , ['data' => $data , 'supervisors' => $supervisors])->render();
        return response()->json(['html' => $html]);
    }

    public function add_training_supervisor(Request $request)
    {
        $data = Registration::where('r_student_id' , $request->student)->first();
        $data->supervisor_id = $request->supervisor;
        if($data->save()){
            return response()->json([
                'success'=>'true',
            ]);
        }
    }
}
