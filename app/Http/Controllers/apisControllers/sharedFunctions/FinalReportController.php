<?php

namespace App\Http\Controllers\apisControllers\sharedFunctions;

use App\Http\Controllers\Controller;
use App\Models\FinalReportsSubmissionsModel;
use App\Models\Registration;
use App\Models\SystemSetting;
use App\Services\FileUploadService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class FinalReportController extends Controller
{
    protected $fileUploadService;


    public function __construct()
    {
        $this->fileUploadService = new FileUploadService();
    }

    public function checkFinalReportStatus()
    {

        if (!$this->isSubmissionOpen()) {

            return response()->json(['is_open_and_valid' => false]);
        }

        $registrationId = $this->getRegistrationId();
        if (!$registrationId) {
            return response()->json(['is_open_and_valid' => false]);
        }

        return response()->json([
            'is_open_and_valid' => true,
            'registration_id' => $registrationId,
        ]);
    }

    private function isSubmissionOpen()
    {
        $systemSetting = SystemSetting::first();
        return $systemSetting->ss_report_status ?? false;
    }

    private function getRegistrationId()
    {
        $student = auth()->user();
        $systemSetting = SystemSetting::first();

        $registration = Registration::where('r_student_id', $student->u_id)
            ->where('r_semester', $systemSetting->ss_semester_type)
            ->where('r_year', $systemSetting->ss_year)
            ->first();

        return $registration?->r_id;
    }


    public function addFinalReport(Request $request)
    {
        $validator = Validator::make(
            $request->all(),
            [
                'registration_id' => 'required|integer|exists:registration,r_id',
                'report_file' => 'required|mimes:pdf,doc,docx,xls,xlsx,ppt,pptx,jpg,jpeg,png,gif,mp4,mkv,avi',
                'notes' => 'nullable',
            ],
            [
                'registration_id.required' => 'Registration ID is required.',
                'registration_id.exists' => 'Invalid Registration ID.',
                // 'report_file.mimes' => 'Only PDF, Word, Excel, Powerpoint documents are allowed.',
            ]
        );


        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => $validator->errors()->first(),
            ], 400);
        }

        if ($request->hasFile('report_file')) {
            $file = $request->file('report_file');
            $folderPath = 'final_reports';
            $new_file_name = $this->fileUploadService->uploadFile($file, $folderPath);

            $final_report = new FinalReportsSubmissionsModel();
            $final_report->frs_registration_id = $request->input('registration_id');
            $final_report->frs_name = $new_file_name;
            $final_report->frs_real_name = $file->getClientOriginalName();
            $final_report->frs_notes = $request->input('notes');

            if ($final_report->save()) {
                return response()->json([
                    'status' => true,
                    'message' => trans('messages.final_report_submission_added'),
                    'new_file' => $final_report,
                ]);
            }
        }

        return response()->json([
            'status' => false,
            'message' => trans('messages.final_report_submission_not_added'),
        ], 500);
    }


    public function getFinalReportSubmissions($registration_id)
    {
        $final_reports = FinalReportsSubmissionsModel::where('frs_registration_id', $registration_id)
            ->orderBy('created_at', 'DESC')
            ->get();
        return response()->json([
            'status' => true,
            'final_reports' => $final_reports,
        ]);
    }
}
