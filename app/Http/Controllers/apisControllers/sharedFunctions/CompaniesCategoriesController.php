<?php

namespace App\Http\Controllers\apisControllers\sharedFunctions;

use App\Http\Controllers\Controller;
use App\Models\CompaniesCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

// for supervisor
class CompaniesCategoriesController extends Controller
{
    // get Companies Categories with search
    public function getCompaniesCategories(Request $request)
    {
        $requestSearch = $request->input('search');
        $companies_categories = CompaniesCategory::where('cc_name', 'like', '%' . $requestSearch . '%')->get();

        return response()->json([
            'status' => true,
            'companiesCategories' => $companies_categories
        ]);
    }

    public function addCompanyCategory(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'category_name' => 'required'
        ], [
            'category_name.required' => trans('messages.category_name_required'),
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => $validator->errors()->first(),
            ]);
        }

        $category_name = $request->input('category_name');

        $company_category = CompaniesCategory::create([
            'cc_name' => $category_name,
        ]);

        return response()->json([
            'status' => true,
            'message' => trans('messages.category_added'),
            // 'companyCategory' => $company_category,
        ]);
    }

    public function editCompanyCategory(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'category_id' => 'required',
            'category_name' => 'required'
        ], [
            'category_id.required' => trans('messages.category_id_required'),
            'category_name.required' => trans('messages.category_name_required'),
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => $validator->errors()->first(),
            ]);
        }

        $category_id = $request->input('category_id');
        $category_name = $request->input('category_name');

        $company_category = CompaniesCategory::where('cc_id', $category_id)->first();

        if (!$company_category) {
            return response()->json([
                'status' => false,
                'message' => trans('messages.category_id_not_exists'),
            ]);
        }

        $company_category->update([
            'cc_name' => $category_name,
        ]);

        return response()->json([
            'status' => true,
            'message' => trans('messages.category_updated'),
            'companyCategory' => $company_category,
        ]);
    }
}
