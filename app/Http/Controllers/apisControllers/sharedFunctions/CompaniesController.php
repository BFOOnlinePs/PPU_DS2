<?php

namespace App\Http\Controllers\apisControllers\sharedFunctions;

use App\Http\Controllers\Controller;
use App\Models\CompaniesCategory;
use App\Models\Company;
use App\Models\User;
use Illuminate\Http\Request;

class CompaniesController extends Controller
{

    // for supervisor and coordinator
    public function getAllCompanies(Request $request)
    {
        $company_name_search = $request->input('search_company');

        $companies = Company::orderBy('created_at', 'desc');

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

    // for students dropdown
    public function getActiveCompaniesForDropDown(Request $request)
    {
        $company_name_search = $request->input('search_company');

        $companies = Company::where('c_status', 1); // active company

        if ($company_name_search) {
            $companies = $companies->where(function ($query) use ($company_name_search) {
                $query->where('c_name', 'like', '%' . $company_name_search . '%')
                    ->orWhere('c_english_name', 'like', '%' . $company_name_search . '%');
            });
        }

        $companies = $companies->select('c_id', 'c_name', 'c_english_name')
            // ->limit(6)
            ->get();

        return response()->json([
            'status' => true,
            'companies' => $companies,
        ]);
    }
}
