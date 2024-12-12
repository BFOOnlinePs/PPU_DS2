<?php

namespace App\Http\Controllers\project\admin;

use App\Http\Controllers\Controller;
use App\Models\Company;
use App\Models\FieldVisitsModel;
use App\Models\User;
use Illuminate\Http\Request;

class FieldVisitsController extends Controller
{
    public function index()
    {
        $company = Company::get();
        $supervisor = User::where('u_role_id',10)->get();
        return view('project.admin.field_visits.index',['company'=>$company , 'supervisor'=>$supervisor]);
    }

    public function list_field_visits(Request $request)
    {
        $data = FieldVisitsModel::query();
        if ($request->filled('company_id')){
            $data->where('fv_company_id',$request->company_id);
        }
        if ($request->filled('supervisor_id')){
            $data->where('fv_supervisor_id',$request->supervisor_id);
        }
        $data = $data->with('company')->orderBy('fv_id','desc')->get();
        foreach ($data as $key){
            $studentIds = json_decode($key->fv_student_id, true);
            $students = User::whereIn('u_id', $studentIds)->pluck('name')->toArray();
            $key->student_names = $students;
        }
        return response()->json([
            'success' => true,
            'view' => view('project.admin.field_visits.ajax.list_field_visits',['data'=>$data])->render()
        ]);
    }

    public function details($id)
    {
        $data = FieldVisitsModel::where('fv_id',$id)->get();
        return view('project.admin.field_visits.details',['data'=>$data]);
    }
}
