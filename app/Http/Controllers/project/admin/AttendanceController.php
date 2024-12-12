<?php

namespace App\Http\Controllers\project\admin;

use App\Http\Controllers\Controller;
use App\Models\Company;
use App\Models\Registration;
use App\Models\StudentAttendance;
use App\Models\StudentCompany;
use App\Models\StudentReport;
use App\Models\SystemSetting;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AttendanceController extends Controller
{
    public function index()
    {
        $companies = Company::get();
        $system_settings = SystemSetting::first();
        return view('project.admin.attendance.index' , [
            'companies' => $companies ,
            'system_settings' => $system_settings
        ]);
    }
    public function fillter(Request $request)
    {
        $users = User::where('name' , 'like' , '%' . $request->name . '%')
        ->where('u_role_id', 2)
        ->pluck('u_id')
        ->toArray();
        $registration = Registration::whereIn('r_student_id' , $users)
        ->where('r_semester' , $request->semester)
        ->where('r_year' , $request->year)
        ->pluck('r_id')
        ->toArray();
        if($request->year == null) {
            $registration = Registration::whereIn('r_student_id' , $users)
            ->where('r_semester' , $request->semester)
            ->pluck('r_id')
            ->toArray();
        }
        $students_companies = StudentCompany::where('sc_company_id' , $request->company)
        ->whereIn('sc_student_id' , $users)
        ->whereIn('sc_registration_id' , $registration)
        ->pluck('sc_id')
        ->toArray();
        if($request->company == null) {
            $students_companies = StudentCompany::whereIn('sc_student_id' , $users)
            ->whereIn('sc_registration_id' , $registration)
            ->pluck('sc_id')
            ->toArray();
        }
        $data = StudentAttendance::whereIn('sa_student_id' , $users)
        ->whereIn('sa_student_company_id' , $students_companies)
        ->orderBy('created_at', 'desc')
        ->whereBetween(DB::raw('DATE(sa_in_time)'), [$request->from, $request->to]) // Filter by date range (ignoring time)
        ->get();
        foreach ($data as $key) {
            $key->student = User::find($key->sa_student_id);
            $student_company = StudentCompany::find($key->sa_student_company_id);
            $key->company = Company::find($student_company->sc_company_id);
        }
        $html = view('project.admin.attendance.ajax.attendanceList' , [
            'data' => $data
        ])->render();
        return response()->json([
            'html' => $html
        ]);
    }
    public function details(Request $request)
    {
        $data = StudentAttendance::find($request->sa_id);
        $data->student = User::find($data->sa_student_id);
        $student_company = StudentCompany::find($data->sa_student_company_id);
        $data->company = Company::find($student_company->sc_company_id);
        $report = StudentReport::where('sr_student_attendance_id' , $data->sa_id)
        ->first();
        if($report != null) {
            $data->report_text = $report->sr_report_text;
            $data->attached_file = $report->sr_attached_file;
            $data->notes_supervisor_modal = $report->sr_notes;
            $data->notes_company_modal = $report->sr_notes_company;
        }
        return response()->json([
            'data' => $data
        ]);
    }
}
