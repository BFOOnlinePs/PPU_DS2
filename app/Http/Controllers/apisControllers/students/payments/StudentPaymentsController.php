<?php

namespace App\Http\Controllers\apisControllers\students\payments;

use App\Http\Controllers\Controller;
use App\Models\Company;
use App\Models\Currency;
use App\Models\Payment;
use App\Models\Registration;
use App\Models\StudentCompany;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class StudentPaymentsController extends Controller
{
    // for current student
    public function getAllStudentPayments()
    {
        $student_id = auth()->user()->u_id;
        $user = User::where('u_id', $student_id)->where('u_role_id', 2)->first();
        if (!$user) {
            return response()->json([
                'status' => false,
                "message" => trans('messages.student_id_not_exists'),
            ]);
        }

        $payments = Payment::where('p_student_id', $student_id)
            ->orderBy('created_at', 'desc')
            ->paginate(7);
        // ->get();

        $payments->getCollection()->transform(function ($payment) {
            $payment->inserted_by_name = User::where('u_id', $payment->p_inserted_by_id)->pluck('name')->first();
            $payment->company_name = Company::where('c_id', $payment->p_company_id)->pluck('c_name')->first();
            $payment->company_english_name = Company::where('c_id', $payment->p_company_id)->pluck('c_english_name')->first();
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

    public function getStudentCompanyPayments(Request $request)
    {
        $student_company_id = $request->input('student_company_id');
        $student_company = StudentCompany::where('sc_id', $student_company_id)->first();
        if (!$student_company) {
            return response()->json([
                'status' => false,
                "message" => trans('messages.training_id_not_exists'),
            ]);
        }

        $payments = Payment::where('p_student_company_id', $student_company_id)
            ->orderBy('created_at', 'desc')
            ->paginate(7);
        // ->get();

        $payments->getCollection()->transform(function ($payment,) use ($student_company_id) {
            $payment->inserted_by_name = User::where('u_id', $payment->p_inserted_by_id)->pluck('name')->first();
            $payment->company_name = Company::where('c_id', $payment->p_company_id)->pluck('c_name')->first();
            $payment->currency_symbol = Currency::where('c_id', $payment->p_currency_id)->pluck('c_symbol')->first();

            $student_training = StudentCompany::where('sc_id', $student_company_id)->first();

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

    // the status should be: 0 or 1
    // 0 default
    // 1 confirmed by the student
    public function studentChangePaymentStatus(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'payment_id' => 'required',
            'payment_status' => 'required',
        ], [
            'payment_status.required' => trans('messages.payment_status_required'),
            'payment_id.required' =>  trans('messages.payment_id_required'),
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                "message" => $validator->errors()->first(),
            ]);
        }

        $payment = Payment::where('p_id', $request->input('payment_id'))->first();
        if (!$payment) {
            return response()->json([
                'status' => false,
                "message" => trans('messages.payment_id_not_exists'),
            ]);
        }

        if ($payment->p_student_id != auth()->user()->u_id) {
            return response()->json([
                'status' => false,
                "message" => trans('messages.payment_confirm_not_auth'),
            ]);
        }

        $payment->update([
            'p_status' => $request->input('payment_status')
        ]);

        return response()->json([
            'status' => true,
            "message" => trans('messages.payment_confirmed'),
            'payment' => $payment
        ]);
    }

    public function studentAddOrEditPaymentNote(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'payment_id' => 'required',
            'student_note' => 'required',
        ], [
            'payment_id.required' => trans('messages.payment_id_required'),
            'student_note.required' => trans('messages.student_note_required'),
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => $validator->errors()->first(),
            ], 200);
        }

        $payment_id = $request->input('payment_id');
        $payment = Payment::where('p_id', $payment_id)->first();
        if (!$payment) {
            return response()->json([
                'status' => false,
                "message" => trans('messages.payment_id_not_exists'),
            ]);
        }

        $payment->update([
            'p_student_notes' => $request->input('student_note'),
        ]);

        return response()->json([
            'status' => true,
            'message' => trans('messages.student_note_added'),
            'payment' => $payment
        ]);
    }
}
