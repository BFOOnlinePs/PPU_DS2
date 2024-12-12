<?php

namespace App\Http\Controllers\project\students;

use App\Exports\EvaluationsExport;
use App\Http\Controllers\Controller;
use App\Models\Company;
use App\Models\Course;
use App\Models\CriteriaModel;
use App\Models\EvaluationsModel;
use App\Models\EvaluationSubmissionScoresModel;
use App\Models\EvaluationSubmissionsModel;
use App\Models\Registration;
use App\Models\StudentCompany;
use http\Client\Curl\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;

class EvaluationController extends Controller
{
    public function index()
    {
        $data = EvaluationsModel::query();
        if (auth()->user()->u_role_id == 6){
            $data->where('e_status',1)->where('e_evaluator_role_id',6)->with('evaluation_type');
        }
        else if (auth()->user()->u_role_id == 2)
        {
            $data->where('e_status',1)->where('e_evaluator_role_id',2)->with('evaluation_type');
        }
        else if (auth()->user()->u_role_id == 10)
        {
            $data->where('e_status',1)->where('e_evaluator_role_id',10)->with('evaluation_type');
        }
        $data = $data->get();
        return view('project.student.evaluation.index',['data'=>$data]);
    }

    public function details($id)
    {
        $check_if_find = EvaluationsModel::find($id);
        if (!$check_if_find){
            abort(404 , 'البيانات غير متوفرة');
        }
        else{
            $criteria = CriteriaModel::get();
            $data = \App\Models\User::query();
//        $data = Registration::query();
            if (auth()->user()->u_role_id == 2){
                $data->whereIn('u_id', function ($query) {
                    $query->select('c_manager_id')
                        ->from('companies')
                        ->whereIn('c_id', function ($query) {
                            $query->select('sc_company_id')
                                ->from('students_companies')->where('sc_student_id',auth()->user()->u_id)->where('sc_registration_id',Registration::where('r_student_id',auth()->user()->u_id)->first()->r_id);
//                                ->whereIn('sc_registration_id', function ($query) {
//                                    $query->select('r_id')
//                                        ->from('registration')
//                                        ->where('supervisor_id', Registration::where('r_student_id',auth()->user()->u_id)->first()->supervisor_id) ?? null;
//                                });
                        });
                });
            }
            if (auth()->user()->u_role_id == 6){
                $data->whereIn('u_id', function ($query) {
                    $query->select('sc_student_id')->from('students_companies')->where('sc_company_id',Company::where('c_manager_id',auth()->user()->u_id)->first()->c_id);
                });
            }
            if (auth()->user()->u_role_id == 10){
                $data->whereIn('u_id',function ($query) {
                    $query->select('r_student_id')->from('registration')->where('supervisor_id',auth()->user()->u_id);
                });
//            $data->where('supervisor_id',auth()->user()->u_id);
            }
//        if (auth()->user()->u_role_id == 6){
//            $data->whereIn('r_id',function ($query){
//                $query->select('sc_registration_id')->from('students_companies');
//            });
//        }
            $data = $data->with('registrations')->get();
            foreach ($data as $index => $key) {
                if (auth()->user()->u_role_id == 2 || auth()->user()->u_role_id == 6){
                    $key->submission_status = EvaluationSubmissionsModel::where('es_evaluatee_id',$key->u_id)->where('es_evaluator_id',auth()->user()->u_id)->where('es_evaluation_id',$id)->first();
                    // $key->total_evaluation = EvaluationSubmissionScoresModel::whereIn('ss_submission_id',function($query) use ($key){
                    //     $query->select('es_id')->from('evaluation_submissions')->where('es_registration_id',$key->registrations[0]->r_id)->where('es_evaluatee_id',$key->registrations[0]->r_student_id);
                    // })->sum('ss_score');
                }
                else{
                    $key->submission_status = EvaluationSubmissionsModel::where('es_registration_id',Registration::where('r_student_id',$key->u_id)->first()->r_id)->where('es_evaluation_id',$id)->where('es_evaluator_id',auth()->user()->u_id)->first();
                }
            }
            return view('project.student.evaluation.details',['criteria'=>$criteria,'id'=>$id , 'data' => $data]);
        }
    }

    public function evaluation_submission_page($registration_id , $evaluation_id)
    {
        $registration = null;
        $check_evaluation = EvaluationsModel::find($evaluation_id);
        if (!$check_evaluation){
            abort(404 , 'البيانات غير متوفرة');
        }

        if (auth()->user()->u_role_id == 2){
            if (!$check_evaluation){
                abort(404 , 'البيانات غير متوفرة');
            }
            $registration = $registration_id;
        }
        elseif(auth()->user()->u_role_id == 6){
            if (!$check_evaluation){
                abort(404 , 'البيانات غير متوفرة');
            }
            $registration = Registration::where('r_student_id',$registration_id)->first();
        }
        else{
            $check_registration = Registration::where('r_id',$registration_id)->first();
            if (!$check_registration){
                abort(404 , 'البيانات غير متوفرة');
            }
        $registration = Registration::where('r_id',$registration_id)->first();
        }
        $evaluation = EvaluationsModel::where('e_id',$evaluation_id)->first();
        $criteria = CriteriaModel::whereIn('c_id',function($query) use ($evaluation_id){
            $query->select('ec_criteria_id')->from('evaluation_criteria')->where('ec_evaluation_id',$evaluation_id);
        })->get();
        return view('project.student.evaluation.evaluation_submission',['registration'=>$registration , 'evaluation'=>$evaluation , 'criteria'=>$criteria]);
    }

