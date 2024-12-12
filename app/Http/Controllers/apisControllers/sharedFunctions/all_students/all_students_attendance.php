<?php

namespace App\Http\Controllers\apisControllers\sharedFunctions\all_students;

use App\Http\Controllers\Controller;
use App\Models\StudentAttendance;
use App\Models\User;
use Illuminate\Http\Request;

class all_students_attendance extends Controller
{
    public function getAllStudentsAttendance(Request $request)
    {
        $studentsIdList = User::where('u_role_id', 2)->pluck('u_id');
        $allStudentsAttendance = StudentAttendance::whereIn('sa_student_id', $studentsIdList)
            ->with('user:u_id,u_username,name')
            ->with(['training.company:c_id,c_name'])
            ->orderBy('created_at', 'desc')
            ->paginate(8);

        return response()->json([
            'pagination' => [
                'current_page' => $allStudentsAttendance->currentPage(),
                'last_page' => $allStudentsAttendance->lastPage(),
                'per_page' => $allStudentsAttendance->perPage(),
                'total_items' => $allStudentsAttendance->total(),
            ],
            'students_attendance' => $allStudentsAttendance->items(),

        ], 200);
    }
}
