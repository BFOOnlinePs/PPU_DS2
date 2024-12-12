<?php

namespace App\Http\Controllers\apisControllers\training_supervisor;

use App\Http\Controllers\Controller;
use App\Models\Registration;
use App\Models\StudentAttendance;
use App\Models\StudentReport;


class TrainingSupervisorStudentsLogController extends Controller
{
    public function getTrainingSupervisorAttendanceLog()
    {
        $trainingSupervisorId = auth()->user()->u_id;
        $studentsIdsList = Registration::where('supervisor_id', $trainingSupervisorId)->pluck('r_student_id');
        $studentsAttendance = StudentAttendance::whereIn('sa_student_id', $studentsIdsList)
            ->with([
                'user:u_id,u_username,name',
                'training:sc_id,sc_company_id,sc_status',
                'training.company:c_id,c_name,c_english_name',
            ])
            // ->with('user:u_id,u_username,name')
            // ->with('training:sc_id,sc_company_id,sc_status')
            // ->with('training.company:c_name,c_english_name')
            ->orderBy('created_at', 'desc')
            ->paginate(8);

        return response()->json([
            'pagination' => [
                'current_page' => $studentsAttendance->currentPage(),
                'last_page' => $studentsAttendance->lastPage(),
                'per_page' => $studentsAttendance->perPage(),
                'total_items' => $studentsAttendance->total(),
            ],
            'students_attendance' => $studentsAttendance->items(),

        ], 200);
    }

    public function getTrainingSupervisorReportLog()
    {
        $trainingSupervisorId = auth()->user()->u_id;
        $studentsIdsList = Registration::where('supervisor_id', $trainingSupervisorId)->pluck('r_student_id');
        $StudentsReports = StudentReport::whereIn('sr_student_id', $studentsIdsList)
            ->with([
                'user:u_id,u_username,name',
                'attendance:sa_id,sa_student_company_id,sa_in_time',
                'attendance.training:sc_id,sc_company_id,sc_status',
                'attendance.training.company:c_id,c_name,c_english_name',
            ])
            // ->with('user')
            // ->with('attendance.training.company')
            ->orderBy('created_at', 'desc')
            ->paginate(8);

        return response()->json([
            'pagination' => [
                'current_page' => $StudentsReports->currentPage(),
                'last_page' => $StudentsReports->lastPage(),
                'per_page' => $StudentsReports->perPage(),
                'total_items' => $StudentsReports->total(),
            ],
            'students_reports' => $StudentsReports->items(),
        ], 200);
    }
}
