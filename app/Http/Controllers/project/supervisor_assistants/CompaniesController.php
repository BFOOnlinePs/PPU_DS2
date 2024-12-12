<?php

namespace App\Http\Controllers\project\supervisor_assistants;

use App\Http\Controllers\Controller;
use App\Models\MajorSupervisor;
use App\Models\StudentCompany;
use App\Models\SupervisorAssistant;
use App\Models\User;
use Illuminate\Http\Request;

class CompaniesController extends Controller
{
    public function index()
    {
        $supervisors_assistant = SupervisorAssistant::where('sa_assistant_id' , auth()->user()->u_id)
                                                    ->pluck('sa_supervisor_id')
                                                    ->toArray();
        $major_supervisor = MajorSupervisor::whereIn('ms_super_id' , $supervisors_assistant)
                                            ->pluck('ms_major_id')
                                            ->toArray();
        $user = User::whereIn('u_major_id' , $major_supervisor)
                    ->where('u_role_id' , 2)
                    ->where('u_status' , 1)
                    ->pluck('u_id')
                    ->toArray();
        $students_companies = StudentCompany::whereIn('sc_student_id', $user)
                                            ->where('sc_status', 1)
                                            ->select('sc_company_id')
                                            ->groupBy('sc_company_id')
                                            ->get();
        return view('project.assistant.companies.index' , ['students_companies' => $students_companies]);
    }
    public function students($id)
    {
        $supervisors_assistant = SupervisorAssistant::where('sa_assistant_id', auth()->user()->u_id)
                                                    ->pluck('sa_supervisor_id')
                                                    ->toArray();
        $major_supervisor = MajorSupervisor::whereIn('ms_super_id' , $supervisors_assistant)
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
        return view('project.assistant.companies.students' , ['students_company' => $students_company]);
    }
}
