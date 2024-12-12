<?php

namespace App\Http\Controllers\apisControllers\supervisors;

use App\Http\Controllers\Controller;
use App\Models\Major;
use App\Models\MajorSupervisor;
use App\Models\StudentAttendance;
use App\Models\StudentReport;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Validator;

class SupervisorStudentsController extends Controller
{
    // to get the students of a major that the current supervisor is supervised
    // all students when no major_id sent
    // students of a specific major when major_id sent
    public function getSupervisorStudentsDependOnMajor(Request $request)
    {
        $supervisorId = auth()->user()->u_id;
        $supervisorMajorsIdList = MajorSupervisor::where('ms_super_id', $supervisorId)->pluck('ms_major_id');
        $studentsList = User::where('u_role_id', 2)->whereIn('u_major_id', $supervisorMajorsIdList);

        // search
        $requestSearch = $request->input('search');
        if (!empty($requestSearch)) {
            $studentsList->where(function ($query) use ($requestSearch) {
                $query->where('u_username', 'like', '%' . $requestSearch . '%')
                    ->orWhere('name', 'like', '%' . $requestSearch . '%');
            });
        }
        // $studentsList->when(!empty($requestSearch), function ($query) use ($requestSearch) {
        //     $query->where('u_username', 'like', '%' . $requestSearch . '%')
        //         ->orWhere('name', 'like', '%' . $requestSearch . '%');
        // });

        if (request()->has('major_id')) {
            $majorId = $request->input('major_id');
            $studentsList->where('u_major_id', $majorId);
        }

        $studentsList = $studentsList->with('major')->paginate(8);
        // $studentsList = $studentsList->with('major');

        return response()->json([
            'status' => true,
            'pagination' => [
                'current_page' => $studentsList->currentPage(),
                'last_page' => $studentsList->lastPage(),
                'per_page' => $studentsList->perPage(),
                'total_items' => $studentsList->total(),
            ],
            'students' => $studentsList->items(),

        ]);
    }

    // attendance log of all supervisors students
    public function getAllSupervisorStudentsAttendanceLog()
    {
        $supervisorId = auth()->user()->u_id;
        $supervisorMajorsIdList = MajorSupervisor::where('ms_super_id', $supervisorId)->pluck('ms_major_id');
        $studentsIdList = User::where('u_role_id', 2)->whereIn('u_major_id', $supervisorMajorsIdList)->pluck('u_id');
        $allStudentsAttendance = StudentAttendance::whereIn('sa_student_id', $studentsIdList)
            ->with('user')
            ->with('training.company')
            ->orderBy('created_at', 'desc')->get();

        $perPage = 8;
        $currentPage = LengthAwarePaginator::resolveCurrentPage();
        $currentPageItems = $allStudentsAttendance->forPage($currentPage, $perPage);
        $paginatedAllStudentsAttendance = new LengthAwarePaginator(
            $currentPageItems->values(),
            $allStudentsAttendance->count(),
            $perPage,
            $currentPage
        );

        return response()->json([
            'pagination' => [
                'current_page' => $paginatedAllStudentsAttendance->currentPage(),
                'last_page' => $paginatedAllStudentsAttendance->lastPage(),
                'per_page' => $paginatedAllStudentsAttendance->perPage(),
                'total_items' => $paginatedAllStudentsAttendance->total(),
            ],
            'students_attendance' => $paginatedAllStudentsAttendance->items(),

        ], 200);
    }

    // reports log of all supervisors students
    public function getAllSupervisorStudentsReportsLog()
    {
        $supervisorId = auth()->user()->u_id;
        $supervisorMajorsIdList = MajorSupervisor::where('ms_super_id', $supervisorId)->pluck('ms_major_id');
        $studentsIdList = User::where('u_role_id', 2)->whereIn('u_major_id', $supervisorMajorsIdList)->pluck('u_id');
        $allStudentsReports = StudentReport::whereIn('sr_student_id', $studentsIdList)
            ->with('user')
            ->with('attendance.training.company')
            ->orderBy('created_at', 'desc')
            ->get();

        $perPage = 10;
        $currentPage = LengthAwarePaginator::resolveCurrentPage();
        $currentPageItems = $allStudentsReports->forPage($currentPage, $perPage);
        $paginatedAllStudentsReports = new LengthAwarePaginator(
            $currentPageItems->values(),
            $allStudentsReports->count(),
            $perPage,
            $currentPage
        );

        return response()->json([
            'pagination' => [
                'current_page' => $paginatedAllStudentsReports->currentPage(),
                'last_page' => $paginatedAllStudentsReports->lastPage(),
                'per_page' => $paginatedAllStudentsReports->perPage(),
                'total_items' => $paginatedAllStudentsReports->total(),
            ],
            'students_reports' => $paginatedAllStudentsReports->items(),

        ], 200);
    }

    // send student id to get his info
    public function getStudentInfo(Request $request)
    {
        $validator = Validator::make(
            $request->all(),
            ['student_id' => 'required'],
            ['student_id.required' => trans('messages.student_id_required')]
        );

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => $validator->errors()->first(),
            ]);
        }

        $student_id = $request->input('student_id');
        $student_info = User::where('u_role_id', 2)->where('u_id', $student_id)->with('userCity:id,city_name_ar,city_name_en')->first();

        if (!$student_info) {
            return response()->json([
                'status' => false,
                'message' => trans('messages.student_id_not_exists')
            ]);
        }

        $major_name = Major::where('m_id', $student_info->u_major_id)->pluck('m_name')->first();
        $student_info['major_name'] = $major_name;

        return response()->json([
            'status' => true,
            'student_info' => $student_info,
        ]);
    }
}
