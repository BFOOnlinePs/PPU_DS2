<?php

namespace App\Http\Controllers\project\settings;

use App\Http\Controllers\Controller;
use App\Imports\CoursesImport;
use App\Imports\integration_company_new;
use App\Imports\MajorsImport;
use App\Imports\RegistrationsImport;
use App\Imports\SemesterCourseImport;
use App\Imports\UsersImport;
use App\Models\Payment;
use App\Models\Registration;
use App\Models\SystemSetting;
use Illuminate\Http\Request;
use App\Models\SemesterCourse;
use App\Models\StudentAttendance;
use App\Models\StudentCompany;
use App\Models\StudentReport;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;

class SettingsController extends Controller
{
    public function index()
    {
        $system_settings = SystemSetting::first();
        $background_color = $system_settings->ss_primary_background_color;
        $text_color = $system_settings->ss_primary_font_color;
        return view('project.admin.settings.coloring' , ['background_color' => $background_color, 'text_color' => $text_color]);
    }
    public function primary_background_color(Request $request)
    {
        $system_settings = SystemSetting::first();
        $system_settings->ss_primary_background_color = $request->color_value;
        if($system_settings->save()) {
            return response()->json([]);
        }
    }
    public function primary_font_color(Request $request)
    {
        $system_settings = SystemSetting::first();
        $system_settings->ss_primary_font_color = $request->color_value;
        if($system_settings->save()) {
            return response()->json([]);
        }
    }

    public function integration()
    {
        return view('project.admin.settings.integration');
    }
    public function uploadFileExcel(Request $request)
    {
        if ($request->hasFile('input-file')) {
            $file = $request->file('input-file');
            $extension = $file->getClientOriginalExtension();
            $filename = time() . '.' . $extension;
            $file->storeAs('excel', $filename, 'public'); // to save file temporarily in excel folder

            // Allowed Excel extensions
            $allowedExtensions = ['xlsx', 'xls'];
            if (in_array($extension, $allowedExtensions)) {
                // Read the first row of the Excel file
                $path = public_path('storage/excel/' . $filename);
                $firstRow = Excel::toCollection([], $path)->first()->first();
                return response()->json(['status' => 1 ,'headers' => $firstRow , 'name_file_hidden' => $filename]);
            }
            else {
                return response()->json(['status' => 0]);
            }
        }
    }
    public function submitForm(Request $request)
    {
        if ($request->hasFile('input-file')) {
            $path = public_path('storage/excel/' . $request->input('name_file_hidden'));
            $decodedMap = explode(',', $request->input('data'));
            $result = null;
            for ($i = 0; $i < count($decodedMap) - 1; $i += 2) {
                $key = $decodedMap[$i];
                $value = $decodedMap[$i + 1];
                $result[$key] = $value;
            }
            $course_object = new CoursesImport($result);
            $major_object = new MajorsImport($result);
            $user_object = new UsersImport($result);
            $registration_object = new RegistrationsImport($result);
            Excel::import($course_object, $path);
            Excel::import($major_object, $path);
            Excel::import($user_object, $path);
            Excel::import($registration_object, $path);
            return response()->json([
                'registration_object' => $registration_object->getCount() ,
                'major_object' => $major_object->getCount() ,
                'user_object' => $user_object->getCount() ,
                'course_object' => $course_object->getCount() ,
                'courses_array' => $course_object->getCoursesArray(),
                'majors_array' => $major_object->getMajorsArray(),
                'students_numbers_array' => $user_object->getArrayStudentsNumbers() ,
                'students_names_array' => $user_object->getArrayStudentsNames() ,
                'registration_array' => $registration_object->getRegistrationArray()
            ]);
        }
    }
    public function validateStepOne(Request $request)
    {
        if ($request->hasFile('input-file')) {
            $file = $request->file('input-file');
            $extension = $file->getClientOriginalExtension();
            // Allowed Excel extensions
            $allowedExtensions = ['xlsx', 'xls'];
            if (in_array($extension, $allowedExtensions)) {
                return response()->json(['status' => 1]);
            }
            else {
                return response()->json(['status' => 0]);
            }
        }
    }


