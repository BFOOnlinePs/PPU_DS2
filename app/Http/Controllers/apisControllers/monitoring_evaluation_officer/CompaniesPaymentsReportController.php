<?php

namespace App\Http\Controllers\apisControllers\monitoring_evaluation_officer;

use App\Http\Controllers\Controller;
use App\Models\Company;
use App\Models\Currency;
use App\Models\StudentCompany;
use App\Models\SystemSetting;
use App\Models\User;
use Illuminate\Http\Request;

class CompaniesPaymentsReportController extends Controller
{
    public function getCompaniesPaymentsReport(Request $request)
    {

        if ($request->has('year')) {
            $year = $request->input('year');
        } else {
            $year = SystemSetting::first()->ss_year;
        }

        if ($request->has('semester')) {
            $semester = $request->input('semester');
        } else {
            $semester = SystemSetting::first()->ss_semester_type;
        }

        $studentsCompanies = StudentCompany::whereHas('registration', function ($query) use ($year, $semester) {
            $query->where('r_year', $year)->where('r_semester', $semester);
        });
        // ->get();


        // if ($request->has('company')) {
        //     $studentsCompanies = $studentsCompanies->filter(function ($studentCompany) use ($request) {
        //         return $studentCompany->sc_company_id == $request->input('company');
        //     })->values();
        // }
        if ($request->has('company')) {
            $studentsCompanies->where('sc_company_id', $request->input('company'));
        }

        // only the student company that has payment
        $studentsCompanies->whereHas('trainingPayments');

        $studentsCompanies = $studentsCompanies->select('sc_id', 'sc_student_id', 'sc_company_id')->get();

        // Load the 'company' and 'users' relationships
        // $studentsCompanies = $studentsCompanies->load('company', 'users');

        // // Extract only the 'c_name' from the 'company' relationship
        // $studentsCompanies->companyNames = $studentsCompanies->pluck('company.c_name');

        // // Extract only the 'name' from the 'users' relationship
        // $studentsCompanies->userNames = $studentsCompanies->pluck('users.name');
        // $studentsCompanies = $studentsCompanies->with('company')->pluck('c_name')
        //     ->with('users')->pluck('u_username');

        $currencies = Currency::get();
        // return $currencies;

        foreach ($studentsCompanies as $studentCompany) {
            $studentCompany->student_name = User::where('u_id', $studentCompany->sc_student_id)->pluck('name')->first();
            $studentCompany->company_name = Company::where('c_id', $studentCompany->sc_company_id)->pluck('c_name')->first();
            $studentCompany->company_english_name = Company::where('c_id', $studentCompany->sc_company_id)->pluck('c_english_name')->first();

            $paymentsByCurrency = []; // Array to store sums of payments by currency
            $confirmedPayments = [];

            $trainingPayments = $studentCompany->trainingPayments()->get(); // relation

            // Iterate over the training payments for the current StudentCompany
            foreach ($trainingPayments as $payment) {
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

                if ($payment->p_status == 1) {
                    if (!isset($confirmedPayments[$currencySymbol])) {
                        $confirmedPayments[$currencySymbol] = 0; // Initialize the sum if not present
                    }
                    $confirmedPayments[$currencySymbol] += $paymentValue;
                }
            }

            // change the key from currency id to currency symbol
            // Now $paymentsByCurrency contains sums of payments by currency for the current StudentCompany
            $studentCompany->payments_by_currency = $paymentsByCurrency;

            if ($confirmedPayments) {
                $studentCompany->confirmed_payments = $confirmedPayments;
            }
        }

        return response()->json([
            'status' => true,
            'students_companies' => $studentsCompanies
        ]);
    }
}
