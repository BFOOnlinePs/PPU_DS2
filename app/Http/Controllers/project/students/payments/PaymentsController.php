<?php

namespace App\Http\Controllers\project\students\payments;

use App\Http\Controllers\Controller;
use App\Models\Payment;
use Illuminate\Http\Request;

class PaymentsController extends Controller
{
    public function index()
    {
        $payments = Payment::where('p_student_id' , auth()->user()->u_id)->get();

        return view('project.student.payments.index' , ['payments' => $payments]);
    }
    public function confirmByAjax(Request $request)
    {
        $payment = Payment::find($request->p_id);
        $payment->p_status = 1;
        $student_id = $payment->p_student_id;
        if($payment->save()) {
            $payments = Payment::where('p_student_id' , $student_id)->get();
            $html = view('project.student.payments.includes.paymentsList' , ['payments' => $payments])->render();
            return response()->json(['html' => $html]);
        }
    }
}
