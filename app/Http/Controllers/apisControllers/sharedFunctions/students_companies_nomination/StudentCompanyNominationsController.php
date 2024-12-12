<?php

namespace App\Http\Controllers\apisControllers\sharedFunctions\students_companies_nomination;

use App\Http\Controllers\Controller;
use App\Models\Company;
use App\Models\StudentCompanyNominationModel;
use App\Models\SystemSetting;
use App\Models\User;


class StudentCompanyNominationsController extends Controller
{
    public function getNominatedStudentsForCurrentManagerCompany()
    {
        $company_id = Company::where('c_manager_id', auth()->user()->u_id)->pluck('c_id')->first();
        $system_settings = SystemSetting::first();
        $nominated_students_id = StudentCompanyNominationModel::where('scn_year', $system_settings->ss_year)
            ->where('scn_semester', $system_settings->ss_semester_type)
            ->where('scn_company_id', $company_id)
            ->pluck('scn_student_id');

        $nominated_students = User::whereIn('u_id', $nominated_students_id)
            ->with('major:m_id,m_name,m_academic_plan')
            ->paginate(10);

        return response()->json([
            'status' => true,
            'pagination' => [
                'current_page' => $nominated_students->currentPage(),
                'last_page' => $nominated_students->lastPage(),
                'per_page' => $nominated_students->perPage(),
                'total_items' => $nominated_students->total(),
            ],
            'nominated_students' => $nominated_students->items()
        ]);
    }
}