    public function list_user($user_id)
    {
        return view('project.student.evaluation.list_user',['user_id'=>$user_id]);
    }

    public function evaluation_submission_create(Request $request)
    {
        $totalEvaluation = array_sum($request->criteria);
        $equation = ($totalEvaluation / (count($request->criteria) * 5)) * 50;
        $check_if_submission = EvaluationSubmissionsModel::query();
        if (auth()->user()->u_role_id == 2){
            $check_if_submission->where('es_evaluation_id',$request->es_evaluation_id)->where('es_evaluatee_id',$request->registration_id)->where('es_evaluator_id',auth()->user()->u_id);
        }
        if (auth()->user()->u_role_id == 6){
            $check_if_submission->where('es_evaluation_id',$request->es_evaluation_id)->where('es_evaluatee_id',auth()->user()->u_id)->where('es_evaluator_id',$request->registration_id);
        }
        if (auth()->user()->u_role_id == 10){
            $check_if_submission->where('es_evaluation_id',$request->es_evaluation_id)->where('es_evaluatee_id',auth()->user()->u_id)->where('es_evaluator_id',$request->registration_id);
        }
        $check_if_submission = $check_if_submission->first();
        if (empty($check_if_submission)){
            $criteria_score = 0;
            $data = new EvaluationSubmissionsModel();
            $data->es_evaluation_id = $request->es_evaluation_id;
            $data->es_evaluator_id = auth()->user()->u_id;
            if (auth()->user()->u_role_id == '10'){
                $data->es_evaluatee_id = Registration::where('r_id',$request->registration_id)->first()->r_student_id;
                $data->es_registration_id = $request->registration_id;
                $registration = Registration::where('r_id',$request->registration_id)->first();
                $registration->university_score = $equation;
                $registration->save();
            }
            if (auth()->user()->u_role_id == '6'){
                // $data->es_evaluatee_id = $request->registration_id;
                $data->es_evaluatee_id = Registration::where('r_id',$request->registration_id)->first()->r_student_id;
                // $data->es_registration_id = Registration::where('r_student_id',$request->registration_id)->first()->r_id;
                $data->es_registration_id = $request->registration_id;
                $registration = Registration::where('r_id',$request->registration_id)->first();
                $registration->company_score = $equation;
                $registration->save();
            }
            if (auth()->user()->u_role_id == '2'){
                $data->es_evaluatee_id = $request->registration_id;
            }
            $data->es_notes = $request->es_notes;
            if ($data->save()) {
                foreach ($request->criteria as $index => $score) {
                    $evaluation_submission_scores = new EvaluationSubmissionScoresModel();
                    $evaluation_submission_scores->ss_submission_id = $data->es_id;
                    $evaluation_submission_scores->ss_criteria_id = $request->criteria[$index];
                    $evaluation_submission_scores->ss_score = $score;
                    $criteria_score = ($criteria_score + $score);
                    $evaluation_submission_scores->save();
                }
                $data->es_final_score = $criteria_score / count($request->criteria);

                $data->save();

                if(auth()->user()->user_role == 6){
                    $registration = Registration::where('r_id',$request->registration_id)->first();
                    $registration->company_score = $equation;
                    $registration->company_score_added_by_user_id = auth()->user()->u_id;
                    $registration->save();    
                }

                if(auth()->user()->user_role == 10){
                    $registration = Registration::where('r_id',$request->registration_id)->first();
                    $registration->university_score = $equation;
                    $registration->university_score_added_by_user_id = auth()->user()->u_id;
                    $registration->save();    
                }

                return redirect()->route('students.evaluation.details', ['evaluation_id' => $request->es_evaluation_id])->with(['success' => 'تم التقييم بنجاح']);
            }
            return back()->withErrors(['error' => 'Failed to save the evaluation. Please try again.']);
        }
        else{
            return 'تم التقييم مسبقا';
        }
    }

    public function update_status(Request $request){
        $data = EvaluationsModel::where('e_id',$request->id)->first();
        $data->e_status = $request->status;
        if ($data->save()){
            return response()->json([
                'success' => 'true',
            ]);
        }
    }

    public function export_excel(Request $request){
        return Excel::download(new EvaluationsExport($request), 'evaluation.xlsx');
    }
}
