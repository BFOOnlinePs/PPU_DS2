<?php

namespace App\Http\Controllers\apisControllers\supervisors;

use App\Http\Controllers\Controller;
use App\Models\Company;
use App\Models\Major;
use App\Models\MajorSupervisor;
use App\Models\StudentCompany;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;


class SupervisorStudentsTrainingsController extends Controller
{
    // training places for all supervisor's students, with number of student in each company
    public function getSupervisorStudentsCompanies()
    {
        $supervisorId = auth()->user()->u_id;
        $supervisorMajorsIdList = MajorSupervisor::where('ms_super_id', $supervisorId)->pluck('ms_major_id');
        $studentsIdList = User::where('u_role_id', 2)->whereIn('u_major_id', $supervisorMajorsIdList)->pluck('u_id');
        // $companiesIdList = StudentCompany::whereIn('sc_student_id', $studentsIdList)->pluck('sc_company_id')->unique()->values();
        // $companies = Company::whereIn('c_id', $companiesIdList)->withCount('trainings')->paginate(8);

        // to get number of students in each company
        $companies = StudentCompany::whereIn('sc_student_id', $studentsIdList)
            ->groupBy('sc_company_id')
            ->select('sc_company_id', DB::raw('count(distinct sc_student_id) as student_count'))
            ->paginate(10);

        // to get the whole Company Model instead of only the id
        $companies->getCollection()->Transform(function($company){
            $company_model = Company::where('c_id', $company->sc_company_id)->first();
            $company_model->student_count = $company->student_count;
            return $company_model;
        });

        if ($companies->isEmpty()) {
            return response()->json([
                'status' => false,
                'message' => trans('messages.no_companies_yet'),
            ]);
        }

        return response()->json([
            'status' => true,
            'pagination' => [
                'current_page' => $companies->currentPage(),
                'last_page' => $companies->lastPage(),
                'per_page' => $companies->perPage(),
                'total_items' => $companies->total(),
            ],
            'companies' => $companies->items(),
            // 'companies' => $companies,
        ]);
    }

    public function getSupervisorStudentsInCompany(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'company_id' => 'required',
        ], [
            'company_id.required' => trans('messages.company_id_required')
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => $validator->errors()->first(),
            ], 200);
        }

        $companyId = $request->input('company_id');
        $supervisorId = auth()->user()->u_id;
        $supervisorMajorsIdList = MajorSupervisor::where('ms_super_id', $supervisorId)->pluck('ms_major_id');
        $supervisorStudentsIdList = User::where('u_role_id', 2)->whereIn('u_major_id', $supervisorMajorsIdList)->pluck('u_id');
        $studentInCompanyIdList = StudentCompany::where('sc_company_id', $companyId)->whereIn('sc_student_id', $supervisorStudentsIdList)->pluck('sc_student_id');
        $studentsInCompany = User::whereIn('u_id', $studentInCompanyIdList)->get();

        if ($studentsInCompany->isEmpty()) {
            return response()->json([
                'status' => false,
                'message' => trans('messages.no_companies_for_supervisor_yet')
            ]);
        }

        $studentsInCompany = $studentsInCompany->map(function ($student) {
            $major_name = Major::where('m_id', $student->u_major_id)->pluck('m_name')->first();
            $student['major_name'] = $major_name;
            return $student;
        });


        return response()->json([
            'status' => true,
            'students_in_company' => $studentsInCompany,
        ]);
    }
}
