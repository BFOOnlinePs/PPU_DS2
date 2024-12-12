<?php

namespace App\Http\Controllers\project\company_manager\student_nominations;

use App\Http\Controllers\Controller;
use App\Models\Company;
use App\Models\StudentCompanyNominationModel;
use App\Models\User;
use Illuminate\Http\Request;

class StudentNominationsController extends Controller
{
    public function index(){
        $data = StudentCompanyNominationModel::where('scn_company_id',auth()->user()->u_id)->get();
        return view('project.company_manager.student_nominations.index',['data'=>$data]);
    }

    public function student_nomination_table(Request $request){
        $company_id = Company::where('c_manager_id',auth()->user()->u_id)->first();
        $data = StudentCompanyNominationModel::where('scn_company_id',$company_id->c_id)->get();
        foreach ($data as $key){
            $key->student = User::where('u_id',$key->scn_student_id)->first();
        }
        return response()->json([
            'success' => 'true',
            'view' => view('project.company_manager.student_nominations.ajax.student_nomination_list',['data'=>$data])->render()
        ]);
    }
}
