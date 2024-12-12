<?php

namespace App\Http\Controllers\project\admin;

use App\Exports\EvaluationsExport;
use App\Http\Controllers\Controller;
use App\Models\Company;
use App\Models\CriteriaModel;
use App\Models\EvaluationCriteriaModel;
use App\Models\EvaluationsModel;
use App\Models\EvaluationTypesModel;
use App\Models\Registration;
use App\Models\Role;
use App\Models\SemesterCourse;
use App\Models\SystemSetting;
use App\Models\User;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class EvaluationsController extends Controller
{
    public function index()
    {
        $data = EvaluationsModel::with('evaluation_type')->orderBy('e_id','desc')->get();
        return view('project.admin.evaluations.index',['data'=>$data]);
    }

    public function add()
    {
        $evaluation_type = EvaluationTypesModel::get();
        $roles = Role::get();
        return view('project.admin.evaluations.add',['evaluation_type'=>$evaluation_type , 'roles'=>$roles]);
    }

    public function create(Request $request)
    {
        $data = new EvaluationsModel();
        $data->e_type_id = $request->e_type_id;
        $data->e_title = $request->e_title;
        $data->e_status = 1;
        $data->e_evaluator_role_id = EvaluationTypesModel::where('et_id',$request->e_type_id)->first()->et_evaluator_role_id;
        $data->e_start_time = $request->e_start_time;
        $data->e_end_time = $request->e_end_time;
        if ($data->save()){
            return redirect()->route('admin.evaluations.evaluation_criteria',['id'=>$data->e_id])->with(['success' => 'تم انشاء التقييم بنجاح']);
        }
    }

    public function edit($id)
    {
        $data = EvaluationsModel::find($id);
        $evaluation_type = EvaluationTypesModel::get();
        $roles = Role::get();
        $evaluation_criteria = EvaluationCriteriaModel::where('ec_evaluation_id')->first();
        return view('project.admin.evaluations.edit', ['data'=>$data , 'evaluation_type'=>$evaluation_type , 'roles'=>$roles , 'evaluation_criteria'=>$evaluation_criteria]);
    }

    public function update(Request $request)
    {
        $data = EvaluationsModel::where('e_id',$request->id)->first();
        $data->e_type_id = $request->e_type_id;
        $data->e_title = $request->e_title;
        $data->e_status = 1;
        $data->e_evaluator_role_id = $request->e_evaluator_role_id;
        $data->e_start_time = $request->e_start_time;
        $data->e_end_time = $request->e_end_time;
        if ($data->save()){
            return redirect()->route('admin.evaluations.index')->with(['success' => 'تم تعديل التقييم بنجاح']);
        }
    }


    public function list_criteria_ajax(Request $request)
    {
        $data = CriteriaModel::get();
        return response()->json([
            'success' => true,
            'view' => view('project.admin.evaluations.ajax.criteria_ajax',['data'=>$data])->render()
        ]);
    }

    public function list_evaluation_criteria_ajax(Request $request)
    {
        $data = EvaluationCriteriaModel::where('ec_evaluation_id',$request->evaluation_id)->get();
        return response()->json([
            'success' => true,
            'view' => view('project.admin.evaluations.ajax.criteria_evaluation_ajax',['data'=>$data])->render()
        ]);
    }

    public function add_evaluation_criteria_ajax(Request $request)
    {
        $check_if_found = EvaluationCriteriaModel::where('ec_evaluation_id',$request->evaluation_id)->where('ec_criteria_id',$request->criteria_id)->first();
        if (empty($check_if_found)){
            $data = new EvaluationCriteriaModel();
            $data->ec_evaluation_id = $request->evaluation_id;
            $data->ec_criteria_id = $request->criteria_id;
            if ($data->save()){
                return response()->json([
                    'success' => true,
                    'message' => 'تم انشاء المعيار بنجاح'
                ]);
            }
        }
    }

    public function delete_evaluation_criteria_ajax(Request $request)
    {
        $data = EvaluationCriteriaModel::with('criteria')->where('ec_id',$request->ec_id)->first();
        if ($data->delete()){
            return response()->json([
                'success' => true,
                'message' => 'تم حذف البيانات بنجاح'
            ]);
        }
    }

    public function evaluation_criteria($id)
    {
        $data = EvaluationCriteriaModel::where('ec_evaluation_id',$id)->get();
        return view('project.admin.evaluations.evaluation_criteria',['data'=>$data , 'id'=>$id]);
    }

    public function details($evaluation_id){
        $system_settings = SystemSetting::first();
        $data = EvaluationsModel::where('e_id',$evaluation_id)->first();
        $semesters = SemesterCourse::where('sc_semester',$system_settings->ss_semester_type)->where('sc_year',$system_settings->ss_year)->get();
        $supervisors = User::where('u_role_id',10)->get();
        $companies = Company::get();
        return view('project.admin.evaluations.details',['data'=>$data , 'semesters'=>$semesters , 'supervisors'=>$supervisors , 'companies'=>$companies]);
    }

    public function list_evaluation_details_list(Request $request){
        $data = Registration::query();
        $data = $data->with('users');
        // $data = $data->whereIn('r_id',function($query) use ($request){
        //     $query->select('es_registration_id')->from('evaluation_submissions');
        // });
        
        $data = $data->whereIn('r_student_id',function($query) use ($request){
            $query->select('u_id')->from('users')->where('name','like','%'.$request->student_name.'%');
        });
        if($request->filled('course_id')){
            $data = $data->where('r_course_id',$request->course_id);
        }
        if($request->filled('supervisor_id')){
            $data = $data->where('supervisor_id',$request->supervisor_id);
        }
        if($request->filled('company_id')){
            $data = $data->whereIn('r_id',function($query) use ($request){
                $query->select('sc_registration_id')->from('students_companies')->whereIn('sc_company_id',function($query) use ($request){
                    $query->select('c_id')->from('companies')->where('c_id',$request->company_id);
                });
            });
        }
        // if($request->filled('selectedRadio')){
        //     $data = $data->whereIn('r_id',function($query) use ($request){
        //         $query->select('es_registration_id')->from('evaluation_submissions')->whereIn('es_evaluation_id',function($query) use ($request){
        //             $query->select('e_id')->from('evaluations')->where('e_evaluator_role_id',$request->selectedRadio);
        //         });
        //     });
        // }
        if($request->filled('selectedRadio')){
            if($request->selectedRadio == 'company'){
                $data = $data->where('company_score',null);
            }
            elseif($request->selectedRadio == 'university'){
                $data = $data->where('university_score',null);
            }
        }
        $data = $data->get();
        return response()->json([
            'success' => true ,
            'view' => view('project.admin.evaluations.ajax.evaluation_deatils_table',['data'=>$data])->render()
        ]);
    }

    public function edit_total_score(Request $request){
        $data = Registration::where('r_id',$request->r_id)->first();
        $data->total_score = $request->total_score;
        if($data->save()){
            return response()->json([
                'success' => true,
                'message' => 'تم تعديل النتيجة بنجاح'
            ]);
        }
    }
}
