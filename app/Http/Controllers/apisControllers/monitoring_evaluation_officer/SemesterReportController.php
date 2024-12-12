<?php

namespace App\Http\Controllers\apisControllers\monitoring_evaluation_officer;

use App\Http\Controllers\Controller;
use App\Models\Major;
use App\Models\Registration;
use App\Models\SemesterCourse;
use App\Models\StudentCompany;
use App\Models\SystemSetting;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;

class SemesterReportController extends Controller
{
    // filters as query params
    public function getSemesterReport(Request $request)
    {
        if ($request->has('year')) {
            $year = $request->input('year');
        } else {
            $year = SystemSetting::first()->ss_year;
        }

        if ($request->has('semester')) {
            $semester = $request->input('semester');
        } else {
            $semester = SystemSetting::first()->ss_semester_type;
        }


        // 1. Total number of registered students
        // affected by: year, semester, gender, major

        // students ids registered in courses in specific yser and semester
        $uniqueStudentsIdsInCourses = Registration::where('r_year', $year)->where('r_semester', $semester)
            ->get()->unique('r_student_id')->values()->pluck('r_student_id');

        $studentsInCourses = User::whereIn('u_id', $uniqueStudentsIdsInCourses)->get();

        if ($request->has('gender')) {
            $studentsInCourses = $studentsInCourses->where('u_gender', $request->input('gender'))->values();
        }

        if ($request->has('major')) {
            $studentsInCourses = $studentsInCourses->where('u_major_id', $request->input('major'))->values();
        }

        $numStudentsInCourses = $studentsInCourses->count();
        // return $numStudentsInCourses; // 1

        // 2. Total number of Semester Courses
        // affected by: year, semester
        $courses = SemesterCourse::where('sc_year', $year)->where('sc_semester', $semester)->get()->count();
        // return $courses; // 2

        // 3. Total of Training Hours for all students
        // affected by: year, semester, gender, major, company, branch

        // this $studentsCompanies shared for 3, 4 and 5
        $studentsCompanies = StudentCompany::whereHas('registration', function ($query) use ($year, $semester) {
            $query->where('r_year', $year)->where('r_semester', $semester);
        })->get();


        if ($request->has('gender') || $request->has('major')) {
            $studentsCompanies = $studentsCompanies->load('users');

            if ($request->has('gender')) {
                $studentsCompanies = $studentsCompanies->filter(function ($studentCompany) use ($request) {
                    return $studentCompany->users->u_gender == $request->input('gender');
                })->values();
            }

            if ($request->has('major')) {
                $studentsCompanies = $studentsCompanies->filter(function ($studentCompany) use ($request) {
                    return $studentCompany->users->u_major_id == $request->input('major');
                })->values();
            }
        }

        // i may use the filter on it too
        // $studentsInCompanies = $studentsCompanies; // to use it with 5 before filter on company and branch

        if ($request->has('company')) {
            $studentsCompanies = $studentsCompanies->filter(function ($studentCompany) use ($request) {
                return $studentCompany->sc_company_id == $request->input('company');
            })->values();

            if ($request->has('branch')) {
                $studentsCompanies = $studentsCompanies->filter(function ($studentCompany) use ($request) {
                    return $studentCompany->sc_branch_id == $request->input('branch');
                })->values();
            }
        }

        // Initialize total time difference
        $totalTimeDifference = 0;

        foreach ($studentsCompanies as $studentCompany) {
            foreach ($studentCompany->attendance as $attendance) { // attendance is the relation name
                if ($attendance->sa_out_time === null) {
                    continue; // Skip this attendance record
                }

                $sa_in_time = Carbon::parse($attendance->sa_in_time);
                $sa_out_time = Carbon::parse($attendance->sa_out_time);

                $timeDifference = $sa_out_time->diffInSeconds($sa_in_time);

                $totalTimeDifference += $timeDifference;
            }
        }

        $totalHours = floor($totalTimeDifference / 3600);
        $totalMinutes = floor(($totalTimeDifference % 3600) / 60);
        $totalSeconds = $totalTimeDifference % 60;

        // return [$totalHours, $totalMinutes, $totalSeconds]; // 3


        // 4. Total of Companies' Trainees
        // affected by: year, semester, gender, major, company, branch

        $studentsInCompanies = $studentsCompanies->unique('sc_student_id')->values()->count();
        // return $studentsInCompanies; // 4


        // 5. Total number of Companies have trainees
        // affected by: year, semester, gender, major
        $studentsCompanies = $studentsCompanies->unique('sc_company_id')->values()->count();
        // $studentsCompanies = StudentCompany::select('sc_company_id')->groupBy('sc_company_id')->get();

        // return $studentsCompanies; // 5

        return response()->json([
            'status' => true,
            'num_students_in_courses' => $numStudentsInCourses,
            'num_student_courses' => $courses,
            'total_attendance_hours' => $totalHours,
            'total_attendance_minutes' => $totalMinutes,
            'total_attendance_seconds' => $totalSeconds,
            'num_students_in_companies' => $studentsInCompanies,
            'num_companies' => $studentsCompanies

        ]);
    }

