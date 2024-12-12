<?php

namespace App\Http\Controllers\project\communications_manager_with_companies\follow_up_record;

use App\Http\Controllers\Controller;
use App\Models\CitiesModel;
use App\Models\CompaniesCategory;
use App\Models\Company;
use App\Models\CompanyBranch;
use App\Models\Currency;
use App\Models\Major;
use App\Models\Payment;
use App\Models\Registration;
use App\Models\StudentCompany;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class FollowUpRecordController extends Controller
{
    public function index(){
        $company_category = CompaniesCategory::get();
        $users = User::get();
        $cities = CitiesModel::get();

        $registration = Registration::get();
        $students = User::where('u_role_id',2)->get();

        $majors = Major::get();
        return view('project.communications_manager_with_companies.follow_up_record.index',['company_category'=>$company_category,'users'=>$users,'cities'=>$cities,'registration'=>$registration,'students'=>$students,'majors'=>$majors]);
    }

    public function company_table_ajax(Request $request){
        $majors = Major::get();
        $data = Company::with('manager','companyCategories')->where(function ($query) use ($request){
            $query->where('c_name','like','%'.$request->company_search.'%');
        })
            ->when($request->filled('company_status'),function ($query) use ($request){
                $query->where('c_status',$request->company_status);
            })
            ->when($request->filled('capacity') && $request->capacity == 1, function ($query) {
                $query->whereRaw('(c_capacity - (SELECT COUNT(sc_student_id) FROM students_companies WHERE sc_company_id = companies.c_id)) > 0');
            })
            ->when($request->filled('capacity') && $request->capacity == 0, function ($query) {
                $query->whereRaw('(c_capacity - (SELECT COUNT(sc_student_id) FROM students_companies WHERE sc_company_id = companies.c_id)) <= 0');
            })
            ->get();
        foreach ($data as $key){
            $key->student_company = User::whereIn('u_id',function ($query) use ($key){
                $query->select('sc_student_id')->from('students_companies')->where('sc_company_id',$key->c_id);
            })->get();
            $key->student_company_count = count($key->student_company);
            $key->student_company_nomination = User::whereIn('u_id',function ($query) use ($key){
                $query->select('scn_student_id')->from('student_company_nomination')->where('scn_company_id',$key->c_id);
            })->get();
            $key->company_branches = CompanyBranch::where('b_company_id',$key->c_id)->first();
        }
        return response()->json([
            'success' => 'true',
            'view' => view('project.communications_manager_with_companies.follow_up_record.ajax.company_table',['data'=>$data,'majors'=>$majors])->render()
        ]);
    }

    public function update_company_information(Request $request){
        if ($request->key == 'b_phone1'){
            $data = CompanyBranch::where('b_company_id',$request->id)->first();
            $data->{$request->key} = $request->value;
        }
        else if ($request->key == 'b_address'){
            $data = CompanyBranch::where('b_company_id',$request->id)->first();
            $data->{$request->key} = $request->value;
        }
        else{
            $data = Company::where('c_id',$request->id)->first();
            $data->{$request->key} = $request->value;
            if ($data->save()){
                return response()->json([
                    'success' => 'true'
                ]);
            }
        }
    }

    public function list_contact_company(Request $request){
        $data = User::where('u_company_id',$request->id)->get();
        return response()->json([
            'success' => 'true',
            'view' => view('project.communications_manager_with_companies.follow_up_record.ajax.contact_company_table',['data'=>$data])->render()
        ]);
    }

    public function create_contact_company(Request $request){
        $data = new User();
        $data->u_username = $request->u_username;
        $data->name = $request->name;
        $data->email = $request->email;
        $data->password = Hash::make($request->password);
        $data->u_phone1 = $request->u_phone1;
        $data->u_phone2 = $request->u_phone2;
        $data->u_role_id = 6;
        $data->u_company_id = $request->c_id;
        if ($data->save()){
            return response()->json([
                'success' => 'true',
            ]);
        }
    }

    public function delete_contact_company(Request $request){
        $data = User::where('u_id',$request->id)->first();
        if ($data->delete()){
            return response()->json([
                'success' => 'true'
            ]);
        }
    }

    public function check_email_found(Request $request){
        $data = User::where('email',$request->email)->first();
        if (empty($data)){
            return response()->json([
                'success' => 'true',
                'status' => 'not_found',
                'message' => 'هذا الايميل غير مستخدم من قبل'
            ]);
        }
        else if (!empty($data)){
            return response()->json([
                'success' => 'true',
                'status' => 'found',
                'message' => 'هذا الايميل مستخدم من قبل'
            ]);
        }
    }

    public function list_branches(Request $request){
        $data = CompanyBranch::where('b_company_id',$request->c_id)->get();
        foreach ($data as $key){
            $key->company = Company::where('c_id',$key->b_company_id)->first();
            $key->user = User::where('u_id',$key->b_manager_id)->first();
        }
        return response()->json([
            'success' => 'true',
            'view' => view('project.communications_manager_with_companies.follow_up_record.ajax.list_branches',['data'=>$data])->render()
        ]);
    }

    public function create_branches_ajax(Request $request){
        $data = new CompanyBranch();
        $data->b_company_id = $request->b_company_id;
        $data->b_address = $request->branch_address;
        $data->b_phone1 = $request->branch_phone1;
        $data->b_phone2 = $request->branch_phone2;
        $data->b_main_branch = 1;
        $data->b_manager_id = $request->branch_manager;
        $data->b_city_id = $request->branch_city;
        if ($data->save()){
            return response()->json([
                'success' => 'true'
            ]);
        }
    }

    public function list_student_company_ajax(Request $request){
        $data = StudentCompany::where('sc_company_id',$request->sc_company_id)->get();
        foreach ($data as $key){
            $key->student = User::where('u_id',$key->sc_student_id)->first();
            $key->company_branches = CompanyBranch::where('b_id',$key->sc_branch_id)->first();
        }
        return response()->json([
            'success' => 'true',
            'view' => view('project.communications_manager_with_companies.follow_up_record.ajax.list_student_company_table',['data'=>$data])->render()
        ]);
    }

    public function payment_table_ajax(Request $request){
        $data = Payment::where('p_company_id',$request->company_id)->get();
        foreach ($data as $key){
            $key->student = User::where('u_id',$key->p_student_id)->first();
            $key->inserted_by = User::where('u_id',$key->p_inserted_by_id)->first();
            $key->currency = Currency::where('c_id',$key->p_currency_id)->first();
        }
        return response()->json([
            'success' => 'true',
            'view' => view('project.communications_manager_with_companies.follow_up_record.ajax.payment_table',['data'=>$data])->render()
        ]);
    }

}
