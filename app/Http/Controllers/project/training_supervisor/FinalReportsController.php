<?php

namespace App\Http\Controllers\project\training_supervisor;

use App\Http\Controllers\Controller;
use App\Models\Registration;
use Illuminate\Http\Request;

class FinalReportsController extends Controller
{
    public function index(){
        return view('project.training_supervisor.final_reports.index');
    }

    public function final_reports_list_ajax(Request $request){
        $data = Registration::query();
        if ($request->filled('student_name')){
            $studentName = $request->input('student_name');
            $data->whereIn('r_student_id',function ($query) use ($studentName){
                $query->select('u_id')->from('users')->where('name','like','%'.$studentName.'%')->where('supervisor_id',auth()->user()->u_id);
            })->get();
        }
        $data = $data->where('supervisor_id',auth()->user()->u_id)->get();
        return response()->json([
            'success' => true,
            'view' => view('project.training_supervisor.final_reports.ajax.final_reports_list',['data'=>$data])->render()
        ]);
    }
}