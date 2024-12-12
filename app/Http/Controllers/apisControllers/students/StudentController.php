<?php

namespace App\Http\Controllers\apisControllers\students;

use App\Http\Controllers\Controller;
use App\Models\Company;
use App\Models\CompanyBranch;
use App\Models\Currency;
use App\Models\Payment;
use App\Models\StudentCompany;
use App\Models\User;
use Carbon\Carbon;

class StudentController extends Controller
{
    // the companies that student registered in for trainings / current student
    public function index()
    {
        $student_id = auth()->user()->u_id;

        $user = User::where('u_id', $student_id)->where('u_role_id', 2)->first();

        if (!$user) {
            return response()->json([
                'status' => false,
                "message" => trans('messages.student_id_not_exists'),
            ]);
        }

        $trainings = StudentCompany::where('sc_student_id', $student_id)
            ->where('sc_status', '!=', 3) // active
            ->orderBy('created_at', 'desc')
            ->paginate(6);

        if ($trainings->isEmpty()) {
            return response()->json([
                'status' => false,
                'message' => trans('messages.no_company_for_student_yet'),
            ], 200);
        }

        // add company name, branch address, mentor name, and assistant name for each training object
        $trainings->getCollection()->transform(function ($training) {
            $training->company_name = Company::where('c_id', $training->sc_company_id)->pluck('c_name')->first();
            $training->company_english_name = Company::where('c_id', $training->sc_company_id)->pluck('c_english_name')->first();
            $training->branch_name = CompanyBranch::where('b_id', $training->sc_branch_id)->pluck('b_address')->first();
            // $training->mentor_trainer_name = User::where('u_id', $training->sc_mentor_trainer_id)->pluck('name')->first();
            // $training->assistant_name = User::where('u_id', $training->sc_assistant_id)->pluck('name')->first();

            // training hours
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
            'pagination' => [
                'current_page' => $trainings->currentPage(),
                'last_page' => $trainings->lastPage(),
                'per_page' => $trainings->perPage(),
                'total_items' => $trainings->total(),
            ],
            'student_companies' => $trainings->items(),
        ], 200);
    }
}




// $companies = StudentCompany::where('sc_student_id', auth()->user()->u_id)
//     ->with('companyBranch')->get();
//
// same as:
//
// foreach($companies as $key){
//     $key->bb = CompanyBranch::where('b_id',$key->sc_branch_id)->first();
// }
