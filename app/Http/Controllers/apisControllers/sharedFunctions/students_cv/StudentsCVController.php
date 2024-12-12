<?php

namespace App\Http\Controllers\apisControllers\sharedFunctions\students_cv;

use App\Http\Controllers\Controller;
use App\Models\MajorSupervisor;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class StudentsCVController extends Controller
{
    // to get the students that has cv
    // if supervisor: get the students of a major that the current supervisor is supervised
    public function getStudentsForCVs(Request $request)
    {
        $currentUserId = auth()->user()->u_id;
        $currentUser = User::where('u_id', $currentUserId)->first();

        $studentsList = User::where('u_role_id', 2)
            ->whereNotNull('u_cv');


        // if supervisor
        if ($currentUser->u_role_id == 3) {
            $supervisorMajorsIdList = MajorSupervisor::where('ms_super_id', $currentUserId)->pluck('ms_major_id');
            $studentsList = $studentsList->whereIn('u_major_id', $supervisorMajorsIdList);
        }

        // search
        $requestSearch = $request->input('search');
        if (!empty($requestSearch)) {
            $studentsList->where(function ($query) use ($requestSearch) {
                $query->where('u_username', 'like', '%' . $requestSearch . '%')
                    ->orWhere('name', 'like', '%' . $requestSearch . '%');
            });
        }

        $studentsList = $studentsList->select('u_id', 'u_username', 'name', 'u_cv', 'u_cv_status')
            ->orderBy('u_cv_updated_at', 'desc')
            ->paginate(8);
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

    public function changeStudentCVStatus(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'student_id' => 'required|exists:users,u_id',
            'cv_status' => 'required|in:0,1'
        ]);

        $student = User::where('u_id', $request->student_id)->first();

        $student->update([
            'u_cv_status' => $request->input('cv_status'),

        ]);

        return response()->json([
            'status' => true,
            'message' => trans('messages.change_student_cv_status'),
            'student' => $student,
        ]);
    }
}
