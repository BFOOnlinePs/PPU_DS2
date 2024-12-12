<?php

namespace App\Http\Controllers\apisControllers\company_manager\payments;

use App\Http\Controllers\Controller;
use App\Models\Company;
use App\Models\Currency;
use App\Models\Payment;
use App\Models\Registration;
use App\Models\StudentCompany;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class AllTraineesPaymentsController extends Controller
{
    // all trainees payment in the manager's company
    // filter search with trainee id
    public function getAllTraineesPayments(Request $request)
    {
        $manager_id = auth()->user()->u_id;
        $company_id = Company::where('c_manager_id', $manager_id)->pluck('c_id')->first();
        $payments = Payment::where('p_company_id', $company_id)
            ->orderBy('created_at', 'desc');
        // ->paginate(7);
        // ->get();

        if ($request->has('trainee_id')) {
            $trainee_id = $request->input('trainee_id');
            $payments->where('p_student_id', $trainee_id);
        }

        if ($request->has('payment_status')) {
            $payment_status = $request->input('payment_status');
            $payments->where('p_status', $payment_status);
        }

        $payments = $payments->paginate(7);

        $payments->getCollection()->transform(function ($payment) use ($manager_id) {
            // $payment->inserted_by_name = User::where('u_id', $manager_id)->pluck('name')->first();
            $payment->inserted_by_name = User::where('u_id', $payment->p_inserted_by_id)->pluck('name')->first();
            $payment->student_name = User::where('u_id', $payment->p_student_id)->pluck('name')->first();
            // $payment->student_number = User::where('u_id', $payment->p_student_id)->pluck('u_username')->first();
            $payment->student_email = User::where('u_id', $payment->p_student_id)->pluck('email')->first();
            $payment->currency_symbol = Currency::where('c_id', $payment->p_currency_id)->pluck('c_symbol')->first();

            $student_training = StudentCompany::where('sc_id', $payment->p_student_company_id)->first();

            $payment->training_status = $student_training->sc_status;
            $registration = Registration::where('r_id', $student_training->sc_registration_id)->first();
            $payment->semester = $registration->r_semester;
            $payment->year = $registration->r_year;

            return $payment;
        });

        return response()->json([
            'status' => true,
            'pagination' => [
                'current_page' => $payments->currentPage(),
                'last_page' => $payments->lastPage(),
                'per_page' => $payments->perPage(),
                'total_items' => $payments->total(),
            ],
            'payments' => $payments->items(),
        ]);
    }
}
