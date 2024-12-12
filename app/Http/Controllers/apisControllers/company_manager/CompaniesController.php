<?php

namespace App\Http\Controllers\apisControllers\company_manager;

use App\Http\Controllers\Controller;
use App\Models\Company;
use Illuminate\Http\Request;

class CompaniesController extends Controller
{
    public function list_student_in_company(){
        $data = Company::where('c_manager_id',auth()->user()->u_id)->with('trainings.users')->get();
        return response()->json([
            'status'=>1,
            'data'=>$data
        ],200);
    }
}
