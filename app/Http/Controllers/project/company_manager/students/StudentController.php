<?php

namespace App\Http\Controllers\project\company_manager\students;

use App\Http\Controllers\Controller;
use App\Models\Company;
use App\Models\CompanyBranch;
use App\Models\CompanyDepartment;
use App\Models\Course;
use App\Models\Registration;
use App\Models\StudentCompany;
use Illuminate\Http\Request;

class StudentController extends Controller
{
    public function index()
    {
        $company = Company::where('c_manager_id', '=' , auth()->user()->u_id)->first();
        $students_company = StudentCompany::where('sc_company_id', $company->c_id)->get();
        foreach($students_company as $key) {
            $key->branch = CompanyBranch::find($key->sc_branch_id);
            $key->department = CompanyDepartment::find($key->sc_department_id);
            $registration = Registration::find($key->sc_registration_id);
            $key->course = Course::find($registration->r_course_id);
        }
        return view('project.company_manager.students.index' , [
            'students_company' => $students_company
        ]);
    }
}
