<?php

namespace App\Http\Controllers\project\company_manager\students\attendance;

use App\Http\Controllers\Controller;
use App\Models\Company;
use App\Models\StudentAttendance;
use App\Models\StudentCompany;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\DB;

class AttendanceController extends Controller
{
    public function index($id , $student_company_id)
    {
        $student_attendances = StudentAttendance::where('sa_student_company_id', $student_company_id)
                                ->orderBy('created_at', 'desc')
                                ->get();
        return view('project.company_manager.students.attendance.index' ,
        [
            'student_attendances' => $student_attendances ,
            'id' => $id
        ]);
    }
    public function index_ajax(Request $request)
    {
        $company = Company::where('c_manager_id' , auth()->user()->u_id)->first();
        $student_company = StudentCompany::where('sc_student_id', $request->student_id)
                            ->where('sc_company_id', $company->c_id)
                            ->pluck('sc_id')
                            ->toArray();
        $student_attendances = StudentAttendance::whereIn('sa_student_company_id', $student_company)
                                                ->orderBy('created_at', 'desc')
                                                ->whereBetween(DB::raw('DATE(sa_in_time)'), [$request->from, $request->to]) // Filter by date range (ignoring time)
                                                ->get();
        $view = view('project.company_manager.students.attendance.includes.attendanceList' , ['student_attendances' => $student_attendances])->render();
        return response()->json(['html' => $view]);
    }
}
