<?php

namespace App\Http\Controllers\apisControllers\sharedFunctions\evaluation;

use App\Http\Controllers\Controller;
use App\Models\Company;
use App\Models\CompanyBranch;
use App\Models\CriteriaModel;
use App\Models\EvaluationCriteriaModel;
use App\Models\EvaluationsModel;
use App\Models\EvaluationSubmissionScoresModel;
use App\Models\EvaluationSubmissionsModel;
use App\Models\EvaluationTypesModel;
use App\Models\StudentCompany;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class EvaluationController extends Controller
{
    public function EvaluationsTitles()
    {
        $current_user =  Auth::user();
        $user_role_id = $current_user->u_role_id;

        $evaluation_type_id = EvaluationTypesModel::where('et_evaluator_role_id', $user_role_id)->pluck('et_id');

        $evaluations = EvaluationsModel::whereIn('e_type_id', $evaluation_type_id)
            ->where('e_status', 1)
            ->where(function ($query) {
                $query->where('e_start_time', '<=', Carbon::now())
                    ->orWhereNull('e_start_time');
            })
            ->where(function ($query) {
                $query->where('e_end_time', '>=', Carbon::now())
                    ->orWhereNull('e_end_time');
            })
            ->get();

        return response()->json([
            'status' => true,
            'evaluations' => $evaluations
        ]);
    }

    public function usersToEvaluate(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'evaluation_type_id' => 'required',
            'evaluation_id' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => $validator->errors()->first(),
            ]);
        };

        $evaluation_id = $request->input('evaluation_id');

        $user = Auth::user();
        $user_id = $user->u_id;

        // manager
        if ($user->u_role_id == 6 && $request->input('evaluation_type_id') == 2) {
            // for manager get the students that he manages
            $manager_id = $user->u_id;

            $company_branches_id = CompanyBranch::where('b_manager_id', $manager_id)->pluck('b_id');

            $trainees = User::join('students_companies', 'users.u_id', '=', 'students_companies.sc_student_id')
                ->whereIn('students_companies.sc_branch_id', $company_branches_id)
                ->where('students_companies.sc_status', 1) // active
                ->select(
                    'users.u_id',
                    'users.name',
                    'users.email',
                    'students_companies.sc_registration_id as registration_id',
                    DB::raw(
                        "(EXISTS (
                        SELECT 1
                        FROM evaluation_submissions
                        WHERE evaluation_submissions.es_evaluation_id = {$evaluation_id}
                          AND evaluation_submissions.es_evaluatee_id = students_companies.sc_student_id
                          AND evaluation_submissions.es_evaluator_id = {$user_id}
                         )
                        ) AS isEvaluated"
                    )
                )
                ->get();

            // Convert isEvaluated to boolean
            $trainees->transform(function ($trainee) {
                $trainee->isEvaluated = (bool) $trainee->isEvaluated;
                return $trainee;
            });

            return response()->json([
                'status' => true,
                'trainees' => $trainees,
            ]);
        } else if ($user->u_role_id == 2 && $request->input('evaluation_type_id') == 1) {
            // for student get the companies that he is in
            $companies = User::join('students_companies', 'users.u_id', '=', 'students_companies.sc_student_id')
                ->join('companies', 'students_companies.sc_company_id', '=', 'companies.c_id')
                ->join('users as managers', 'companies.c_manager_id', '=', 'managers.u_id')
                ->where('students_companies.sc_student_id', $user->u_id)
                ->where('students_companies.sc_status', 1) // active
                // ->select('users.u_id', 'users.name', 'students_companies.sc_registration_id')
                ->select(
                    'companies.c_id',
                    'managers.name as manager_name',
                    'companies.c_name',
                    'companies.c_english_name',
                    'students_companies.sc_registration_id',
                    DB::raw(
                        "
                        EXISTS (
                            SELECT 1
                            FROM evaluation_submissions
                            WHERE evaluation_submissions.es_evaluation_id = {$evaluation_id}
                            AND evaluation_submissions.es_evaluatee_id = companies.c_manager_id
                            AND evaluation_submissions.es_evaluator_id = {$user_id}
                        ) AS isEvaluated"
                    )
                )
                ->get();



            // Convert isEvaluated to boolean
            $companies->transform(function ($company) {
                $company->isEvaluated = (bool) $company->isEvaluated;
                return $company;
            });

            return response()->json([
                'status' => true,
                'companies' => $companies,
            ]);
        } else if ($user->u_role_id == 10) {
            $supervisor_id = $user->u_id;
            // for supervisor get the students that he supervises

            $students = User::join('students_companies', 'users.u_id', '=', 'students_companies.sc_student_id')
                ->join('registration', 'students_companies.sc_registration_id', '=', 'registration.r_id')
                ->join('companies', 'students_companies.sc_company_id', '=', 'companies.c_id')
                ->where('registration.supervisor_id', $supervisor_id)
                ->where('students_companies.sc_status', 1) // active
                ->select(
                    'users.u_id', // student
                    'users.name',
                    'registration.r_id',
                    'companies.c_id',
                    'companies.c_name as company_name',
                    'companies.c_english_name as company_english_name',
                    DB::raw(
                        "
                        EXISTS (
                            SELECT 1
                            FROM evaluation_submissions
                            WHERE evaluation_submissions.es_evaluation_id = {$evaluation_id}
                            AND evaluation_submissions.es_evaluatee_id = registration.r_student_id
                            AND evaluation_submissions.es_evaluator_id = {$user_id}
                        ) AS isEvaluated"
                    )
                )
                // company
                ->get();

            if ($request->input('evaluation_type_id') == 3) {
                // Convert isEvaluated to boolean
                $students->transform(function ($student) {
                    $student->isEvaluated = (bool) $student->isEvaluated;
                    return $student;
                });

                return response()->json([
                    'status' => true,
                    'students' => $students,
                ]);
            }

            // and get the companies his students in
            if ($request->input('evaluation_type_id') == 4) {
                $companies = Company::whereIn('c_id', $students->pluck('c_id'))
                    // get the company manager name from users table
                    ->join('users', 'companies.c_manager_id', '=', 'users.u_id')

                    ->select(
                        'c_id',
                        'c_name',
                        'c_english_name',
                        'c_manager_id',
                        'users.name as manager_name',

                        DB::raw(
                            "
                        EXISTS (
                            SELECT 1
                            FROM evaluation_submissions
                            WHERE evaluation_submissions.es_evaluation_id = {$evaluation_id}
                            AND evaluation_submissions.es_evaluatee_id = c_manager_id
                            AND evaluation_submissions.es_evaluator_id = {$user_id}
                        ) AS isEvaluated"
                        )
                    )
                    ->get();

                $companies->transform(function ($company) {
                    $company->isEvaluated = (bool) $company->isEvaluated;
                    return $company;
                });

                return response()->json([
                    'status' => true,
                    'companies' => $companies,
                ]);
            }
        }

        return response()->json([
            'status' => false,
            'messages' => trans('messages.no_data_something_wrong'),
        ]);
    }

    public function submitEvaluation(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'evaluation_id' => 'required',
            // 'evaluator_id' => 'required',
            'evaluatee_id' => 'required',
            'registration_id' => 'nullable',
            'notes' => 'nullable',
            // final score + max score
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => $validator->errors()->first(),
            ]);
        };

        $evaluator_id = Auth::user()->u_id;

        $evaluation_submission = new EvaluationSubmissionsModel();

        $evaluation_submission->es_evaluation_id = $request->input('evaluation_id');
        $evaluation_submission->es_evaluator_id = $evaluator_id;
        $evaluation_submission->es_evaluatee_id = $request->input('evaluatee_id');
        $evaluation_submission->es_registration_id = $request->input('registration_id');
        $evaluation_submission->es_notes = $request->input('notes');


        if ($evaluation_submission->save()) {
            $submission_id = $evaluation_submission->es_id;

            $submission_scores = $request->input('submission_scores');
            $final_score = 0;
            $max_score = 0;
            foreach ($submission_scores as $submission_score) {
                $evaluation_submission_scores = new EvaluationSubmissionScoresModel();

                $evaluation_submission_scores->ss_submission_id = $submission_id;
                $evaluation_submission_scores->ss_criteria_id = $submission_score['submission_criteria_id'];
                $evaluation_submission_scores->ss_score = $submission_score['submission_score_id'];
                $evaluation_submission_scores->save();

                $final_score += $submission_score['submission_score_id'];
                $max_score += CriteriaModel::where('c_id', $submission_score['submission_criteria_id'])->first()->c_max_score;
            }

            $evaluation_submission->es_final_score = $final_score;
            $evaluation_submission->es_max_score = $max_score;
            $evaluation_submission->save();

            return response()->json([
                'status' => true,
                'message' => trans('messages.evaluation_submitted'),
            ]);
        }
    }

    public function getEvaluationsToSubmit(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'evaluation_type_id' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => $validator->errors()->first(),
            ]);
        };

        $current_user =  Auth::user();

        $evaluation = EvaluationsModel::where('e_status', 1)
            ->where(function ($query) {
                $query->where('e_start_time', '<=', Carbon::now())
                    ->orWhereNull('e_start_time');
            })
            ->where(function ($query) {
                $query->where('e_end_time', '>=', Carbon::now())
                    ->orWhereNull('e_end_time');
            })
            ->where('e_type_id', $request->input('evaluation_type_id'))
            // ->where('e_evaluator_role_id', $user_role_id)
            ->latest()->first(); // to get the last one

        if ($evaluation == null) {
            return response()->json([
                'status' => false,
                'message' => trans('messages.no_evaluation_found'),
            ]);
        }

        $evaluation_criteria_ids = EvaluationCriteriaModel::where('ec_evaluation_id', $evaluation->e_id)
            ->pluck('ec_criteria_id');


        $criteria = CriteriaModel::whereIn('c_id', $evaluation_criteria_ids)->get();

        return response()->json([
            'status' => true,
            'evaluation' => $evaluation,
            'criteria' => $criteria,
        ]);
    }
}
