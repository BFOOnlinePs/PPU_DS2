<?php

namespace App\Http\Controllers\project\students\attendance;

use App\Http\Controllers\Controller;
use App\Models\StudentCompany;
use App\Models\StudentAttendance;
use App\Models\StudentReport;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
class AttendanceController extends Controller
{
    public function ajax_company_from_to(Request $request)
    {
        $student_company = null;
        if($request->sc_id == null) {
            $student_company = StudentCompany::where('sc_student_id' , auth()->user()->u_id)
                                            ->where('sc_status' , 1)
                                            ->with('company')
                                            ->pluck('sc_id')
                                            ->toArray();
        }
        else {
            $student_company = StudentCompany::where('sc_id' , $request->sc_id)
                                            ->where('sc_status' , 1)
                                            ->with('company')
                                            ->pluck('sc_id')
                                            ->toArray();
        }
        $student_attendances = StudentAttendance::with('report')->where('sa_student_id', auth()->user()->u_id)
                                                ->whereIn('sa_student_company_id', $student_company)
                                                ->whereBetween(DB::raw('DATE(sa_in_time)'), [$request->from, $request->to]) // Filter by date range (ignoring time)
                                                ->orderBy('created_at', 'desc')
                                                ->get();
        $date_today =  Carbon::now('Asia/Gaza')->toDateString();
        $view = view('project.student.attendance.ajax.attendanceList' , ['date_today' => $date_today , 'student_attendances' => $student_attendances])->render();
        return response()->json(['html' => $view]);
    }
    public function index()
    {
        $student_attendances = [];
        $nowInHebron = Carbon::now('Asia/Gaza');
        $dateToday = $nowInHebron->toDateString();
        $date = StudentAttendance::selectRaw('DATE(sa_in_time) as sa_date')
                                ->where('sa_student_id', auth()->user()->u_id)
                                ->where('sa_in_time' , 'like' , $dateToday . '%')
                                ->first();
        $lastDate = null;
        if(isset($date)) {
            $lastDate = $date->sa_date;
        }
        $student_companies = StudentCompany::where('sc_student_id' , auth()->user()->u_id)
                                            ->where('sc_status' , 1)
                                            ->with('company')
                                            ->get();
        $student_company = StudentCompany::where('sc_student_id' , auth()->user()->u_id)
                            ->where('sc_status' , 1)
                            ->with('company')
                            ->pluck('sc_id')
                            ->toArray();
        $student_attendances = StudentAttendance::where('sa_student_id', auth()->user()->u_id)
                                ->whereIn('sa_student_company_id', $student_company)
                                ->orderBy('created_at', 'desc')
                                ->get();
        return view('project.student.attendance.index' , [
            'student_attendances' => $student_attendances ,
            'date_today' => $dateToday ,
            'last_date' => $lastDate ,
            'student_companies' => $student_companies
        ]);
    }
    public function index_for_specific_company($id)
    {
        $student_companies = StudentCompany::where('sc_id', $id)->where('sc_status' , 1);

        $student_companies = $student_companies->union(
            StudentCompany::where('sc_student_id', auth()->user()->u_id)
                ->where('sc_id', '!=', $id)
                ->where('sc_status' , 1)
        )
        ->get();
        return view('project.student.attendance.index' , [
            'student_companies' => $student_companies ,
            'all_companies' => 'جميع الشركات'
        ]);
    }
    public function create_attendance(Request $request)
    {
        $student_attendance = new StudentAttendance;
        $student_attendance->sa_student_id = auth()->user()->u_id;
        $student_attendance->sa_student_company_id = $request->sa_student_company_id;
        $student_attendance->sa_start_time_latitude = $request->sa_start_time_latitude;
        $student_attendance->sa_start_time_longitude = $request->sa_start_time_longitude;
        $time_now = Carbon::now('Asia/Gaza'); // Time now
        $student_attendance->sa_in_time = $time_now;

        if($student_attendance->save()) {
            $date_today = $time_now->toDateString();
            $not_null_sa_out_time = StudentAttendance::where('sa_student_id', auth()->user()->u_id)
                                                    ->where('sa_in_time' , 'like' , $date_today . '%')
                                                    ->whereNull('sa_out_time')
                                                    ->first();
            $sa_student_company_id = null;
            if($not_null_sa_out_time) {
                $sa_student_company_id = $not_null_sa_out_time->sa_student_company_id;
            }
            $show_in_buttons = true;
            if($sa_student_company_id) {
                $show_in_buttons = false;
            }
            $student_companies = StudentCompany::where('sc_student_id', auth()->user()->u_id)
            ->where('sc_status' , 1)
            ->get();
            $html = view('project.student.company.ajax.companyList' , ['student_companies' => $student_companies , 'show_in_buttons' => $show_in_buttons , 'sa_student_company_id' => $sa_student_company_id])->render();
            return response()->json(['html' => $html]);
        }
    }
    public function create_departure(Request $request)
    {
        $now_Hebron = Carbon::now('Asia/Gaza');
        $date_today = $now_Hebron->toDateString();
        $student_attendance = StudentAttendance::where('sa_student_id', auth()->user()->u_id)
                            ->where('sa_student_company_id' , $request->sa_student_company_id)
                            ->where('sa_in_time' , 'like' , $date_today . '%')
                            ->whereNull('sa_out_time')
                            ->first();
        if($student_attendance) {
            $student_attendance->sa_end_time_longitude = $request->sa_end_time_longitude;
            $student_attendance->sa_end_time_latitude = $request->sa_end_time_latitude;
            $student_attendance->sa_out_time = $now_Hebron;
            if($student_attendance->save()) {
                $sa_student_company_id = null;
                $show_in_buttons = true;
                $student_companies = StudentCompany::where('sc_student_id', auth()->user()->u_id)
                ->where('sc_status' , 1)
                ->get();
                $html = view('project.student.company.ajax.companyList' , ['student_companies' => $student_companies , 'show_in_buttons' => $show_in_buttons , 'sa_student_company_id' => $sa_student_company_id])->render();
                return response()->json(['html' => $html , 'alert_departure' => false]);
            }
        }
        else {
            $sa_student_company_id = null;
            $show_in_buttons = true;
            $student_companies = StudentCompany::where('sc_student_id', auth()->user()->u_id)
            ->where('sc_status' , 1)
            ->get();
            $html = view('project.student.company.ajax.companyList' , ['student_companies' => $student_companies , 'show_in_buttons' => $show_in_buttons , 'sa_student_company_id' => $sa_student_company_id])->render();
            return response()->json(['html' => $html , 'alert_departure' => true]);
        }
    }
}
