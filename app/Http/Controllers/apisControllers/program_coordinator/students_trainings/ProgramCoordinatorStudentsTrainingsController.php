<?php

namespace App\Http\Controllers\apisControllers\program_coordinator\students_trainings;

use App\Http\Controllers\Controller;
use App\Models\Company;
use App\Models\Major;
use App\Models\StudentCompany;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class ProgramCoordinatorStudentsTrainingsController extends Controller
{
    // training places for all students, with number of student in each company
    public function getStudentsCompanies()
    {
        // to get number of students in each company
        $companies = StudentCompany::groupBy('sc_company_id')
            ->select('sc_company_id', DB::raw('count(distinct sc_student_id) as student_count'))
            ->paginate(10);

        // to get the whole Company Model instead of only the id
        $companies->getCollection()->Transform(function ($company) {
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
        ]);
    }

    public function getAllStudentsInCompany(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'company_id' => 'required',
        ], [
            'company_id.required' => trans('messages.company_id_required'),
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => $validator->errors()->first(),
            ], 200);
        }

        $companyId = $request->input('company_id');
        $studentInCompanyIdList = StudentCompany::where('sc_company_id', $companyId)->pluck('sc_student_id');
        $studentsInCompany = User::whereIn('u_id', $studentInCompanyIdList)->get();

        if ($studentsInCompany->isEmpty()) {
            return response()->json([
                'status' => false,
                'message' => trans('messages.no_students_in_this_company_yet')
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
