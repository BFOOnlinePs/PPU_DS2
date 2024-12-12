<?php

namespace App\Http\Controllers\project\students\company;

use App\Http\Controllers\Controller;
use App\Models\StudentAttendance;
use App\Models\StudentCompany;
use Carbon\Carbon;

use function PHPUnit\Framework\isEmpty;

class CompanyController extends Controller
{
    public function index()
    {
        $student_companies = StudentCompany::where('sc_student_id', auth()->user()->u_id)
                            ->where('sc_status' , 1)
                            ->get();
        $now_Hebron = Carbon::now('Asia/Gaza');
        $date_today = $now_Hebron->toDateString();
        $student_attendance = StudentAttendance::where('sa_student_id', auth()->user()->u_id)
                            ->where('sa_in_time' , 'like' , $date_today . '%')
                            ->whereNull('sa_out_time')
                            ->first();
        $sa_student_company_id = null;
        if($student_attendance) {
            $sa_student_company_id = $student_attendance->sa_student_company_id;
        }
        $show_in_buttons = true;
        if($sa_student_company_id) {
            $show_in_buttons = false;
        }
        return view('project.student.company.index' , ['student_companies' => $student_companies , 'sa_student_company_id' => $sa_student_company_id , 'show_in_buttons' => $show_in_buttons]);
    }
}
