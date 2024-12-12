<?php

namespace App\Http\Controllers\apisControllers\sharedFunctions\add_edit_company;

use App\Http\Controllers\Controller;
use App\Models\Company;
use App\Models\CompanyBranch;
use App\Models\companyBranchDepartments;
use App\Models\CompanyDepartment;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class AddCompanyController extends Controller
{
    // first step in add company
    public function createManagerAndHisCompany(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'company_name' => 'required|unique:companies,c_name',
            'company_english_name' => 'required|unique:companies,c_english_name',
            'manager_name' => 'required',
            'manager_email' => 'required|email|unique:users,email',
            'manager_password' => 'required',
            'phone' => 'required', // main branch phone + manager phone
            // 'phone' => ['required', 'regex:/^[0-9]{10}$/'], // main branch phone + manager phone
            'address' => 'required', // main branch phone + manager phone
        ], [
            'company_name.required' => trans('messages.company_name_required'),
            'company_name.unique' => trans('messages.company_name_unique'),
            'company_english_name.required' => trans('messages.company_english_name_required'),
            'company_english_name.unique' => trans('messages.company_english_name_unique'),
            'manager_name.required' => trans('messages.manager_name_required'),
            'manager_email.required' => trans('messages.manager_email_required'),
            'manager_email.email' => trans('messages.manager_email_format'),
            'manager_email.unique' => trans('messages.manager_email_unique'),
            'manager_password.required' => trans('messages.manager_password_required'),
            'phone.required' => trans('messages.company_phone_required'),
            // 'phone.regex' => 'صيغة الهاتف غير صحيحة، ويحب ان يتكون من 10 ارقام',
            'address.required' => trans('messages.company_address_required'),
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => $validator->errors()->first(),
            ], 200);
        }

        // $manager_email = $request->input('manager_email');
        // $manager_username = substr(strstr($manager_email, '@', true), 0);

        $manager_user = User::create([
            'u_username' => $request->input('manager_email'),
            'name' => $request->input('manager_name'),
            'email' => $request->input('manager_email'),
            'password' => bcrypt($request->input('manager_password')),
            'u_phone1' => $request->input('phone'),
            'u_address' => $request->input('address'),
            'u_role_id' => 6, // company manager
        ]);

        // if the manager user created successfully, create the company and the main branch (it is always true)
        if ($manager_user) {
            $company = Company::create([
                'c_name' => $request->input('company_name'),
                'c_english_name' => $request->input('company_english_name'),
                'c_manager_id' => $manager_user->u_id,
                'c_status' => 1, // active
            ]);

            if ($company) { // always true
                $main_branch = CompanyBranch::create([
                    'b_company_id' => $company->c_id,
                    'b_address' => $request->input('address'),
                    'b_phone1' => $request->input('phone'),
                    'b_main_branch' => 1,
                    'b_manager_id' => $manager_user->u_id,
                ]);
                return response()->json([
                    'status' => true,
                    'message' => trans('messages.manager_company_branch_created'),
                    'manager' => $manager_user,
                    'company' => $company,
                    'main_branch' => $main_branch,
                ]);
            }

            // return response()->json([
            //     'status' => true,
            //     'message' => 'تم انشاء حساب المدير و الشركة بنجاح',
            //     'manager' => $manager_user,
            //     'company' => $company
            // ]);
        } else {
            return response()->json([
                'status' => false,
                'message' => trans('messages.manager_account_not_created'),
                'manager' => $manager_user,
            ]);
        }
    }

    // second step in add company
    // update the Companies table by adding: category, type, website and description
    public function updateCompanyAddingCategoryAndType(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'company_id' => 'required|exists:companies,c_id',
            'company_type' => 'required|in:1,2',
            'category_id' => 'required|exists:companies_categories,cc_id',
        ], [
            'company_id.required' => trans('messages.company_id_required'),
            'company_id.exists' => trans('messages.company_id_not_exists'),
            'company_type.required' => trans('messages.company_type_required'),
            'company_type.in' => trans('messages.company_type_in'),
            'category_id.required' => trans('messages.category_id_required'),
            'category_id.exists' => trans('messages.category_id_not_exists'),
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => $validator->errors()->first(),
            ], 200);
        }

        $company = Company::where('c_id', $request->input('company_id'))->first();
        $company->update([
            'c_type' =>  $request->input('company_type'),
            'c_category_id' =>  $request->input('category_id'),
            'c_description' =>  $request->input('company_description'),
            'c_english_description' =>  $request->input('company_english_description'),
            'c_website' =>  $request->input('company_website')
        ]);

        return response()->json([
            'status' => true,
            'message' => trans('messages.company_type_category_added'),
            'company' => $company,
        ], 200);
    }

    // third step
    // add company departments (not mandatory)
    // departments_names is a list of Strings
    public function addCompanyDepartments(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'company_id' => 'required|exists:companies,c_id',

        ], [
            'company_id.required' => trans('messages.company_id_required'),
            'company_id.exists' => trans('messages.company_id_not_exists'),
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => $validator->errors()->first(),
            ], 200);
        }

        $company_id = $request->input('company_id');
        $departments_names = $request->input('departments_names');
        // return response()->json([
        //     'a' => gettype($departments_names)
        // ]);
        foreach ($departments_names as $department_name) {
            $existing_department = CompanyDepartment::where('d_name', $department_name)
                ->where('d_company_id', $company_id)
                ->first();

            if (!$existing_department) {
                // department does not exist, so insert it into the database
                $department = new CompanyDepartment();
                $department->d_name = $department_name;
                $department->d_company_id = $company_id;
                // $department->d_status = 1; // default

                $department->save();
            }
        }

        return response()->json([
            'status' => true,
            'message' => trans('messages.company_departments_added'),
        ]);
    }

    // fourth step

    // company_branches is a list of objects
    // each object is a branch which has:
    // address, phone1, is_main_branch (mandatory)
    // b_phone2 (optional)
    // branch_departments, if no departments -> send empty list of branch_departments
    public function addCompanyBranches(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'company_id' => 'required|exists:companies,c_id',

        ], [
            'company_id.required' => trans('messages.company_id_required'),
            'company_id.exists' => trans('messages.company_id_not_exists'),
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => $validator->errors()->first(),
            ], 200);
        }

        $company_id = $request->input('company_id');
        $manager_id = Company::where('c_id', $company_id)->pluck('c_manager_id')->first();
        $manager = User::where('u_id', $manager_id)->first();
        $main_branch = CompanyBranch::where('b_company_id', $company_id)
            ->where('b_main_branch', 1)->first();
        // return $main_branch;
        $branches = $request->input('company_branches');

        foreach ($branches as $branch) {
            $company_branch = new CompanyBranch();
            $is_company_branch_save = null;
            // when main branch
            if ($branch['is_main_branch'] == 1) {
                $main_branch->update([
                    'b_phone2' => $branch['branch_phone2'],
                    'b_city_id' => $branch['branch_city_id'],
                ]);

                $manager->update([
                    'u_phone2' => $branch['branch_phone2'],
                    'u_city_id' => $branch['branch_city_id'],
                ]);
            } else {
                $company_branch->b_company_id = $company_id;
                $company_branch->b_manager_id = $manager_id;
                $company_branch->b_address = $branch['branch_address'];
                $company_branch->b_phone1 = $branch['branch_phone1'];
                $company_branch->b_phone2 = $branch['branch_phone2'];
                $company_branch->b_city_id = $branch['branch_city_id'];
                $company_branch->b_main_branch = $branch['is_main_branch']; // 1:yes, 0:no
                $is_company_branch_save = $company_branch->save();
                // return $is_company_branch_save;
            }

            // for branch departments
            if ($is_company_branch_save || $branch['is_main_branch'] == 1) {
                $branch_departments = $branch['branch_departments'];
                if ($branch_departments) {
                    foreach ($branch_departments as $branch_department) { // each one is department id
                        $company_branch_department = new companyBranchDepartments();
                        $company_branch_department->cbd_d_id = $branch_department;
                        $company_branch_department->cbd_company_branch_id =
                            $branch['is_main_branch'] == 1 ?
                            $main_branch->b_id :
                            $company_branch->b_id;
                        if (!$company_branch_department->save())
                            return response()->json([
                                'status' => false,
                                'message' => trans('messages.branches_created_departments_not'),
                            ]);
                    }
                }
            } else {
                return response()->json([
                    'status' => false,
                    'message' => trans('messages.branches_not_created'),
                ]);
            }
        }

        return response()->json([
            'status' => true,
            'message' => trans('messages.branches_and_departments_created'),
        ]);
    }

    public function getCompanyDepartments(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'company_id' => 'required|exists:companies,c_id',

        ], [
            'company_id.required' => trans('messages.company_id_required'),
            'company_id.exists' => trans('messages.company_id_not_exists'),
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => $validator->errors()->first(),
            ], 200);
        }

        $company_id = $request->input('company_id');
        $company_departments = CompanyDepartment::where('d_company_id', $company_id)->get();
        return response()->json([
            'status' => true,
            'company_departments' => $company_departments
        ]);
    }

}
