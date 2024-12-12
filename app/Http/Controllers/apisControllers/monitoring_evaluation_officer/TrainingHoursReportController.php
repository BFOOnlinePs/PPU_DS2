<?php

namespace App\Http\Controllers\apisControllers\monitoring_evaluation_officer;

use App\Http\Controllers\Controller;
use App\Models\Registration;
use App\Models\StudentCompany;
use App\Models\SystemSetting;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;

class TrainingHoursReportController extends Controller
{
    // filters as query params
    public function getTrainingHoursReport(Request $request)
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


        foreach ($studentsCompanies as $studentCompany) {
            // Initialize total time difference
            $totalTimeDifference = 0;
            foreach ($studentCompany->attendance as $attendance) { // attendance is the relation name
                if ($attendance->sa_out_time === null) {
                    continue; // Skip this attendance record
                }

                $sa_in_time = Carbon::parse($attendance->sa_in_time);
                $sa_out_time = Carbon::parse($attendance->sa_out_time);

                $timeDifference = $sa_out_time->diffInSeconds($sa_in_time);

                $totalTimeDifference += $timeDifference;
            }
            $totalHours = floor($totalTimeDifference / 3600);
            $totalMinutes = floor(($totalTimeDifference % 3600) / 60);
            $totalSeconds = $totalTimeDifference % 60;

            $studentCompany->sc_total_hours = $totalHours;
            $studentCompany->sc_total_minutes = $totalMinutes;
            $studentCompany->sc_total_seconds = $totalSeconds;
        }


        // Group the objects by student_id and calculate the sums
        $studentWithTrainingTimes = $studentsCompanies->groupBy('sc_student_id')->map(function ($group, $sc_student_id) {
            $totalHours = $group->sum('sc_total_hours');
            $totalMinutes = $group->sum('sc_total_minutes');
            $totalSeconds = $group->sum('sc_total_seconds');

            $totalMinutes += floor($totalSeconds / 60);
            $totalSeconds %= 60;

            $totalHours += floor($totalMinutes / 60);
            $totalMinutes %= 60;

            $student_info = User::where('u_id', $sc_student_id)->first();

            return [
                'student_info' => $student_info,
                'student_time' => [
                    'total_hours' => $totalHours,
                    'total_minutes' => $totalMinutes,
                    'total_seconds' => $totalSeconds
                ]
            ];
        });

        // Calculate the sum of hours, minutes, and seconds for all students
        $totalHoursAll = $studentWithTrainingTimes->sum('student_time.total_hours');
        $totalMinutesAll = $studentWithTrainingTimes->sum('student_time.total_minutes');
        $totalSecondsAll = $studentWithTrainingTimes->sum('student_time.total_seconds');

        // // Adjust minutes and seconds if they exceed 60
        $totalMinutesAll += floor($totalSecondsAll / 60);
        $totalSecondsAll %= 60;

        $totalHoursAll += floor($totalMinutesAll / 60);
        $totalMinutesAll %= 60;

        $studentWithTrainingTimes = $studentWithTrainingTimes->values();

        return response()->json([
            'status' => true,
            'student_with_training_times' => $studentWithTrainingTimes,
            'all_students_times' => [
                'total_hours' => $totalHoursAll,
                'total_minutes' => $totalMinutesAll,
                'total_seconds' => $totalSecondsAll
            ]
        ]);
    }
}
