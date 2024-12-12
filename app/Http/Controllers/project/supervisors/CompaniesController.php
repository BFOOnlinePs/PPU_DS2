<?php

namespace App\Http\Controllers\project\supervisors;

use App\Http\Controllers\Controller;
use App\Models\MajorSupervisor;
use App\Models\StudentCompany;
use App\Models\User;
use Illuminate\Http\Request;


class CompaniesController extends Controller
{
    public function index()
    {
        $major_supervisor = MajorSupervisor::where('ms_super_id' , auth()->user()->u_id)
                            ->pluck('ms_major_id')
                            ->toArray();
        $user = User::whereIn('u_major_id' , $major_supervisor)
                ->where('u_role_id' , 2)
                ->pluck('u_id')
                ->toArray();
        $students_companies = StudentCompany::whereIn('sc_student_id', $user)
                            ->where('sc_status', 1)
                            ->select('sc_company_id')
                            ->groupBy('sc_company_id')
                            ->get();

        foreach ($students_companies as $key){
            $key->user = User::whereIn('u_id',function ($query) use ($key){
                $query->select('sc_student_id')->from('students_companies')->where('sc_status', 1)->where('sc_company_id' , $key->sc_company_id)->groupBy('sc_student_id');
            })->get();
//                StudentCompany::whereIn('sc_student_id', $user)
//                ->where('sc_status', 1)
//                ->where('sc_company_id' , $key->sc_company_id)
//                ->select('sc_student_id')
//                ->groupBy('sc_student_id')
//                ->get();
        }
        return view('project.supervisor.companies.index' , ['students_companies' => $students_companies]);
    }
    public function students($id)
    {
        $major_supervisor = MajorSupervisor::where('ms_super_id' , auth()->user()->u_id)
                            ->pluck('ms_major_id')
                            ->toArray();
        $user = User::whereIn('u_major_id' , $major_supervisor)
                ->where('u_role_id' , 2)
                ->pluck('u_id')
                ->toArray();
        $students_company = StudentCompany::whereIn('sc_student_id', $user)
                            ->where('sc_status', 1)
                            ->where('sc_company_id' , $id)
                            ->select('sc_student_id')
                            ->groupBy('sc_student_id')
                            ->get();

        return view('project.supervisor.companies.students' , ['students_company' => $students_company]);
    }
}
