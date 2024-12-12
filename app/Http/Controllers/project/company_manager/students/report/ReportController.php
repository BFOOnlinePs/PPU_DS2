<?php

namespace App\Http\Controllers\project\company_manager\students\report;

use App\Http\Controllers\Controller;
use App\Models\StudentCompany;
use App\Models\StudentReport;
use App\Models\Company;
use App\Models\StudentAttendance;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    public function index($id , $student_company_id)
    {
        $student_company = StudentCompany::where('sc_id' , $student_company_id)
                                        ->where('sc_status', 1)
                                        ->pluck('sc_id')
                                        ->toArray();
        $student_attendance = StudentAttendance::whereIn('sa_student_company_id', $student_company)
                                                ->pluck('sa_id')
                                                ->toArray();
        $reports = StudentReport::where('sr_student_id', $id)
                                    ->whereIn('sr_student_attendance_id', $student_attendance)
                                    ->get();
        return view('project.company_manager.students.reports.index' , [
            'reports' => $reports
        ]);
    }
    public function addNotes(Request $request)
    {
        $student_report = StudentReport::find($request->sr_id);
        $student_report->sr_notes_company = $request->sr_notes_company;
        if($student_report->save()){
            return response()->json(['html' => 's']);
        }
    }
    public function showNotes(Request $request)
    {
        $student_report = StudentReport::find($request->sr_id);
        return response()->json(['sr_notes_company' => $student_report->sr_notes_company]);
    }
    public function showReport(Request $request)
    {
        $student_report = StudentReport::find($request->report_sr_id);
        return response()->json(['sr_report_text' => $student_report->sr_report_text , 'sr_attached_file' => $student_report->sr_attached_file]);
    }
}
