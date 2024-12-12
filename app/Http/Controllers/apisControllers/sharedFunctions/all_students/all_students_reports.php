<?php

namespace App\Http\Controllers\apisControllers\sharedFunctions\all_students;

use App\Http\Controllers\Controller;
use App\Models\StudentReport;
use App\Models\User;

class all_students_reports extends Controller
{
    public function getAllStudentsReports()
    {
        $studentsIdList = User::where('u_role_id', 2)->pluck('u_id');
        $allStudentsReports = StudentReport::whereIn('sr_student_id', $studentsIdList)
            ->with('user:u_id,u_username,name')->with('attendance.training.company')
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return response()->json([
            'pagination' => [
                'current_page' => $allStudentsReports->currentPage(),
                'last_page' => $allStudentsReports->lastPage(),
                'per_page' => $allStudentsReports->perPage(),
                'total_items' => $allStudentsReports->total(),
            ],
            'students_reports' => $allStudentsReports->items(),

        ], 200);
    }
}
