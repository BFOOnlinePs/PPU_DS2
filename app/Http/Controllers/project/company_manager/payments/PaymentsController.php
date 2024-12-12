<?php

namespace App\Http\Controllers\project\company_manager\payments;

use App\Http\Controllers\Controller;
use App\Models\Company;
use App\Models\Payment;
use Illuminate\Http\Request;

class PaymentsController extends Controller
{
    public function index()
    {
        $company = Company::where('c_manager_id' , auth()->user()->u_id)->first();
        $payments = Payment::where('p_company_id', $company->c_id)
                            ->get();
        return view('project.company_manager.payments.index' , ['payments' => $payments]);
    }

    public function update_status($id){
        $data = Payment::where('p_id' , $id)->first();
        $data->p_status = 1;
        if($data->save()){
            return redirect()->route('company_manager.payments.index')->with(['success' => 'تم تعديل حالة الدفعة بنجاح']);
        }
    }
}
