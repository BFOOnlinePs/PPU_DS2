<?php

namespace App\Http\Controllers\apisControllers\students;

use App\Http\Controllers\Controller;
use App\Models\StudentAttendance;
use App\Models\StudentCompany;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\Validator;

// we depend on Gaza time: Asia/Gaza
class StudentAttendanceController extends Controller
{
    public function studentCheckIn(Request $request)
    {
        $student_in_company = StudentCompany::where('sc_id', $request->input('sa_student_company_id'))
            ->where('sc_student_id', auth()->user()->u_id)->first();

        if (!$student_in_company) {
            return response()->json([
                'status' => false,
                'message' => 'not authenticated',
            ]);
        }

        $validator = Validator::make($request->all(), [
            'sa_student_company_id' => 'required|exists:students_companies,sc_id',
            'sa_start_time_latitude' => 'required',
            'sa_start_time_longitude' => 'required',
            'sa_description' => 'nullable',
        ], [
            'sa_start_time_latitude.required' => trans('messages.start_time_latitude_required'),
            'sa_start_time_longitude.required' => trans('messages.start_time_longitude_required'),
            'sa_student_company_id.required' => trans('messages.training_id_required'),
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => $validator->errors()->first(),
            ]);
        }

        $latestCheckIn = StudentAttendance::where('sa_student_id', auth()->user()->u_id)
            ->where('sa_student_company_id', $request->input('sa_student_company_id'))
            ->latest() // descending order
            ->first();

        if ($latestCheckIn) {
            $lastCheckInDate = Carbon::parse($latestCheckIn->sa_in_time)->toDateString();
            $today = Carbon::now('Asia/Gaza')->toDateString();

            if ($lastCheckInDate === $today && $latestCheckIn->sa_out_time == null) {
                return response()->json(['message' => trans('messages.attendance_today_done'),]);
            }
        }

        $studentCheckIn = StudentAttendance::create([
            'sa_student_id' => auth()->user()->u_id,
            'sa_student_company_id' => $request->input('sa_student_company_id'),
            'sa_start_time_latitude' => $request->input('sa_start_time_latitude'),
            'sa_start_time_longitude' => $request->input('sa_start_time_longitude'),
            'sa_description' => $request->input('sa_description'),
            'sa_in_time' => Carbon::now('Asia/Gaza'),
        ]);

        return response()->json([
            'status' => true,
            'message' => trans('messages.check_in_done'),
            'data' => $studentCheckIn
        ]);
    }


    public function studentCheckOut(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'sa_end_time_longitude' => 'required',
            'sa_end_time_latitude' => 'required',
            'sa_description' => 'nullable',
        ], [
            'sa_id.required' => trans('messages.check_in_id_required'),
            'sa_end_time_latitude.required' => trans('messages.end_time_latitude_required'),
            'sa_end_time_longitude.required' => trans('messages.end_time_longitude_required'),
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => $validator->errors()->first(),
            ]);
        }

        // $studentAttendance = StudentAttendance::where('sa_id', $request->input('sa_id'))->first();
        $latestCheckIn = StudentAttendance::where('sa_student_id', auth()->user()->u_id)
            ->where('sa_student_company_id', $request->input('sa_student_company_id'))
            ->whereNull('sa_out_time')
            ->latest() // descending order
            ->first();

        if (!$latestCheckIn) {
            return response()->json(['message' => trans('messages.check_in_required')]);
        }

        if ($latestCheckIn) {
            $lastCheckInDate = Carbon::parse($latestCheckIn->sa_in_time)->toDateString();
            $today = Carbon::now('Asia/Gaza')->toDateString();

            if ($lastCheckInDate !== $today) {
                return response()->json(['message' => trans('messages.check_in_required')]);
            }
        }

        $latestCheckIn->update([
            'sa_end_time_longitude' => $request->input('sa_end_time_longitude'),
            'sa_end_time_latitude' => $request->input('sa_end_time_latitude'),
            'sa_description' => $request->input('sa_description') ?? $latestCheckIn->sa_description,
            'sa_out_time' => Carbon::now('Asia/Gaza'),
        ]);

        return response()->json([
            'status' => true,
            'message' => trans('messages.check_out_done'),
            'data' => $latestCheckIn
        ]);
    }


    // req: sa_student_company_id
    public function checkTodayStudentAttendance(Request $request)
    {
        $student_company_id = $request->input('sa_student_company_id');
        $student_attendance = StudentAttendance::where('sa_student_id', auth()->user()->u_id)
            ->where('sa_student_company_id', $student_company_id)
            ->latest()
            ->first();

        $student_attendance_in_all_companies = StudentAttendance::where('sa_student_id', auth()->user()->u_id)
            ->latest()
            ->first();

        $today = Carbon::now('Asia/Gaza')->toDateString();

        $today_attendance = StudentAttendance::where('sa_student_company_id', $student_company_id)
            ->whereDate('sa_in_time', $today)
            ->get();

        // if last check in is today and different sc_id and did not checked out
        // then he can not checkin in different training (return true true)
        if ($student_attendance_in_all_companies) {
            $lastCheckInAllCompaniesDate = Carbon::parse($student_attendance_in_all_companies->sa_in_time)->toDateString();

            if (
                $lastCheckInAllCompaniesDate === $today
                && $student_attendance_in_all_companies->sa_out_time == null
                && $student_attendance_in_all_companies->sa_student_company_id != $request->input('sa_student_company_id')
            ) {
                return response()->json([
                    'today_checkin' => true,
                    'today_checkout' => true,
                    'sa_description' => null,
                    'today_attendance' => $today_attendance
                ]);
            }
        }

        $today_checkin = false;
        $today_checkout = false;
        $sa_description = null;

        if ($student_attendance) {
            $lastCheckInDate = Carbon::parse($student_attendance->sa_in_time)->toDateString();

            if ($lastCheckInDate === $today) {
                $today_checkin = true;
                $sa_description = $student_attendance->sa_description;
            }

            if ($today_checkin && $student_attendance->sa_out_time != null) {
                $today_checkout = true;
            }

            if ($today_checkin && $today_checkout) {
                $today_checkin = false;
                $today_checkout = false;
                $sa_description = null;
            }
        }

        return response()->json([
            'today_checkin' => $today_checkin,
            'today_checkout' => $today_checkout,
            'sa_description' => $sa_description,
            'today_attendance' => $today_attendance
        ]);
    }
}