    // all students enrolled in training
    // with the filter params and search
    public function getAllMatchStudents(Request $request)
    {
        // search
        $requestSearch = $request->input('search');

        if ($request->has('year')) {
            $year = $request->input('year');
        } else {
            $year = SystemSetting::first()->ss_year;
        }

        if ($request->has('semester')) {
            $semester = $request->input('semester');
        } else {
            $semester = SystemSetting::first()->ss_semester_type;
        }

        $studentsCompanies = StudentCompany::whereHas('registration', function ($query) use ($year, $semester) {
            $query->where('r_year', $year)->where('r_semester', $semester);
        })->get();


        if ($request->has('gender') || $request->has('major')) {
            $studentsCompanies = $studentsCompanies->load('users');

            if ($request->has('gender')) {
                $studentsCompanies = $studentsCompanies->filter(function ($studentCompany) use ($request) {
                    return $studentCompany->users->u_gender == $request->input('gender');
                })->values();
            }

            if ($request->has('major')) {
                $studentsCompanies = $studentsCompanies->filter(function ($studentCompany) use ($request) {
                    return $studentCompany->users->u_major_id == $request->input('major');
                })->values();
            }
        }

        if ($request->has('company')) {
            $studentsCompanies = $studentsCompanies->filter(function ($studentCompany) use ($request) {
                return $studentCompany->sc_company_id == $request->input('company');
            })->values();

            if ($request->has('branch')) {
                $studentsCompanies = $studentsCompanies->filter(function ($studentCompany) use ($request) {
                    return $studentCompany->sc_branch_id == $request->input('branch');
                })->values();
            }
        }


        $studentsInCompanies = User::whereIn('u_id', $studentsCompanies->unique('sc_student_id')->values()->pluck('sc_student_id'))
            ->with('major:m_id,m_name');

        if (!empty($requestSearch)) {
            $studentsInCompanies->where(function ($query) use ($requestSearch) {
                $query->where('u_username', 'like', '%' . $requestSearch . '%')
                    ->orWhere('name', 'like', '%' . $requestSearch . '%');
            });
        }

        $studentsInCompanies = $studentsInCompanies->select('u_id', 'u_username', 'name', 'u_major_id')->paginate(10);

        return response()->json([
            'status' => true,
            'pagination' => [
                'current_page' => $studentsInCompanies->currentPage(),
                'last_page' => $studentsInCompanies->lastPage(),
                'per_page' => $studentsInCompanies->perPage(),
                'total_items' => $studentsInCompanies->total(),
            ],
            'students' => $studentsInCompanies->items(),
        ]);
    }

    public function getAllNonMatchStudents(Request $request)
    {
        if ($request->has('year')) {
            $year = $request->input('year');
        } else {
            $year = SystemSetting::first()->ss_year;
        }

        if ($request->has('semester')) {
            $semester = $request->input('semester');
        } else {
            $semester = SystemSetting::first()->ss_semester_type;
        }


        // all (match + non match)
        $uniqueStudentsIdsInCourses = Registration::where('r_year', $year)->where('r_semester', $semester)
            ->get()->unique('r_student_id')->values()->pluck('r_student_id');

        $studentsInCourses = User::whereIn('u_id', $uniqueStudentsIdsInCourses)->get();

        if ($request->has('gender')) {
            $studentsInCourses = $studentsInCourses->where('u_gender', $request->input('gender'))->values();
        }

        if ($request->has('major')) {
            $studentsInCourses = $studentsInCourses->where('u_major_id', $request->input('major'))->values();
        }


        // return $studentsInCourses;



        // to get only the non match
        $studentsCompanies = StudentCompany::whereHas('registration', function ($query) use ($year, $semester) {
            $query->where('r_year', $year)->where('r_semester', $semester);
        })->get();


        if ($request->has('gender') || $request->has('major')) {
            $studentsCompanies = $studentsCompanies->load('users');

            if ($request->has('gender')) {
                $studentsCompanies = $studentsCompanies->filter(function ($studentCompany) use ($request) {
                    return $studentCompany->users->u_gender == $request->input('gender');
                })->values();
            }

            if ($request->has('major')) {
                $studentsCompanies = $studentsCompanies->filter(function ($studentCompany) use ($request) {
                    return $studentCompany->users->u_major_id == $request->input('major');
                })->values();
            }
        }



        if ($request->has('company')) {
            $studentsCompanies = $studentsCompanies->filter(function ($studentCompany) use ($request) {
                return $studentCompany->sc_company_id == $request->input('company');
            })->values();

            if ($request->has('branch')) {
                $studentsCompanies = $studentsCompanies->filter(function ($studentCompany) use ($request) {
                    return $studentCompany->sc_branch_id == $request->input('branch');
                })->values();
            }
        }

        // $non_match_students = $studentsInCourses->whereNotIn('u_id', $studentsCompanies->unique('sc_student_id'))->values();


        $students_in_courses_ids = $studentsInCourses
            ->pluck('u_id')
            ->toArray();

        $non_match_students = User::whereNotIn('u_id', $studentsCompanies->pluck('sc_student_id')->toArray())
            ->whereIn('u_id', $students_in_courses_ids)
            ->with('major:m_id,m_name')
            ->select('u_id', 'u_username', 'name', 'u_major_id')
            ->paginate(10); // Adjust 10 to the desired number of items per page


        // $studentsInCompanies = User::whereIn('u_id', $studentsCompanies->unique('sc_student_id')->values()->pluck('sc_student_id'))
        //     ->with('major:m_id,m_name');

        // $studentsInCompanies = $studentsInCompanies->select('u_id', 'u_username', 'name', 'u_major_id')->paginate(10);


        return response()->json([
            'status' => true,
            'pagination' => [
                'current_page' => $non_match_students->currentPage(),
                'last_page' => $non_match_students->lastPage(),
                'per_page' => $non_match_students->perPage(),
                'total_items' => $non_match_students->total(),
            ],
            'students' => $non_match_students->items(),
        ]);
    }
}
