<?php

namespace App\Http\Controllers\project\admin;

use App\Http\Controllers\Controller;
use App\Models\Registration;
use Illuminate\Http\Request;
use App\Models\SemesterCourse;
use App\Models\Course;
use App\Models\SystemSetting;

class SemesterCoursesController extends Controller
{
    public function index()
    {
        $systemSettings = SystemSetting::first();

        $semester = $systemSettings->ss_semester_type;
        $year = $systemSettings->ss_year;

        $years = SemesterCourse::distinct()->pluck('sc_year')->toArray();

        $data = SemesterCourse::with('courses')->where('sc_semester',$systemSettings->ss_semester_type)->where('sc_year',$systemSettings->ss_year)->get();

        $course = Course::whereNotIn('c_id', function ($query) use ($systemSettings) {
            $query->select('sc_course_id')->from('semester_courses')->where('sc_semester',$systemSettings->ss_semester_type)->where('sc_year',$systemSettings->ss_year);
        })->get();

        return view('project.admin.semesterCourses.index',['data'=>$data,'course'=>$course,'semester'=>$semester, 'year'=>$year,'years'=>$years]);
    }

    public function create(Request $request){
        $systemSettings = SystemSetting::first();

        $courses = $request->coursesList;
        $data = new SemesterCourse;
        $semester = $systemSettings->ss_semester_type;
        $year = $systemSettings->ss_year;
        for($i = 0;$i<count($courses);$i++)
        {
            $data = new SemesterCourse;
            $data->sc_course_id=$courses[$i];
            $data->sc_year=$year;
            $data->sc_semester=$semester;
            $data->save();
        }
        $data = SemesterCourse::with('courses')->where('sc_semester',$systemSettings->ss_semester_type)->where('sc_year',$systemSettings->ss_year)->get();

        $course = Course::whereNotIn('c_id', function ($query) use ($systemSettings) {
            $query->select('sc_course_id')->from('semester_courses')->where('sc_semester',$systemSettings->ss_semester_type)->where('sc_year',$systemSettings->ss_year);
        })->get();

        return response()->json([
            'success'=>'true',
            'view'=>view('project.admin.semesterCourses.ajax.semesterCoursesList',['data'=>$data,'semester'=>$systemSettings->ss_semester_type, 'year'=>$systemSettings->ss_year])->render(),
            'modal'=>view('project.admin.semesterCourses.ajax.select',['course'=>$course])->render(),
        ]);
    }


    public function delete(Request $request){
        $systemSettings = SystemSetting::first();

        $data = SemesterCourse::where('sc_id',$request->semesterCourse)->first();
        if($data->delete()) {
            $data = SemesterCourse::with('courses')->where('sc_semester',$systemSettings->ss_semester_type)->where('sc_year',$systemSettings->ss_year)->get();

            $course = Course::whereNotIn('c_id', function ($query) use ($systemSettings) {
                $query->select('sc_course_id')->from('semester_courses')->where('sc_semester',$systemSettings->ss_semester_type)->where('sc_year',$systemSettings->ss_year);
            })->get();
            ;
            return response()->json([
                'success'=>'true',
                'view'=>view('project.admin.semesterCourses.ajax.semesterCoursesList',['data'=>$data,'semester'=>$systemSettings->ss_semester_type, 'year'=>$systemSettings->ss_year])->render(),
                'modal'=>view('project.admin.semesterCourses.ajax.select',['course'=>$course])->render(),
            ]);
        }

    }

    public function search(Request $request){

        $systemSettings = SystemSetting::first();

        $year = $request->year;
        $semester = $request->semester;


        if($year == 0 && $semester == 0){//here all years and all semesters
            $data = SemesterCourse::with('courses')->get();
        }else if($year != 0 && $semester == 0){//here all semesters but specific year
            $data = SemesterCourse::with('courses')->where('sc_year', $year)->get();
        }else if($year == 0 && $semester != 0){//here all years but specific semester
            $data = SemesterCourse::with('courses')->where('sc_semester', $semester)->get();
        }else if($year != 0 && $semester != 0){//here specific year and specific year
            $data = SemesterCourse::with('courses')
            ->where('sc_semester', $request->semester)
            ->where('sc_year', $request->year)->get();
        }


        $course = Course::whereNotIn('c_id', function ($query) use ($systemSettings) {
            $query->select('sc_course_id')->from('semester_courses')->where('sc_semester',$systemSettings->ss_semester_type)->where('sc_year',$systemSettings->ss_year);
        })->get();

        return response()->json([
            'success'=>'true',
            'view'=>view('project.admin.semesterCourses.ajax.semesterCoursesList',['data'=>$data,'semester'=>$systemSettings->ss_semester_type, 'year'=>$systemSettings->ss_year])->render(),
            'modal'=>view('project.admin.semesterCourses.ajax.select',['course'=>$course])->render()
        ]);


    }


    public function courseNameSearch(Request $request){

        $systemSettings = SystemSetting::first();

        $year = $request->year;
        $semester = $request->semester;

        if($year == 0 && $semester == 0){//here all years and all semesters
            $data = SemesterCourse::with('courses')->whereHas('courses',function($query) use ($request){
                $query->where('c_name','like','%'.$request->search.'%');
            })->get();
        }else if($year != 0 && $semester == 0){//here all semesters but specific year
            $data = SemesterCourse::with('courses')->whereHas('courses',function($query) use ($request){
                $query->where('c_name','like','%'.$request->search.'%');
            })
            ->where('sc_year', $request->year)
            ->get();
        }else if($year == 0 && $semester != 0){//here all years but specific semester
            $data = SemesterCourse::with('courses')->whereHas('courses',function($query) use ($request){
                $query->where('c_name','like','%'.$request->search.'%');
            })
            ->where('sc_semester', $request->semester)
            ->get();
        }else if($year != 0 && $semester != 0){//here specific year and specific year
            $data = SemesterCourse::with('courses')->whereHas('courses',function($query) use ($request){
                $query->where('c_name','like','%'.$request->search.'%');
            })
            ->where('sc_semester', $request->semester)
            ->where('sc_year', $request->year)
            ->get();
        }

        $course = Course::whereNotIn('c_id', function ($query) use ($systemSettings) {
            $query->select('sc_id')->from('semester_courses')->where('sc_semester',$systemSettings->ss_semester_type)->where('sc_year',$systemSettings->ss_year);
        })->get();

        return response()->json([
            'success'=>'true',
            'view'=>view('project.admin.semesterCourses.ajax.semesterCoursesList',['data'=>$data,'semester'=>$systemSettings->ss_semester_type, 'year'=>$systemSettings->ss_year])->render(),
            'modal'=>view('project.admin.semesterCourses.ajax.select',['course'=>$course])->render(),
        ]);
    }
}
