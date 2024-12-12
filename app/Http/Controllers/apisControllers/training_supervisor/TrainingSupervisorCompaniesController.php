<?php

namespace App\Http\Controllers\apisControllers\training_supervisor;

use App\Http\Controllers\Controller;
use App\Models\CompaniesCategory;
use App\Models\Company;
use App\Models\Registration;
use App\Models\StudentCompany;
use App\Models\SystemSetting;
use App\Models\User;
use Illuminate\Http\Request;

class TrainingSupervisorCompaniesController extends Controller
{
    // companies where the supervisor students are registered
    public function getTrainingSupervisorCompanies(Request $request)
    {
        $current_user = auth()->user();
        $system_settings = SystemSetting::first();

        $company_name_search = $request->input('search_company');

        $registration_ids = Registration::where('supervisor_id', $current_user->u_id)
            ->where('r_semester', $system_settings->ss_semester_type)
            ->where('r_year', $system_settings->ss_year)
            ->pluck('r_id');

        $student_companies = StudentCompany::whereIn('sc_registration_id', $registration_ids)
            ->where('sc_status', 1) //
            ->pluck('sc_company_id');

        $companies = Company::whereIn('c_id', $student_companies)
            ->orderBy('created_at', 'desc');

        if ($company_name_search) {
            $companies = $companies->where(function ($query) use ($company_name_search) {
                $query->where('c_name', 'like', '%' . $company_name_search . '%')
                    ->orWhere('c_english_name', 'like', '%' . $company_name_search . '%');
            });
        }

        $companies = $companies->paginate(4);

        $companies->getCollection()->transform(function ($company) {
            $company->manager_name = User::where('u_id', $company->c_manager_id)->pluck('name')->first();
            $company->category_name = CompaniesCategory::where('cc_id', $company->c_category_id)->pluck('cc_name')->first();
            return $company;
        });

        return response()->json([
            'status' => true,
            'pagination' => [
                'current_page' => $companies->currentPage(),
                'last_page' => $companies->lastPage(),
                'per_page' => $companies->perPage(),
                'total_items' => $companies->total(),
            ],
            'companies' => $companies->items()
        ]);
    }

    // for drop down, in add visit
    public function getTrainingSupervisorCompaniesForDropDown()
    {
        $current_user = auth()->user();
        $system_settings = SystemSetting::first();

        $registration_ids = Registration::where('supervisor_id', $current_user->u_id)
            ->where('r_semester', $system_settings->ss_semester_type)
            ->where('r_year', $system_settings->ss_year)
            ->pluck('r_id');

        $student_companies = StudentCompany::whereIn('sc_registration_id', $registration_ids)->pluck('sc_company_id');

        $companies = Company::whereIn('c_id', $student_companies)
            ->select('c_id', 'c_name', 'c_english_name')
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json([
            'status' => true,
            'companies' => $companies,
        ]);
    }
}
