<?php

namespace App\Http\Controllers\apisControllers\program_coordinator\students;

use App\Http\Controllers\Controller;
use App\Models\SystemSetting;
use App\Models\User;
use Illuminate\Http\Request;

class ProgramCoordinatorStudentsController extends Controller
{
    // for program coordinator
    // to get all students
    // it has search on student name and username(university id)
    public function getAllStudentsDependOnMajor(Request $request)
    {
        $studentsList = User::where('u_role_id', 2);

        $student_search = $request->input('search');
        if (!empty($student_search)) {
            $studentsList->where(function ($query) use ($student_search) {
                $query->where('u_username', 'like', '%' . $student_search . '%')
                    ->orWhere('name', 'like', '%' . $student_search . '%');
            });
        }

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

    // for program coordinator
    // to get students currently enrolled in training
    public function getStudentsRegisteredForTraining(Request $request)
    {
        $system_settings = SystemSetting::first();
        $current_semester = $system_settings->ss_semester_type;
        $current_year = $system_settings->ss_year;
        $studentsList = User::where('u_role_id', 2)
            ->whereHas('studentCompanies.registrations', function ($query) use ($current_year, $current_semester) {
                $query->where('r_year', $current_year);
                $query->where('r_semester', $current_semester);
            });



        $student_search = $request->input('search');
        if (!empty($student_search)) {
            $studentsList->where(function ($query) use ($student_search) {
                $query->where('u_username', 'like', '%' . $student_search . '%')
                    ->orWhere('name', 'like', '%' . $student_search . '%');
            });
        }

        // if (request()->has('major_id')) {
        //     $majorId = $request->input('major_id');
        //     $studentsList->where('u_major_id', $majorId);
        // }

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
}
