<?php

namespace App\Http\Controllers\project\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Course;

class CoursesController extends Controller
{

    public function index(){
        $numOfDataPerPage = 10;
        $data = Course::orderBy('c_id', 'desc')->paginate($numOfDataPerPage);
        return view('project.admin.courses.index',['data'=>$data,'total'=>$data->total(), 'per_page'=>$numOfDataPerPage,'last_page'=>$data->lastPage()]);
    }

    public function create(Request $request){
        $data = new Course;
        $data->c_name = $request->c_name;
        $data->c_course_code = $request->c_course_code;
        $data->c_hours = $request->c_hours;
        $data->c_description = $request->c_description;
        $data->c_course_type =$request->c_course_type;
        $data->c_reference_code	 =$request->c_reference_code;

        if ($data->save()) {
            //$data = Course::get();
            $numOfDataPerPage = 10;
            $data = Course::orderBy('c_id', 'desc')->paginate($numOfDataPerPage);
            return response()->json([
                'success'=>'true',
                'view'=>view('project.admin.courses.ajax.courseList',['data'=>$data,'total'=>$data->total(), 'per_page'=>$numOfDataPerPage,'last_page'=>$data->lastPage()])->render()
            ]);
        }
    }

    public function update(Request $request){
        $data = Course::where('c_id',$request->c_id)->first();
        $data->c_name = $request->c_name;
        $data->c_course_code = $request->c_course_code;
        $data->c_course_type = $request->c_course_type;
        $data->c_description = $request->c_description;
        $data->c_hours = $request->c_hours;
        $data->c_reference_code = $request->c_reference_code;
        $data->c_name = $request->c_name;



        if ($data->save()) {

            $course = Course::where('c_id',$request->c_id)->first();
            // $data = Course::get();
            $numOfDataPerPage = 10;
            $data = Course::paginate($numOfDataPerPage);
            return response()->json([
                'success'=>'true',
                'data'=>$course,
                'view'=>view('project.admin.courses.ajax.courseList',['data'=>$data])->render()
            ]);
        }
    }

    public function courseSearch(Request $request){

        $numOfDataPerPage = 10;

        if($request->search == ""){
            $data = Course::orderBy('c_id', 'desc')->paginate($numOfDataPerPage);
        }else{
            $data = Course::where('c_name','like','%'.$request->search.'%')->get();
        }

        return response()->json([
            'success'=>'true',
            'view'=>view('project.admin.courses.ajax.courseList',['data'=>$data])->render()
        ]);

    }

    public function checkCourseCode(Request $request){



        $opp = $request->opp;

        if($opp == 'code'){
            $data = Course::where('c_course_code',$request->search)->first();
            if($data!=null){

                return response()->json([
                    'success'=>'true',
                    'data'=>$data
                ]);

            }else{
                return response()->json([
                    'success'=>'true',
                    'data'=>$data
                ]);
            }
        }else{
            $data = Course::where('c_reference_code',$request->search)->first();
            if($data!=null){

                return response()->json([
                    'success'=>'true',
                    'data'=>$data
                ]);

            }else{
                return response()->json([
                    'success'=>'true',
                    'data'=>$data
                ]);
            }
        }


    }

    public function getCourses(Request $request)
    {
        $results = Course::orderBy('c_id', 'desc')->paginate(10);

        return response()->json([
                'success'=>'true',
                'data'=>$results,
                'current_page'=>$results->currentPage(),
        ]);

    }

}
