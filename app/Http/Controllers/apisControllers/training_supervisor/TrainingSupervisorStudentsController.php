<?php

namespace App\Http\Controllers\apisControllers\training_supervisor;

use App\Http\Controllers\Controller;
use App\Models\Major;
use App\Models\Registration;
use App\Models\StudentCompany;
use App\Models\SystemSetting;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class TrainingSupervisorStudentsController extends Controller
{
    public function getTrainingSupervisorStudents(Request $request)
    {
        $system_settings = SystemSetting::first();

        $supervisorId = auth()->user()->u_id;
        $studentsIds = Registration::where('supervisor_id', $supervisorId)
            ->where('r_semester', $system_settings->ss_semester_type)
            ->where('r_year', $system_settings->ss_year)
            ->pluck('r_student_id');
        $studentsList = User::where('u_role_id', 2)->whereIn('u_id', $studentsIds);

        // search
        $requestSearch = $request->input('search');
        if (!empty($requestSearch)) {
            $studentsList->where(function ($query) use ($requestSearch) {
                $query->where('u_username', 'like', '%' . $requestSearch . '%')
                    ->orWhere('name', 'like', '%' . $requestSearch . '%');
            });
        }

        // if (request()->has('major_id')) {
        //     $majorId = $request->input('major_id');
        //     $studentsList->where('u_major_id', $majorId);
        // }

        $studentsList = $studentsList->with('major')->paginate(8);

        return response()->json([
            'status' => true,
            'pagination' => [
                'current_page' => $studentsList->currentPage(),
                'last_page' => $studentsList->lastPage(),
                'per_page' => $studentsList->perPage(),
                'total_items' => $studentsList->total(),
            ],
            'students' => $studentsList->items(),

        ]);
    }

    public function getTrainingSupervisorStudentsInCompany(Request $request)
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
        $system_settings = SystemSetting::first();

        $studentsIds = Registration::where('supervisor_id', $supervisorId)
            ->where('r_semester', $system_settings->ss_semester_type)
            ->where('r_year', $system_settings->ss_year)
            ->pluck('r_student_id')
            ->unique();

        $studentsList = User::where('u_role_id', 2)->whereIn('u_id', $studentsIds)->pluck('u_id');
        $studentInCompanyIdList = StudentCompany::where('sc_company_id', $companyId)->whereIn('sc_student_id', $studentsList)->pluck('sc_student_id');
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

    public function getTrainingSupervisorStudentsNamesInCompany(Request $request)
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
        $system_settings = SystemSetting::first();

        $studentsIds = Registration::where('supervisor_id', $supervisorId)
            ->where('r_semester', $system_settings->ss_semester_type)
            ->where('r_year', $system_settings->ss_year)
            ->pluck('r_student_id');
        // ->unique();

        // enable if you want to repeat the students
        // $studentsList = User::where('u_role_id', 2)->whereIn('u_id', $studentsIds)->pluck('u_id');
        $studentInCompanyIdList = StudentCompany::where('sc_company_id', $companyId)
            ->whereIn('sc_student_id', $studentsIds)
            ->where('sc_status', 1) // only active trainings
            ->pluck('sc_student_id');

        $studentsInCompany = User::whereIn('u_id', $studentInCompanyIdList)
            ->select('u_id', 'name')
            ->get();

        return response()->json([
            'status' => true,
            'students_in_company' => $studentsInCompany,
        ]);
    }
}
