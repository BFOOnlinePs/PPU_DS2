<?php

namespace App\Http\Controllers\project\communications_manager_with_companies\companies;

use App\Http\Controllers\Controller;
use App\Models\Company;
use App\Models\StudentCompany;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CompaniesController extends Controller
{
    public function index()
    {
        $company = Company::get();
        $students_id = User::where('u_role_id' , 2)
                    ->pluck('u_id')
                    ->toArray();
        $students_companies = StudentCompany::whereIn('sc_student_id', $students_id)
                ->select('sc_company_id')
                ->groupBy('sc_company_id')
                ->get();
        return view('project.communications_manager_with_companies.companies.index' , ['students_companies' => $students_companies,'company'=>$company]);
    }
    public function students($id)
    {
        $user = User::where('u_role_id' , 2)
                ->pluck('u_id')
                ->toArray();
        $students_company = StudentCompany::whereIn('sc_student_id', $user)
                            ->where('sc_company_id' , $id)
                            ->select('sc_student_id' , DB::raw('MAX(sc_status) as sc_status'))
                            ->groupBy('sc_student_id')
                            ->get();
        return view('project.communications_manager_with_companies.companies.students' , ['students_company' => $students_company]);
    }

    public function communications_manager_with_companies_table_ajax(Request $request){
        $data = null;
        if ($request->filled('trainees')){
            if ($request->trainees == 'yes_trainees'){
                $data = StudentCompany::whereIn('sc_student_id',function ($query) use ($request){
                    $query->select('u_id')->from('users')->where('u_role_id' , 2);
                })
                    ->when($request->filled('company_id'), function ($query) use ($request) {
                        $query->whereHas('company', function ($subQuery) use ($request) {
                            $subQuery->where('c_id', $request->company_id);
                        });
                    })
                    ->when($request->filled('company_status'),function ($query) use ($request){
                        $query->whereIn('sc_company_id',function ($query) use ($request){
                            $query->select('c_id')->from('companies')->where('c_status',$request->company_status);
                        });
                    })
                    ->when($request->filled('capacity') && $request->capacity == 1, function ($query) use ($request) {
                        $query->whereRaw('(SELECT (c_capacity - COUNT(sc_student_id)) FROM students_companies WHERE sc_company_id = companies.c_id) > 0');
                    })
                    ->when($request->filled('capacity') && $request->capacity == 1, function ($query) {
                        $query->select('sc_company_id')
                            ->selectRaw('(companies.c_capacity - COUNT(sc_student_id)) as remaining_capacity')
                            ->from('students_companies') // Corrected table name here
                            ->leftJoin('companies', 'students_companies.sc_company_id', '=', 'companies.c_id')
                            ->groupBy('sc_company_id', 'companies.c_capacity')
                            ->havingRaw('(companies.c_capacity - COUNT(sc_student_id)) > 0');
                    })
                    ->when($request->filled('capacity') && $request->capacity == 0, function ($query) {
                        $query->select('sc_company_id')
                            ->selectRaw('(companies.c_capacity - COUNT(sc_student_id)) as remaining_capacity')
                            ->from('students_companies') // Corrected table name here
                            ->leftJoin('companies', 'students_companies.sc_company_id', '=', 'companies.c_id')
                            ->groupBy('sc_company_id', 'companies.c_capacity')
                            ->havingRaw('(companies.c_capacity - COUNT(sc_student_id)) < 0');
                    })
                    ->get();
            }
            else{
                $data = Company::whereNotIn('c_id', function ($query) use ($request){
                    $query->select('sc_company_id')->from('students_companies')->whereIn('sc_student_id', function ($subQuery) use ($request) {
                        $subQuery->select('u_id')->from('users')->where('u_role_id', 2);
                    });
                })
                    ->when($request->filled('company_id'), function ($query) use ($request) {
                        $query->where('c_id', $request->company_id);
                    })
                    ->when($request->filled('company_status'),function ($query) use ($request){
                        $query->where('c_status', $request->company_status);
                    })
                    ->when($request->filled('capacity') && $request->capacity == 1, function ($query) {
                        $query->whereRaw('(c_capacity - (SELECT COUNT(sc_student_id) FROM students_companies WHERE sc_company_id = companies.c_id)) > 0');
                    })
                    ->when($request->filled('capacity') && $request->capacity == 0, function ($query) {
                        $query->whereRaw('(c_capacity - (SELECT COUNT(sc_student_id) FROM students_companies WHERE sc_company_id = companies.c_id)) <= 0');
                    })
                    ->get();
            }
        }

        foreach ($data as $key) {
            $key->company = Company::where('c_id',$key->sc_company_id)->first();
            $key->users = User::whereIn('u_id', function ($query) use ($key) {
                $query->select('sc_student_id')
                    ->from('students_companies')
                    ->where('sc_status', 1)
                    ->where('sc_company_id', $key->sc_company_id)
                    ->groupBy('sc_student_id');
            })->get();

            $key->count =  User::whereIn('u_id', function ($query) use ($key) {
                $query->select('sc_student_id')
                    ->from('students_companies')
                    ->where('sc_status', 1)
                    ->where('sc_company_id', $key->sc_company_id)
                    ->groupBy('sc_student_id');
            })->count();

            $key->student = User::where('u_id',$key->sc_student_id)->first();
        }
        return response()->json([
            'success' => 'true',
            'view' => view('project.communications_manager_with_companies.companies.ajax.communications_manager_with_companies_table_ajax',['data' => $data])->render()
        ]);
    }
}
