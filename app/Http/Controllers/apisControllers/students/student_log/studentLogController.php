<?php

namespace App\Http\Controllers\apisControllers\students\student_log;

use App\Http\Controllers\Controller;
use App\Models\StudentAttendance;
use App\Models\StudentReport;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class studentLogController extends Controller
{
    // to get the student attendance in all trainings
    public function getAllStudentAttendanceLog(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'sa_student_id' => 'required'
        ], [
            'sa_student_id.required' => trans('messages.student_id_required'),
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => $validator->errors()->first(),
            ], 422);
        };


        $allStudentAttendanceLog = StudentAttendance::where('sa_student_id', $request->input('sa_student_id'))
            ->with('training.company')
            ->orderBy('created_at', 'desc')
            ->paginate(6); // number of items each page
        // ->get();

        if (!$allStudentAttendanceLog) {
            return response()->json([
                'status' => false,
                'message' => trans('messages.no_attendance_yet'),
            ]);
        }

        return response()->json([
            'status' => true,
            'pagination' => [
                'current_page' => $allStudentAttendanceLog->currentPage(),
                'last_page' => $allStudentAttendanceLog->lastPage(),
                'per_page' => $allStudentAttendanceLog->perPage(),
                'total_items' => $allStudentAttendanceLog->total(),
            ],
            'data' => $allStudentAttendanceLog->items(),

        ], 200);
    }


    public function getAllStudentReportsLog(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'sr_student_id' => 'required'
        ], [
            'sr_student_id.required' => trans('messages.student_id_required'),
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => $validator->errors()->first(),
            ], 200);
        };


        $allStudentReportsLog = StudentReport::where('sr_student_id', $request->input('sr_student_id'))
        ->with('attendance.training.company')
        ->orderBy('created_at', 'desc')
        ->paginate(5); // number of items each page
        // ->get();

        if (!$allStudentReportsLog) {
            return response()->json([
                'status' => false,
                'message' => trans('messages.no_submitted_reports_yet'),
            ]);
        }

        return response()->json([
            'status' => true,
            'data' => $allStudentReportsLog->items(),
            'pagination' => [
                'current_page' => $allStudentReportsLog->currentPage(),
                'last_page' => $allStudentReportsLog->lastPage(),
                'per_page' => $allStudentReportsLog->perPage(),
                'total_items' => $allStudentReportsLog->total(),
            ],
        ], 200);
    }
}
