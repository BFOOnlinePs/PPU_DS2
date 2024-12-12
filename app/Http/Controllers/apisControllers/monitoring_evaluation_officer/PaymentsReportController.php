<?php

namespace App\Http\Controllers\apisControllers\monitoring_evaluation_officer;

use App\Http\Controllers\Controller;
use App\Models\Company;
use App\Models\Currency;
use App\Models\Payment;
use App\Models\StudentCompany;
use App\Models\SystemSetting;
use App\Models\User;
use Illuminate\Http\Request;

class PaymentsReportController extends Controller
{
    // filter as query parameter
    public function getAllPayments(Request $request)
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


        if ($request->has('company')) {
            $studentsCompanies->where('sc_company_id', $request->input('company'));
        }

        if ($request->has('student')) {
            $studentsCompanies->where('sc_student_id', $request->input('student'));
        }

        $studentsCompaniesIdList = $studentsCompanies->pluck('sc_id');

        $payments = Payment::whereIn('p_student_company_id', $studentsCompaniesIdList);

        if ($request->has('payment_status')) {
            $payments->where('p_status', $request->input('payment_status'));
        }

        $payments = $payments->orderBy('created_at', 'desc')->get();

        $payments->map(function ($payment) {
            $payment->student_name = User::where('u_id', $payment->p_student_id)->pluck('name')->first();
            $payment->company_name = Company::where('c_id', $payment->p_company_id)->pluck('c_name')->first();
            $payment->company_english_name = Company::where('c_id', $payment->p_company_id)->pluck('c_english_name')->first();
            $payment->currency_symbol = Currency::where('c_id', $payment->p_currency_id)->pluck('c_symbol')->first();
        });


        return response()->json([
            'status' => true,
            'payments' => $payments
        ]);
    }


    // student name filter as query params
    // students who has training
    public function getStudentsNamesWithSearch(Request $request)
    {
        $student_name_search = $request->input('student_name');

        $unique_students_ids = StudentCompany::select('sc_student_id')
            ->distinct()
            ->orderBy('created_at', 'desc')
            ->get()
            ->pluck('sc_student_id');

        // if ($student_name_search) {
            $students = User::whereIn('u_id', $unique_students_ids)
                ->where('name', 'like', '%' . $student_name_search . '%')
                ->select('u_id', 'name')
                ->limit(5)
                ->get();
        // }


        return response()->json([
            'status' => true,
            'students' => $students,
        ]);
    }
}
