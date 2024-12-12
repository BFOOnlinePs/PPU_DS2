<?php

namespace App\Http\Controllers\apisControllers\students;

use App\Http\Controllers\Controller;
use App\Models\StudentAttendance;
use App\Models\StudentReport;
use Carbon\Carbon;
use Illuminate\Http\Request;

class StudentReportAttendanceController extends Controller
{
    // reports depend on attendance
    public function getStudentReportsWithAttendance(Request $request)
    {
        $student_company_id = $request->input('student_company_id');
        $attendance = StudentAttendance::where('sa_student_company_id', $student_company_id)
            ->whereDate('sa_in_time', Carbon::now('Asia/Gaza')->toDateString())
            ->whereDoesntHave('report')
            ->get();
        // same as:
        // ->where('sa_in_time', '>=', Carbon::now('Asia/Gaza')->toDateString())
        // for more than one
        // ->where('sa_in_time', '>=', Carbon::now('Asia/Gaza')->subDays(2))

        // $reports = StudentReport::whereHas('attendance', function ($query) use ($student_company_id) {
        //     $query->where('sa_student_company_id', $student_company_id);
        // })->with('attendance:sa_id,sa_in_time')->get(); // to return the date and print the day of attendance not report

        $reports = StudentReport::whereHas('attendance', function ($query) use ($student_company_id) {
            $query->where('sa_student_company_id', $student_company_id);
        })->with(['reportAttendance' => function ($query) {
            $query->select('sa_id', 'sa_in_time');
        }])->orderBy('created_at', 'desc')
            ->paginate(7);

        $today = Carbon::now('Asia/Gaza')->toDateString();

        $reports->each(function ($report) use ($today) {
            $attendance = $report->reportAttendance;

            if ($attendance) {
                $attendanceDate = Carbon::parse($attendance->sa_in_time)->toDateString();
                $attendance->is_same_day = $attendanceDate === $today;
            }
        });

        return response()->json([
            'status' => true,
            'attendance' => $attendance,
            'reports' => $reports->items(),
            'pagination' => [
                'current_page' => $reports->currentPage(),
                'last_page' => $reports->lastPage(),
                'per_page' => $reports->perPage(),
                'total_items' => $reports->total(),
            ],
        ], 200);
    }
}
