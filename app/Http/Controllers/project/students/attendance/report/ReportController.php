<?php

namespace App\Http\Controllers\project\students\attendance\report;

use App\Http\Controllers\Controller;
use App\Models\StudentAttendance;
use Illuminate\Http\Request;
use App\Models\StudentReport;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;

class ReportController extends Controller
{
    public function edit($sa_id)
    {
        $student_report = StudentReport::where('sr_student_attendance_id', $sa_id)->first();
        return view('project.student.report.edit' , ['sa_id' => $sa_id , 'student_report' => $student_report]);
    }
    public function submit(Request $request)
    {
        $student_report = StudentReport::where('sr_student_attendance_id', $request->sa_id)->first();
        if(!isset($student_report)) {
            $student_report = new StudentReport;
        }
        $student_report->sr_student_attendance_id = $request->sa_id;
        $student_report->sr_student_id = auth()->user()->u_id;
        $student_attendance = StudentAttendance::find($request->sa_id);
        $date_today =  Carbon::now('Asia/Gaza')->toDateString();
        $sa_in_time = $student_attendance->sa_in_time;
        if(date("Y-m-d", strtotime($sa_in_time)) == $date_today) {
            $student_report->sr_report_text = $request->sr_report_text;
            $student_report->sr_submit_latitude = $request->latitude;
            $student_report->sr_submit_longitude = $request->longitude;

            if($request->save_file == 'true') {
                if ($request->hasFile('file')) {
                    $file = $request->file('file');
                    $extension = $file->getClientOriginalExtension();
                    $filename = time() . '_' . uniqid() . '.' .  $extension;
                    $file->storeAs('student_reports', $filename, 'public');
                    $student_report->sr_attached_file = $filename;
                }
            }
            else {
                Storage::delete('public/student_reports/' . $student_report->sr_attached_file);
                $student_report->sr_attached_file = null;
            }
            if($student_report->save()) {
                return redirect()->back()->with('success', 'تم تسليم التقرير بنجاح');
            }
        }
        else {
            return redirect()->back()->with('danger', 'عذرًا لا يُمكنك تسليم التقرير بسبب انتهاء الوقت');
        }
    }
    public function upload(Request $request)
    {
        if ($request->hasFile('input-file')) {
            $file = $request->file('input-file');
            $originalFileName = $file->getClientOriginalName();
            return response()->json(['newHref' => $originalFileName]);
        }
    }
}
