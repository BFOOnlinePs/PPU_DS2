<?php

namespace App\Http\Controllers\apisControllers\students;

use App\Http\Controllers\Controller;
use App\Models\Company;
use App\Models\CompanyBranch;
use App\Models\companyBranchDepartments;
use App\Models\CompanyDepartment;
use App\Models\Currency;
use App\Models\StudentCompany;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class studentTrainingsController extends Controller
{
    public function getStudentTrainings(Request $request)
    {
        $student_id = $request->input('student_id');

        $user = User::where('u_id', $student_id)->where('u_role_id', 2)->first();

        if (!$user) {
            return response()->json([
                'status' => false,
                "message" => trans('messages.student_id_not_exists'),
            ]);
        }

        $trainings = StudentCompany::where('sc_student_id', $student_id)
            ->orderBy('created_at', 'desc')
            ->paginate(6);

        if ($trainings->isEmpty()) {
            return response()->json([
                'status' => false,
                'message' => trans('messages.no_trainings_for_student')
            ], 200);
        }

        // add company name, branch address, mentor name, and assistant name for each training object
        $trainings->getCollection()->transform(function ($training) {
            $training->company_name = Company::where('c_id', $training->sc_company_id)->pluck('c_name')->first();
            $training->company_english_name = Company::where('c_id', $training->sc_company_id)->pluck('c_english_name')->first();
            $training->branch_name = CompanyBranch::where('b_id', $training->sc_branch_id)->pluck('b_address')->first();
            // $training->mentor_trainer_name = User::where('u_id', $training->sc_mentor_trainer_id)->pluck('name')->first();
            // $training->assistant_name = User::where('u_id', $training->sc_assistant_id)->pluck('name')->first();

            $totalTimeDifference = 0;
            foreach ($training->attendance as $attendance) { // attendance is the relation name
                if ($attendance->sa_out_time === null) {
                    continue; // Skip this attendance record
                }

                $sa_in_time = Carbon::parse($attendance->sa_in_time);
                $sa_out_time = Carbon::parse($attendance->sa_out_time);

                $timeDifference = $sa_out_time->diffInSeconds($sa_in_time);

                $totalTimeDifference += $timeDifference;
            }
            $totalHours = floor($totalTimeDifference / 3600);
            $totalMinutes = floor(($totalTimeDifference % 3600) / 60);
            $totalSeconds = $totalTimeDifference % 60;

            $training->total_hours = $totalHours;
            $training->total_minutes = $totalMinutes;
            $training->total_seconds = $totalSeconds;

            unset($training->attendance);


            // training payments

            $paymentsByCurrency = []; // Array to store sums of payments by currency

            // Iterate over the training payments for the current StudentCompany
            foreach ($training->trainingPayments as $payment) {
                // Calculate the sum for each currency
                $currencyId = $payment->p_currency_id;
                $paymentValue = $payment->p_payment_value;

                // Fetch currency symbol
                $currencySymbol = Currency::where('c_id', $currencyId)->pluck('c_symbol')->first();

                // Add the payment value to the corresponding currency sum
                if (!isset($paymentsByCurrency[$currencySymbol])) {
                    $paymentsByCurrency[$currencySymbol] = 0; // Initialize the sum if not present
                }

                $paymentsByCurrency[$currencySymbol] += $paymentValue;
            }

            // change the key from currency id to currency symbol
            // Now $paymentsByCurrency contains sums of payments by currency for the current StudentCompany
            $training->payments_by_currency = empty($paymentsByCurrency) ? (object)[] : $paymentsByCurrency;
            unset($training->trainingPayments);


            return $training;
        });


        return response()->json([
            'status' => true,
            'pagination' => [
                'current_page' => $trainings->currentPage(),
                'last_page' => $trainings->lastPage(),
                'per_page' => $trainings->perPage(),
                'total_items' => $trainings->total(),
            ],
            'student_companies' => $trainings->items(),
        ], 200);
    }

    public function getStudentTrainingsForPayments(Request $request)
    {
        $student_id = $request->input('student_id');

        $user = User::where('u_id', $student_id)->where('u_role_id', 2)->first();

        if (!$user) {
            return response()->json([
                'status' => false,
                "message" => trans('messages.student_id_not_exists'),
            ]);
        }

        $trainings = StudentCompany::where('sc_student_id', $student_id)
            ->where('sc_status', 1) // active
            ->select('sc_id', 'sc_company_id', 'sc_student_id', 'created_at')
            ->orderBy('created_at', 'desc')
            ->get();

        if ($trainings->isEmpty()) {
            return response()->json([
                'status' => false,
                'message' => trans('messages.no_trainings_for_student')
            ], 200);
        }

        // add company name, branch address, mentor name, and assistant name for each training object
        $trainings->transform(function ($training) {
            $company = Company::where('c_id', $training->sc_company_id)->first();
            if ($company) {
                $training->company_name = $company->c_name;
                $training->company_english_name = $company->c_english_name;
            }
            return $training;
        });


        return response()->json([
            'status' => true,
            'student_companies' => $trainings,
        ], 200);
    }

    public function registerStudentInTraining(Request $request)
    {
        $validator = Validator::make(
            $request->all(),
            [
                'student_id' => 'required',
                'company_id' => 'required',
                'branch_id' => 'required',
                'registration_id' => 'required',
                'agreement_file' => 'nullable|file|mimes:jpg,jpeg,png,svg,pdf,doc,docx,xls,xlsx,ppt,pptx,odt,ods,odp,csv,xlsx',
            ],
            [
                'student_id.required' => trans('messages.student_id_required'),
                'company_id.required' => trans('messages.company_id_required'),
                'branch_id.required' => trans('messages.branch_id_required'),
                'registration_id.required' => trans('messages.registration_id_required'),
                'agreement_file.mimes' => trans('messages.agreement_file_mimes'),
            ]
        );

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                "message" => $validator->errors()->first(),
            ]);
        }

        $student_id = $request->input('student_id');
        $company_id = $request->input('company_id');
        $branch_id = $request->input('branch_id');
        $registration_id = $request->input('registration_id');
        $department_id = $request->input('department_id');
        $mentor_id = $request->input('mentor_id');
        $assistant_id = $request->input('assistant_id');


        $user = User::where('u_id', $student_id)->where('u_role_id', 2)->first();

        if (!$user) {
            return response()->json([
                'status' => false,
                "message" => trans('messages.student_id_required'),
            ]);
        }

        $company = Company::where('c_id', $company_id)->first();

        if (!$company) {
            return response()->json([
                'status' => false,
                "message" => trans('messages.company_id_required'),
            ]);
        }


        if ($request->hasFile('agreement_file')) {
            $file = $request->file('agreement_file');
            $folderPath = 'uploads';
            // $fileName = time() . '_' . uniqid() . '.' . $file->getClientOriginalName();
            $extension = $file->getClientOriginalExtension();
            $fileName = time() . '_' . uniqid() . '.' . $extension;
            $file->storeAs($folderPath, $fileName, 'public');
        }

        $student_company = StudentCompany::create([
            'sc_student_id' => $student_id,
            'sc_company_id' => $company_id,
            'sc_branch_id' => $branch_id,
            'sc_registration_id' => $registration_id,
            'sc_department_id' => $department_id,
            'sc_status' => 1,
            'sc_mentor_trainer_id' => $mentor_id,
            'sc_assistant_id' => $assistant_id,
            'sc_agreement_file' => $fileName ?? null

        ]);

        return response()->json([
            'status' => true,
            "message" => trans('messages.student_reg_in_training'),
            'student_company' => $student_company
        ]);
    }

    public function updateStudentRegistrationInTraining(Request $request)
    {
        $validator = Validator::make(
            $request->all(),
            [
                'sc_id' => 'required',
                'branch_id' => 'required',
                'agreement_file' => 'nullable|file|mimes:jpg,jpeg,png,svg,pdf,doc,docx,xls,xlsx,ppt,pptx,odt,ods,odp,csv,xlsx',
            ],
            [
                'sc_id.required' => trans('messages.training_id_required'),
                'branch_id.required' => trans('messages.branch_id_required'),
                'agreement_file.mimes' => trans('messages.agreement_file_mimes'),
            ]
        );

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                "message" => $validator->errors()->first(),
            ]);
        }
        $sc_id = $request->input('sc_id');
        $student_company = StudentCompany::where('sc_id', $sc_id)->first();
        if (!$student_company) {
            return response()->json([
                'status' => false,
                "message" => trans('messages.training_id_not_exists'),
            ]);
        }


        $student_company->update([
            'sc_branch_id' => $request->input('branch_id'),
            'sc_department_id' => $request->input('department_id'),
            'sc_mentor_id' => $request->input('mentor_id'),
            'sc_assistant_id' => $request->input('assistant_id'),
            'sc_status' => $request->input('status'),
        ]);

        if ($request->hasFile('agreement_file')) {
            $file = $request->file('agreement_file');
            $folderPath = 'uploads';
            // $fileName = time() . '_' . uniqid() . '.' . $file->getClientOriginalName();
            $extension = $file->getClientOriginalExtension();
            $fileName = time() . '_' . uniqid() . '.' . $extension;
            $file->storeAs($folderPath, $fileName, 'public');
            // delete old file
            Storage::disk('public')->delete($folderPath . '/' . $student_company->sc_agreement_file);
            $student_company->update([
                'sc_agreement_file' => $fileName
            ]);
        }

        return response()->json([
            'status' => true,
            "message" => trans('messages.student_reg_in_training_updated'),
        ]);
    }

    // with search as query param.
    public function getAllCompaniesWithSearch(Request $request)
    {
        $company_name_search = $request->input('company_name_search');
        // $companies = Company::get();
        $companies = Company::where('c_name', 'like', '%' . $company_name_search . '%')->get();

        // $assistants = User::where('u_role_id', 4)->get();

        return response()->json([
            'status' => true,
            'companies' => $companies,
            // 'assistants' => $assistants,
        ]);
    }

    public function getAllAssistants()
    {
        $assistants = User::where('u_role_id', 4)->get();

        return response()->json([
            'status' => true,
            'assistants' => $assistants,
        ]);
    }


    public function getCompanyBranchesWithEmployees(Request $request)
    {
        $validator = Validator::make(
            $request->all(),
            [
                'company_id' => 'required',
            ],
            [
                'company_id.required' => trans('messages.company_id_required'),
            ]
        );

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                "message" => $validator->errors()->first(),
            ]);
        }

        $company_id = $request->input('company_id');
        $company = Company::where('c_id', $company_id)->first();

        if (!$company) {
            return response()->json([
                'status' => false,
                "message" => trans('messages.company_id_not_exists'),
            ]);
        }

        $company_branches = CompanyBranch::where('b_company_id', $company_id)->get();
        $company_employees = User::where('u_company_id', $company_id)->get();

        // if($company_branches->isEmpty()){
        //     return response()->json([
        //         'status' => false,
        //         "message" => 'لا يوجد فروع للشركة',
        //     ]);
        // }

        return response()->json([
            'status' => true,
            "company_branches" => $company_branches,
            'company_employees' => $company_employees
        ]);
    }

    public function getBranchDepartments(Request $request)
    {
        $validator = Validator::make(
            $request->all(),
            [
                'branch_id' => 'required',
            ],
            [
                'branch_id.required' => trans('messages.branch_id_required'),
            ]
        );

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                "message" => $validator->errors()->first(),
            ]);
        }

        $branch_id = $request->input('branch_id');
        $branch = CompanyBranch::where('b_id', $branch_id)->first();

        if (!$branch) {
            return response()->json([
                'status' => false,
                "message" => trans('messages.branch_id_exists'),
            ]);
        }

        $branch_departments_ids = companyBranchDepartments::where('cbd_company_branch_id', $branch_id)->pluck('cbd_d_id');
        $company_departments_with_names = CompanyDepartment::whereIn('d_id', $branch_departments_ids)->get();

        return response()->json([
            'status' => true,
            'departments' => $company_departments_with_names,
        ]);
    }
}
