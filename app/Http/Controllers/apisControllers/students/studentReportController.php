<?php

namespace App\Http\Controllers\apisControllers\students;

use App\Http\Controllers\Controller;
use App\Models\StudentReport;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class studentReportController extends Controller
{
    // to add student report for the current student
    public function studentSubmitNewReport(Request $request)
    {
        $validator  = Validator::make($request->all(), [
            'sr_student_attendance_id' => 'required|exists:students_attendance,sa_id',
            'sr_report_text' => 'required',
            'sr_attached_file' => 'nullable|file|mimes:jpg,jpeg,png,svg,pdf,doc,docx,xls,xlsx,ppt,pptx,odt,ods,odp,mp3,mp4,csv,xlsx', // .txt accept other things
            'sr_submit_longitude' => 'required|string',
            'sr_submit_latitude' => 'required|string',
        ], [
            'sr_student_attendance_id.required' => trans('messages.student_attendance_id_required'),
            'sr_report_text.required' => trans('messages.report_text_required'),
            'sr_submit_longitude.required' => trans('messages.report_submit_location_required'),
            'sr_submit_latitude.required' => trans('messages.report_submit_location_required'),
            'sr_attached_file.mimes' => trans('messages.report_attached_file_mimes'),
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => $validator->errors()->first(),
            ], 200);
        }

        //  $reportUrl = asset('storage/' . $folderPath . '/' . $fileName);

        // return response()->json([
        //     'reportUrl' => $reportUrl,
        // ]);
        $studentReport = StudentReport::create([
            'sr_student_attendance_id' => $request->input('sr_student_attendance_id'),
            'sr_report_text' => $request->input('sr_report_text'),
            'sr_student_id' => auth()->user()->u_id,
            'sr_submit_longitude' => $request->input('sr_submit_longitude'),
            'sr_submit_latitude' => $request->input('sr_submit_latitude'),
        ]);

        if ($request->hasFile('sr_attached_file')) {
            $file = $request->file('sr_attached_file');
            $folderPath = 'student_reports';
            // $fileName = time() . '_' . uniqid() . '.' . $file->getClientOriginalName();
            $extension = $file->getClientOriginalExtension();
            $fileName = time() . '_' . uniqid() . '.' . $extension;
            $request->file('sr_attached_file')->storeAs($folderPath, $fileName, 'public');

            $studentReport->update([
                'sr_attached_file' => $fileName,
            ]);
        }

        return response()->json([
            'status' => true,
            'message' => trans('messages.report_submitted'),
            'student_report' => $studentReport,
        ], 200);
    }


    public function studentEditReport(Request $request)
    {
        $validator  = Validator::make($request->all(), [
            'sr_id' => 'required',
            'sr_report_text' => 'required',
            'sr_attached_file' => 'nullable|file|mimes:jpg,jpeg,png,svg,pdf,doc,docx,xls,xlsx,ppt,pptx,odt,ods,odp,mp3,mp4,csv,xlsx,wmv,ai,psd,wmv', // .txt accept other things
            'sr_submit_longitude' => 'required|string',
            'sr_submit_latitude' => 'required|string',
        ], [
            'sr_id.required' => trans('messages.report_id_required'),
            'sr_report_text.required' => trans('messages.report_text_required'),
            'sr_submit_longitude.required' => trans('messages.report_submit_location_required'),
            'sr_submit_latitude.required' => trans('messages.report_submit_location_required'),
            'sr_attached_file.mimes' => trans('messages.report_attached_file_mimes'),
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => $validator->errors()->first(),
            ], 200);
        }

        $studentReport = StudentReport::where('sr_id', $request->input('sr_id'))->first();

        if (!$studentReport) {
            return response()->json([
                'status' => false,
                'message' => trans('messages.report_id_not_exists'),
            ], 200);
        }

        $studentReport->update([
            'sr_report_text' => $request->input('sr_report_text'),
            'sr_submit_longitude' => $request->input('sr_submit_longitude'),
            'sr_submit_latitude' => $request->input('sr_submit_latitude'),
        ]);

        if ($request->hasFile('sr_attached_file')) {
            $file = $request->file('sr_attached_file');
            $folderPath = 'student_reports';
            $extension = $file->getClientOriginalExtension();
            $fileName = time() . '_' . uniqid() . '.' . $extension;
            $request->file('sr_attached_file')->storeAs($folderPath, $fileName, 'public');

            // delete old file
            Storage::disk('public')->delete($folderPath . '/' . $studentReport->sr_attached_file);

            $studentReport->update([
                'sr_attached_file' => $fileName,
            ]);
        }

        return response()->json([
            'status' => true,
            'message' => trans('messages.report_updated'),
            // 'student_report' => $studentReport,
        ], 200);
    }
}