    public function systemSettings(){
        $systemSettings = SystemSetting::first();

        $semester = $systemSettings->ss_semester_type;
        $year = $systemSettings->ss_year;
        $report = $systemSettings->ss_report_status;

        $studentsNum = Registration::where('r_year',$year)
        ->where('r_semester',$semester)
        ->select('r_student_id')
        ->distinct()
        ->get();

        $coursesNum = SemesterCourse::where('sc_semester',$semester)
        ->where('sc_year',$year)
        ->get();

        return view('project.admin.settings.systemSettings' , ['year' => $year, 'semester' => $semester, 'studentsNum'=>count($studentsNum), 'coursesNum'=>count($coursesNum) , 'report'=>$report]);
    }
    public function deleteData() {
        return view('project.admin.settings.deleteData');
    }
    public function confirmDelete(Request $request)
    {
        // Make date range
        $from = Carbon::parse($request->from)->format('Y-m-d H:i:s');
        $to = Carbon::parse($request->to)->addDay()->format('Y-m-d H:i:s');
        // Get student ids
        $students_id = User::whereBetween('created_at', [$from , $to])
                        ->where('u_role_id' , 2)
                        ->pluck('u_id')
                        ->toArray();
        // Delete payments and its files
        $payment_p_file = Payment::whereIn('p_student_id' , $students_id)
                                    ->pluck('p_file')
                                    ->toArray();
        for($i = 0; $i < count($payment_p_file); $i++) {
            if(Storage::exists('public/payments/' . $payment_p_file[$i]))
            {
                Storage::delete('public/payments/' . $payment_p_file[$i]);
            }
        }
        $payment_delete = Payment::whereIn('p_student_id' , $students_id)
                            ->delete();
        // Delete registration
        $registration_delete = Registration::whereIn('r_student_id' , $students_id)
                                ->delete();
        // Delete students attendance
        $student_attendance_delete = StudentAttendance::whereIn('sa_student_id' , $students_id)
                                    ->delete();
        // Delete student company
        $student_company_sc_agreement_file = StudentCompany::whereIn('sc_student_id' , $students_id)
                                    ->pluck('sc_agreement_file')
                                    ->toArray();
        for($i = 0; $i < count($student_company_sc_agreement_file); $i++) {
            if(Storage::exists('public/uploads/' . $student_company_sc_agreement_file[$i]))
            {
                Storage::delete('public/uploads/' . $student_company_sc_agreement_file[$i]);
            }
        }
        $student_company_delete = StudentCompany::whereIn('sc_student_id' , $students_id)
                                    ->delete();
        // Delete student report
        $student_report_sr_attached_file = StudentReport::whereIn('sr_student_id' , $students_id)
                                            ->pluck('sr_attached_file')
                                            ->toArray();
        for($i = 0; $i < count($student_report_sr_attached_file); $i++) {
            if(Storage::exists('public/student_reports/' . $student_report_sr_attached_file[$i]))
            {
                Storage::delete('public/student_reports/' . $student_report_sr_attached_file[$i]);
            }
        }
        $student_report_delete = StudentReport::whereIn('sr_student_id', $students_id)
                                ->delete();
        // Delete students from user table
        $student_users_delete = User::whereIn('u_id' , $students_id)
                        ->where('u_role_id', 2)
                        ->delete();

        if($payment_delete > 0 || $registration_delete > 0 || $student_attendance_delete > 0 || $student_company_delete > 0 || $student_report_delete > 0 || $student_users_delete > 0) {
            return response()->json(['status' => 1]);
        }
        else {
            return response()->json(['status' => 0]);
        }
    }

    public function systemSettingsUpdate(Request $request){
        $systemSettings = SystemSetting::first();

        $year = $request->year;
        $semester = $request->semester;


        $systemSettings->ss_year = $year;
        $systemSettings->ss_semester_type = $semester;
        $systemSettings->ss_report_status = $request->report_status;

        $studentsNum = Registration::where('r_year',$year)
        ->where('r_semester',$semester)
        ->select('r_student_id')
        ->distinct()
        ->get();

        $coursesNum = SemesterCourse::where('sc_semester',$semester)
        ->where('sc_year',$year)
        ->get();


        if($systemSettings->save()) {
            return response()->json([
                'success'=> 'true',
                'coursesNum'=> count($coursesNum),
                'studentsNum'=> count($studentsNum)
            ]);
        }
    }

    public function import_integration_student_excel(Request $request){
        $data = $request;
        Excel::import(new integration_company_new($request), $request->file('file'));        return redirect('/')->with('success', 'All good!');
    }
}
