<?php

namespace App\Http\Controllers\project\students;

use App\Http\Controllers\Controller;
use App\Models\FinalReportsSubmissionsModel;
use App\Models\Registration;
use App\Models\SystemSetting;
use Illuminate\Http\Request;

class FinalReportController extends Controller
{
    public function index(){
        $registration = Registration::where('r_student_id',auth()->user()->u_id)->where('r_semester',SystemSetting::first()->ss_semester_type)->where('r_year',SystemSetting::first()->ss_year)->first();
        $setting = SystemSetting::first();
        $data = FinalReportsSubmissionsModel::where('frs_registration_id',$registration->r_id)->get();
        return view('project.student.final_report.index',['registration'=>$registration , 'setting'=>$setting , 'data'=>$data]);
    }

    public function create(Request $request){
        // $system_setting = SystemSetting::first();
        // $data = Registration::where('r_student_id',auth()->user()->u_id)->where('r_semester',$system_setting->ss_semester_type)->where('r_year',$system_setting->ss_year)->first();
        // if ($request->hasFile('final_file')) {
            // $file = $request->file('final_file');
            // $extension = $file->getClientOriginalExtension();
            // $filename = time() . '.' . $extension; // Unique filename
            // $file->storeAs('uploads', $filename, 'public');
            // $data->final_file = $filename;
        // }
        // if($data->save()){
        //     return redirect()->route('students.final_reports.index')->with(['success'=>'تم اضافة ال التقرير النهائي بنجاح']);
        // }
        $system_setting = SystemSetting::first();
        $data = new FinalReportsSubmissionsModel();
        $registration = Registration::where('r_student_id',auth()->user()->u_id)->where('r_semester',$system_setting->ss_semester_type)->where('r_year',$system_setting->ss_year)->first();
        if ($request->hasFile('final_file')) {
            $file = $request->file('final_file');
            $extension = $file->getClientOriginalExtension();
            $data->frs_real_name = $file->getClientOriginalName();
            $filename = time() . '.' . $extension; // Unique filename
            $file->storeAs('final_reports', $filename, 'public');
            $data->frs_name = $filename;
        }
        $data->frs_notes = $request->frs_notes;
        $data->frs_registration_id = $registration->r_id;
        if($data->save()){
            return redirect()->route('students.final_reports.index')->with(['success'=>'تم اضافة ال التقرير النهائي بنجاح']);
        }
    }

    public function delete($id){
        $data = FinalReportsSubmissionsModel::where('id',$id)->first();
        if ($data->delete()){
            return redirect()->route('students.final_reports.index')->with(['success'=>'تم حذف ال التقرير النهائي بنجاح']);
        }
    }
}
