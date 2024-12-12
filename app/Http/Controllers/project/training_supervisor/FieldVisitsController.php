<?php

namespace App\Http\Controllers\project\training_supervisor;

use App\Http\Controllers\Controller;
use App\Models\Company;
use App\Models\FieldVisitsModel;
use App\Models\Registration;
use App\Models\StudentCompany;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;

class FieldVisitsController extends Controller
{
    public function index()
    {
        $data = FieldVisitsModel::orderBy('fv_id','desc')->get();
        foreach ($data as $key){
            $studentIds = json_decode($key->fv_student_id, true);
            $students = User::whereIn('u_id', $studentIds)->pluck('name')->toArray();
            $key->student_names = $students;
        }
        return view('project.training_supervisor.field_visits.index',['data'=>$data]);
    }

    public function add()
    {
        $students = User::where('u_role_id',2)->get();
        $company = Company::whereIn('c_id',function ($query){
            $query->select('sc_company_id')->from('students_companies')->whereIn('sc_registration_id',function ($query){
                $query->select('r_id')->from('registration')->where('supervisor_id',auth()->user()->u_id)
                    ->whereIn('r_id',function ($query){
                        $query->select('sc_registration_id')->from('students_companies');
                    });
            });
        })->get();
        $registration = Registration::with('users')->get();
        return view('project.training_supervisor.field_visits.add',['students'=>$students , 'company'=>$company , 'registration'=>$registration]);
    }

    public function create(Request $request)
    {
        $data = new FieldVisitsModel();
        $data->fv_student_id = json_encode($request->student);
        $data->fv_supervisor_id = auth()->user()->u_id;
        $data->fv_company_id = $request->fv_company_id;
        $data->fv_visiting_place = $request->fv_visiting_place;
        $data->fv_visit_duration = $request->fv_visit_duration;
        $data->fv_vistit_time = Carbon::now();
        $data->fv_notes = $request->fv_notes;
        $data->fv_date_by_user = $request->fv_date_by_user;
        if ($data->save()){
            return redirect()->route('training_supervisor.field_visits.index')->with(['success'=>'تم انشاء الزيارة بنجاح']);
        }
    }

    public function get_student_from_company(Request $request)
    {
        $data = StudentCompany::with('users')->where('sc_company_id',$request->company_id)->whereIn('sc_registration_id',function ($query) use ($request){
            $query->select('r_id')->from('registration')->where('supervisor_id',auth()->user()->u_id)->where('sc_status',1);
        })->get();
        return response()->json([
            'success' => true,
            'status' => ($data->isEmpty()) ? 'empty' : 'not_empty',
            'data' => $data
        ]);
    }

    public function details($id)
    {
        $data = FieldVisitsModel::where('fv_id',$id)->first();
            $studentIds = json_decode($data->fv_student_id, true);
            $students = User::whereIn('u_id', $studentIds)->pluck('name')->toArray();
        $data->student_names = $students;
        return view('project.training_supervisor.field_visits.details',['data'=>$data]);
    }
}
