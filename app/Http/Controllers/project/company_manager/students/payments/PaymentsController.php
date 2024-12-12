<?php

namespace App\Http\Controllers\project\company_manager\students\payments;

use App\Http\Controllers\Controller;
use App\Models\Currency;
use App\Models\Company;
use App\Models\Payment;
use Illuminate\Http\Request;

class PaymentsController extends Controller
{
    public function index($id , $name_student , $student_company_id)
    {
        $currencies = Currency::get();
        $company = Company::where('c_manager_id' , auth()->user()->u_id)->first();
        $payments = Payment::where('p_student_id', $id)
                            ->where('p_student_company_id', $student_company_id)
                            ->get();
        return view('project.company_manager.students.payments.index' ,
        [
            'currencies' => $currencies ,
            'id' => $id ,
            'payments' => $payments ,
            'name_student' => $name_student ,
            'student_company_id' => $student_company_id
        ]);
    }
    public function create(Request $request)
    {
        $payment = new Payment;
        $payment->p_student_id = $request->p_student_id;
        $payment->p_student_company_id = $request->student_company_id;
        $company = Company::where('c_manager_id' , auth()->user()->u_id)->first();
        $payment->p_company_id = $company->c_id;
        $payment->p_reference_id = $request->p_reference_id;
        $payment->p_payment_value = $request->p_payment_value;
        $payment->p_currency_id = $request->p_currency_id;
        $payment->p_company_id = $company->c_id;
        if ($request->hasFile('p_file')) {
            $file = $request->file('p_file');
            $extension = $file->getClientOriginalExtension();
            $filename = time() . '.' . $extension; // Unique filename
            $file->storeAs('payments', $filename, 'public');
            $payment->p_file = $filename;
        }
        $payment->p_inserted_by_id = auth()->user()->u_id;
        $payment->p_company_notes = $request->p_company_notes;
        if($payment->save()) {
            $company = Company::where('c_manager_id' , auth()->user()->u_id)->first();
            $payments = Payment::where('p_student_id', $request->p_student_id)
                                ->where('p_student_company_id', $request->student_company_id)
                                ->get();
            $html = view('project.company_manager.students.payments.ajax.paymentsList' , ['payments' => $payments])->render();
            return response()->json(['html' => $html]);
        }
    }
}
