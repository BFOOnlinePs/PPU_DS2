<?php

namespace App\Http\Controllers\apisControllers\sharedFunctions\add_edit_company;

use App\Http\Controllers\Controller;
use App\Models\Company;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

// this edit company to update status and capacity (not from stepper)

class EditCompanyInfoController extends Controller
{
    public function updateCompanyStatus(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'company_id' => 'required|exists:companies,c_id',
            'company_status' => 'required|in:0,1',
        ], [
            'company_id.required' => trans('messages.company_id_required'),
            'company_id.exists' => trans('messages.company_id_not_exists'),
            'company_status.required' => trans('messages.company_status_required'),
            'company_status.in' => trans('messages.company_status_in'),
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => $validator->errors()->first(),
            ]);
        }

        $company = Company::where('c_id', $request->input(['company_id']))->first();

        $company->update([
            'c_status' => $request->input(['company_status']),
        ]);

        return response()->json([
            'status' => true,
            'message' => trans('messages.company_status_updated'),
            'company' => $company,
        ]);
    }

    public function updateCompanyCapacity(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'company_id' => 'required|exists:companies,c_id',
            'company_capacity' => 'nullable|integer',
        ], [
            'company_id.required' => trans('messages.company_id_required'),
            'company_id.exists' => trans('messages.company_id_not_exists'),
            // 'company_capacity.required' => trans('messages.company_capacity_required'),
            'company_capacity.integer' => trans('messages.company_capacity_integer'),
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => $validator->errors()->first(),
            ]);
        }

        $company = Company::where('c_id', $request->input(['company_id']))->first();

        $company->update([
            'c_capacity' => $request->input(['company_capacity']),
        ]);

        return response()->json([
            'status' => true,
            'message' => trans('messages.company_capacity_updated'),
            'company' => $company,
        ]);
    }
}
