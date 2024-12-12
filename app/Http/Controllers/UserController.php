<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\CitiesModel;
use App\Models\FileAttachmentModel;
use App\Models\StudentCompanyNominationModel;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Role;
use App\Models\Major;
use App\Models\Course;
use App\Models\SystemSetting;
use App\Models\Registration;
use App\Models\Company;
use App\Models\CompanyBranch;
use App\Models\companyBranchDepartments;
use App\Models\CompanyDepartment;
use App\Models\StudentAttendance;
use App\Models\StudentCompany;
use Illuminate\Support\Facades\Storage;
use App\Models\MajorSupervisor;
use App\Models\Payment;
use App\Models\SemesterCourse;
use App\Models\StudentReport;
use App\Models\SupervisorAssistant;
use Carbon\Carbon;
use Illuminate\Validation\Rule;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx\Rels;

class UserController extends Controller
{
    public function student_submit_report(Request $request)
    {
        $student_report = new StudentReport;
        $student_report->sr_student_attendance_id = $request->input('sa_id');
        if ($request->hasFile('file_report_student')) {
            $file = $request->file('file_report_student');
            $extension = $file->getClientOriginalExtension();
            $filename = time() . '.' . $extension; // Unique filename
            $file->storeAs('student_reports', $filename, 'public');
            $student_report->sr_attached_file = $filename;
            $student_report->save();
            // return response()->json(['html' => 'kdsljfdlkjd']);
        }
    }
    public function student_training_list($id)
    {
        $student_companies = StudentCompany::where('sc_student_id', $id)->get();
        return view('project.admin.users.studentCompanyList' , ['student_companies' => $student_companies]);
    }

    public function report_student_edit(Request $request)
    {
        $student_report = StudentReport::find($request->sr_id);
        $student_report->sr_notes = $request->sr_notes;
        if($student_report->save()) {
            return response()->json([]);
        }
    }

    public function report_student_display(Request $request)
    {
        $student_report = StudentReport::where('sr_student_attendance_id' , $request->sa_id)->first();
        $modal = view('project.admin.users.modals.report_student' , ['student_report' => $student_report])->render();
        return response()->json(['modal' => $modal]);
    }
    public function supervisor_students_search_major(Request $request)
    {
        if(!isset($request->m_id)) {
            $ms_major_id = MajorSupervisor::where('ms_super_id' , $request->user_id)
                                    ->pluck('ms_major_id')
                                    ->toArray();
            $students = User::where('u_role_id' , 2)
                            ->whereIn('u_major_id' , $ms_major_id)
                            ->get();
        }
        else {
            $students = User::where('u_role_id' , 2)
                            ->where('u_major_id' , $request->m_id)
                            ->get();
        }
        $html = view('project.admin.users.ajax.supervisorStudentsList' , ['students' => $students])->render();
        return response()->json(['html' => $html]);
    }
    public function supervisor_students_search(Request $request)
    {
        $students = null;
        if(!isset($request->m_id)) {
            $ms_major_id = MajorSupervisor::where('ms_super_id' , $request->user_id)
                                            ->pluck('ms_major_id')
                                            ->toArray();
            $students = User::where('u_role_id' , 2)
                            ->whereIn('u_major_id' , $ms_major_id)
                            ->where('name', 'like', '%' . $request->word_to_search . '%');
            $students = $students->union(
                User::where('u_role_id' , 2)
                    ->whereIn('u_major_id' , $ms_major_id)
                    ->where('u_username', 'like', '%' . $request->word_to_search . '%')
            )->get();
        }
        else {
            $students = User::where('u_role_id' , 2)
                        ->where('u_major_id' , $request->m_id)
                        ->where('name', 'like', '%' . $request->word_to_search . '%');
            $students = $students->union(
            User::where('u_role_id' , 2)
                ->where('u_major_id' , $request->m_id)
                ->where('u_username', 'like', '%' . $request->word_to_search . '%')
            )->get();
        }
        $html = view('project.admin.users.ajax.supervisorStudentsList' , ['students' => $students])->render();
        return response()->json(['html' => $html]);
    }
    public function supervisor_major_delete(Request $request)
    {
        $major_supervisor_delete = MajorSupervisor::where('ms_id' , $request->ms_id)->delete();
        if($major_supervisor_delete > 0)
        {
            $data = MajorSupervisor::where('ms_super_id' , $request->user_id)->get();
            $html = view('project.admin.users.ajax.supervisorMajorList' , ['data' => $data])->render();
            $supervisor_majors_id = MajorSupervisor::where('ms_super_id' , $request->user_id)
                                            ->pluck('ms_major_id')
                                            ->toArray();
            $majors = Major::whereNotIn('m_id', $supervisor_majors_id)->get();
            $supervisor_assistants = User::where('u_role_id' , 4)->get();
            return response()->json(['html' => $html , 'majors' => $majors , 'supervisor_assistants' => $supervisor_assistants]);
        }
    }
    public function supervisor_major_add(Request $request)
    {
        $major_supervisor = new MajorSupervisor;
        $major_supervisor->ms_super_id = $request->user_id;
        $major_supervisor->ms_major_id = $request->major_id;
        if($major_supervisor->save())
        {
            $data = MajorSupervisor::where('ms_super_id' , $request->user_id)->get();
            $html = view('project.admin.users.ajax.supervisorMajorList' , ['data' => $data])->render();
            $supervisor_majors_id = MajorSupervisor::where('ms_super_id' , $request->user_id)
                                            ->pluck('ms_major_id')
                                            ->toArray();
            $majors = Major::whereNotIn('m_id', $supervisor_majors_id)->get();
            $supervisor_assistants = User::where('u_role_id' , 4)->get();
            return response()->json(['html' => $html , 'majors' => $majors , 'supervisor_assistants' => $supervisor_assistants]);
        }
    }
    public function student_payments($id)
    {
        $user = User::find($id);
        $payments = Payment::where('p_student_id', $id)->get();
        return view('project.admin.users.student_payments' , ['user' => $user , 'payments' => $payments]);
    }
    public function students_attendance($id)
    {
        $user = User::find($id);
        $student_company = StudentCompany::where('sc_student_id' , $id)
                            ->pluck('sc_id')
                            ->toArray();
        $student_attendances = StudentAttendance::where('sa_student_id', $id )
                            ->whereIn('sa_student_company_id', $student_company)
                            ->get();
        return view('project.admin.users.students_attendance' , ['id' => $id , 'user' => $user , 'student_attendances' => $student_attendances , 'student_report'=> null]);
    }
    public function training_place_delete_file_agreement($sc_id)
    {
        $studentCompany = StudentCompany::find($sc_id);
        if(Storage::exists('public/uploads/' . $studentCompany->sc_agreement_file))
        {
            Storage::delete('public/uploads/' . $studentCompany->sc_agreement_file);
        }
        $studentCompany->sc_agreement_file = null;
        if($studentCompany->save()) {
            return redirect()->back();
        }
    }
    public function training_place_update_file_agreement(Request $request)
    {
        $studentCompany = StudentCompany::find($request->id_company_student);
        if ($request->hasFile('file_company_student')) {
            $file = $request->file('file_company_student');
            $extension = $file->getClientOriginalExtension();
            $filename = time() . '.' . $extension; // Unique filename
            $file->storeAs('uploads', $filename, 'public');
            $studentCompany->sc_agreement_file = $filename;
        }
        if($studentCompany->save()) {
            $data = StudentCompany::where('sc_student_id' , $request->sc_student_id)
                                    ->where('sc_status', 1)
                                    ->get();
            $html = view('project.admin.users.ajax.placesTrainingList' , ['data' => $data])->render();
            return response()->json(['html' => $html]);
        }
    }
    public function places_training_edit_branch(Request $request)
    {
        $company_branch_department = companyBranchDepartments::where('cbd_company_branch_id' , $request->branch_id)
                                    ->pluck('cbd_d_id')
                                    ->toArray();
        $departments = CompanyDepartment::whereIn('d_id' , $company_branch_department)->get();
        return response()->json(['departments' => $departments]);
    }
    public function places_training_edit(Request $request)
    {
        $student_company = StudentCompany::where('sc_id' , $request->sc_id)->first();
        $trainers = User::where('u_company_id' , $student_company->sc_company_id)
                    ->whereNot('u_id' , $student_company->sc_mentor_trainer_id)
                    ->get();
        $branches = CompanyBranch::where('b_company_id' , $student_company->sc_company_id)
                    ->whereNot('b_id' , $student_company->sc_branch_id)
                    ->get();
        $branches_department = companyBranchDepartments::where('cbd_company_branch_id' , $student_company->sc_branch_id)
                                ->whereNot('cbd_d_id' , $student_company->sc_department_id)
                                ->pluck('cbd_d_id')
                                ->toArray();
        $departments = CompanyDepartment::whereIn('d_id' , $branches_department)
                        ->get();
        $courses = Registration::where('r_student_id' , $student_company->sc_student_id)
                    ->whereNot('r_id' , $student_company->sc_registration_id)
                    ->with('courses')
                    ->get();
        return response()->json(['courses' => $courses , 'departments' => $departments , 'student_company' => $student_company , 'branches' => $branches , 'trainers' => $trainers]);
    }
    public function places_training_update(Request $request)
    {
        $student_company = StudentCompany::find($request->sc_id);
        $student_company->sc_branch_id = $request->sc_branch_id;
        if($request->sc_department_id == "null") {
            // return response()->json(['x' => $request->sc_department_id]);
            $student_company->sc_department_id = null;
        }
        else {
            $student_company->sc_department_id = $request->sc_department_id;
        }
        $student_company->sc_status = $request->sc_status;
        if($request->sc_mentor_trainer_id == "null") {
            $student_company->sc_mentor_trainer_id = null;
        }
        else {
            $student_company->sc_mentor_trainer_id = $request->sc_mentor_trainer_id;
        }
        $student_company->sc_registration_id = $request->sc_registration_id;
        if($student_company->save()) {
            $data = StudentCompany::where('sc_student_id' , $student_company->sc_student_id)->get();
            $html = view('project.admin.users.ajax.placesTrainingList' , ['data' => $data])->render();
            return response()->json(['html'=>$html]);
        }
    }
    public function places_training_delete(Request $request)
    {
        $student_company = StudentCompany::find($request->sc_id);
        $student_company->sc_status = 3;
        if($student_company->save()) {
            $data = StudentCompany::where('sc_student_id' , $student_company->sc_student_id)
                                ->get();
            $html = view('project.admin.users.ajax.placesTrainingList' , ['data' => $data])->render();
            return response()->json(['html' => $html]);
        }
    }
    public function places_training_add(Request $request)
    {
        $validatedData = $request->validate([
            'company' => 'required' ,
            'course' => 'required'
        ],
        [
            'company.required' => __('translate.Company Name is required') // اسم الشركة حقل مطلوب
            ,
            'course.required' => __('translate.Course Name is required') // اسم المساق حقل مطلوب
            ]
        );

        $studentCompany = new StudentCompany;
        $studentCompany->sc_student_id = $request->id;
        $studentCompany->sc_company_id = $request->input('company');
        $studentCompany->sc_branch_id = $request->input('branch');
        $studentCompany->sc_department_id = $request->input('department');
        $studentCompany->sc_mentor_trainer_id = $request->input('trainer');
        $studentCompany->sc_status = 1;
        $studentCompany->sc_registration_id = $request->input('course');
        if ($request->hasFile('file')) {
            $file = $request->file('file');
            $extension = $file->getClientOriginalExtension();
            $filename = time() . '.' . $extension; // Unique filename
            $file->storeAs('uploads', $filename, 'public');
            $studentCompany->sc_agreement_file = $filename;
        }

        // Save the data to the database
        if($studentCompany->save()) {
            $data = StudentCompany::where('sc_student_id' , $request->id)
                                ->get();
            $html = view('project.admin.users.ajax.placesTrainingList' , ['data' => $data])->render();
            return response()->json(['html' => $html]);
        }
        return response()->json(['errors' => $validatedData]);
    }
    public function places_training_departments(Request $request)
    {
        $company_branch_department = companyBranchDepartments::where('cbd_company_branch_id' , $request->branch_id)
                                    ->pluck('cbd_d_id')
                                    ->toArray();
        $departments = CompanyDepartment::whereIn('d_id' , $company_branch_department)->get();
        return response()->json(['departments' => $departments]);
    }
    public function places_training_branches(Request $request)
    {
        $trainers = User::where('u_company_id' , $request->company_id)->get();
        $branches = CompanyBranch::where('b_company_id' , $request->company_id)->get();
        $branch = $branches->first();
        $company_branch_department = companyBranchDepartments::where('cbd_company_branch_id' , $branch->b_id)
                                    ->pluck('cbd_d_id')
                                    ->toArray();
        $departments = CompanyDepartment::whereIn('d_id' , $company_branch_department)->get();
        return response()->json(['departments' => $departments , 'branches' => $branches , 'trainers' => $trainers]);
    }
    public function places_training($id)
    {
        $user = User::find($id);
        $companies = Company::get();
        $data = StudentCompany::where('sc_student_id' , $id)
                            ->with('registrations.courses')
                            ->get();
        $system_setting = SystemSetting::first();
        $registrations = Registration::where('r_student_id' , $id)
                        ->where('r_semester' , $system_setting->ss_semester_type)
                        ->where('r_year' , $system_setting->ss_year)
                        ->get();
        return view('project.admin.users.places_training' , ['registrations' => $registrations , 'user' => $user , 'companies' => $companies , 'branches' => null , 'departments' => null , 'trainers' => null , 'data' => $data]);
    }
    public function courses_student_delete(Request $request)
    {
        $system_setting = SystemSetting::first();
        $registration_id = Registration::where('r_student_id', $request->u_id)
            ->where('r_course_id', $request->c_id)
            ->first();
        $check_if_exist = StudentCompany::where('sc_registration_id',$registration_id->r_id)->first();
        if (empty($check_if_exist)){
            $deleted = Registration::where('r_student_id', $request->u_id)
                ->where('r_course_id', $request->c_id)
                ->where('r_semester' , $system_setting->ss_semester_type)
                ->where('r_year' , $system_setting->ss_year)
                ->delete();

            if($deleted > 0) {
                $data = Registration::where('r_student_id' , $request->u_id)
                    ->where('r_semester' , $system_setting->ss_semester_type)
                    ->where('r_year' , $system_setting->ss_year)
                    ->get();
                $html = view('project.admin.users.ajax.coursesList' , ['data' => $data])->render();
                $r_course_id = Registration::where('r_student_id' , $request->u_id)
                    ->where('r_semester' , $system_setting->ss_semester_type)
                    ->where('r_year' , $system_setting->ss_year)
                    ->pluck('r_course_id')
                    ->toArray();
                $semester_courses = SemesterCourse::whereNotIn('sc_course_id' , $r_course_id)
                    ->pluck('sc_course_id')
                    ->toArray();
                $courses = Course::whereIn('c_id' , $semester_courses)->get();
                return response()->json(['html' => $html , 'courses' => $courses]);
        }
        }
        else{
            return response()->json(['status' => 'exist']);
        }
    }
    public function courses_student_add(Request $request)
    {
        $serializedData = $request->input('data');

        // Parse the serialized data into an array
        parse_str($serializedData, $parsedData);

        // Access the 'c_id' value
        $c_id = $parsedData['c_id'];
        $supervisor_id = $parsedData['supervisor_id'];
        $supervisors = User::get();
        $system_setting = SystemSetting::first();
        $registration = new Registration();
        $registration->r_student_id = $request->input('id');
        $registration->r_course_id = $c_id;
        $registration->supervisor_id = $supervisor_id;
        $registration->r_semester = $system_setting->ss_semester_type;
        $registration->r_year = $system_setting->ss_year;
        if($registration->save()) {
            $data = Registration::where('r_student_id' , $request->input('id'))
                                ->where('r_semester' , $system_setting->ss_semester_type)
                                ->where('r_year' , $system_setting->ss_year)
                                ->get();
            $html = view('project.admin.users.ajax.coursesList' , ['data' => $data])->render();
            $system_setting = SystemSetting::first();
            $r_course_id = Registration::where('r_student_id' , $request->input('id'))
                                            ->where('r_semester' , $system_setting->ss_semester_type)
                                            ->where('r_year' , $system_setting->ss_year)
                                            ->pluck('r_course_id')
                                            ->toArray();
            $semester_courses = SemesterCourse::whereNotIn('sc_course_id' , $r_course_id)
                                                ->pluck('sc_course_id')
                                                ->toArray();
            $courses = Course::whereIn('c_id' , $semester_courses)->get();
            $modal = view('project.admin.users.modals.add_courses_student' , ['courses' => $courses , 'supervisors'=>$supervisors])->render();

            return response()->json(['html' => $html , 'modal' => $modal]);
        }
    }
    public function create_or_update_grade(Request $request)
    {
        $registration = Registration::find($request->r_id);
        $registration->r_grade = $request->r_grade;
        if($registration->save()) {
            $data = Registration::where('r_student_id', $registration->r_student_id)->get();
            return response()->json(['x' => $data]);
            $html = view('project.admin.users.ajax.coursesList' , ['data' => $data]);
            return response()->json(['html' => $html]);
        }
    }
    public function courses_student($id)
    {
        $user = User::find($id);
        $supervisors = User::where('u_role_id' , 5)->get();
        $system_setting = SystemSetting::first();
        $r_course_id = Registration::where('r_student_id' , $id)
                                        ->where('r_semester' , $system_setting->ss_semester_type)
                                        ->where('r_year' , $system_setting->ss_year)
                                        ->pluck('r_course_id')
                                        ->toArray();
        $semester_courses = SemesterCourse::whereNotIn('sc_course_id' , $r_course_id)
                                            ->where('sc_semester' , $system_setting->ss_semester_type)
                                            ->where('sc_year' , $system_setting->ss_year)
                                            ->pluck('sc_course_id')
                                            ->toArray();
        $courses = Course::whereIn('c_id' , $semester_courses)->get();

        $data = Registration::where('r_student_id' , $id)
                                ->where('r_semester' , $system_setting->ss_semester_type)
                                ->where('r_year' , $system_setting->ss_year)
                                ->get();
        return view('project.admin.users.courese_student' , ['user' => $user , 'courses' => $courses , 'data' => $data , 'supervisors' => $supervisors]);
    }
    public function details($id)
    {        $student_for_supervisor = null;

        $user = User::find($id);
        $company_id = Company::where('c_manager_id' , $id)
                                ->pluck('c_id')
                                ->toArray();
        $company = Company::where('c_manager_id' , $id)->first();
        $students = StudentCompany::whereIn('sc_company_id', $company_id)
                                    ->get();
        $supervisors_assistant = null;
        if($user->u_role_id == 4) {
            $supervisors_assistant = SupervisorAssistant::where('sa_assistant_id' , $user->u_id)
                                                        ->get();
        }
        if($user->u_role_id == 3){
            $student_for_supervisor = User::whereIn('u_id',function($query) use ($id){
                $query->select('r_student_id')->from('registration')->where('supervisor_id', $id)->where('r_semester' , '=', SystemSetting::first()->ss_semester_type)->where('r_year' , '=', SystemSetting::first()->ss_year);
            })->get();
        }
        return view('project.admin.users.details' , ['user' => $user , 'students' => $students , 'company' => $company , 'supervisors_assistant' => $supervisors_assistant , 'student_for_supervisor'=>$student_for_supervisor]);
    }
    public function search(Request $request)
    {
        $data = null;
        if($request->data['u_role_id'] == null) {
            $data = User::where('u_username' , 'like' , '%' . $request->data['data'] . '%')
                        ->orWhere('name' , 'like' , '%' . $request->data['data'] . '%')
                        ->get();
        }
        else {
            $data = User::where('u_username', 'like', '%' . $request->data['data'] . '%')
                        ->where('u_role_id', $request->data['u_role_id']);

                    $data = $data->union(
                        User::where('name', 'like', '%' . $request->data['data'] . '%')
                            ->where('u_role_id', $request->data['u_role_id'])
                    )->get();

        }
        $role_id = $request->data['u_role_id'];
        $html = view('project.admin.users.ajax.usersList' , ['data' => $data , 'role_id' => $role_id , 'user_role' => $request->user_role])->render();
        return response()->json(['html' => $html]);
    }
    public function update(Request $request)
    {
        $validatedData = $request->validate([
            'u_username' => [
                'required',
                'regex:/^[a-zA-Z0-9_]+$/u', // Only allows English letters, numbers, and underscores
            ],
            'name' => 'required',
            'email' => [
                'required',
                'email',
                Rule::unique('users', 'email')->ignore($request->u_id , 'u_id'),
                'regex:/^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/u', // Email pattern validation
            ],
            'password' => 'nullable|min:8',
            'u_date_of_birth' => [
                'required',
                'date',
                'before_or_equal:' . Carbon::now()->format('Y-m-d'),
            ],
            'u_phone1' => 'required|digits:10',
            'u_phone2' => 'nullable|digits:10',
            'u_gender' => 'required'
        ],
        [
            'u_username.required' => __('translate.Username') // اسم المستخدم حقل مطلوب
            ,
            'u_username.regex' => __('translate.Username should only contain English letters, numbers, and underscores') // يجب أن يحتوي اسم المستخدم على أحرف وأرقام وشرطات سفلية باللغة الإنجليزية فقط
            ,
            'name.required' => __('translate.Name is required') // الاسم حقل مطلوب
            ,
            'email.required' => __('translate.Email is required') // البريد الإلكتروني حقل مطلوب
            ,
            'email.email' => __('translate.Email must be a valid email address') // البريد الإلكتروني يجب أن يكون صالحًا
            ,
            'email.unique' => __('translate.Email has already been used') // البريد الإلكتروني موجود بالفعل
            ,
            'email.regex' => __('translate.Email should contain English letters, numbers') // يجب أن يحتوي البريد الإلكتروني على أحرف وأرقام وشرطات سفلية باللغة الإنجليزية فقط
            ,
            'password.min' => __('translate.Password must be at least 8 characters long') // يجب أن تتكون كلمة المرور من 8  أرقام أو حروف
            ,
            'u_date_of_birth.required' => __('translate.Date of Birth is required') // تاريخ الميلاد حقل مطلوب
            ,
            'u_date_of_birth.date' => __('translate.Date of Birth is in an invalid format') // صيغة تاريخ الميلاد غير صالحة
            ,
            'u_date_of_birth.before_or_equal' => __("translate.Date of Birth must be before today's date") // يجب أن يكون تاريخ الميلاد في الماضي
            ,
            'u_phone1.required' => __('translate.Phone Number is required') // رقم الجوال حقل مطلوب
            ,
            'u_phone1.digits' => __('translate.Phone Number must be exactly 10 digits') // يجب أن يتكون رقم الجوال من عشرة أرقام فقط
            ,
            'u_phone2.digits' => __('translate.Alternative phone number must be exactly 10 digits') // يجب أن يتكون رقم الجوال الاحتياطي من عشرة أرقام فقط
            ,
            'u_gender' => __('translate.Gender must be selected') // يجب اختيار ذكر أو أنثى
        ]
        );
        $user = User::find($request->u_id);
        $user->u_username = $request->u_username;
        $user->name = $request->name;
        $user->email = $request->email;
        if(isset($request->password)) {
            $user->password = bcrypt($request->password);
        }
        $user->u_phone1 = $request->u_phone1;
        $user->u_phone2 = $request->u_phone2;
        $user->u_address = $request->u_address;
        $user->u_date_of_birth = $request->u_date_of_birth;
        $user->u_gender = $request->u_gender;
        $user->u_major_id = $request->u_major_id;
        $user->u_role_id = $request->u_role_id;
        $user->u_address_details = $request->u_address_details;
        $user->u_tawjihi_gpa = $request->u_tawjihi_gpa;
        $user->u_city_id = $request->u_city_id;
        if(isset($request->u_status)) {
            $user->u_status = 1;
        }
        else {
            $user->u_status = 0;
        }
        if ($user->save()) {
            return redirect()->back()->with('success', 'تم تعديل بيانات هذا المستخدم بنجاح');
        }
        else {
            return redirect()->back()->withErrors(['error' => 'حدثت مشكلة أثناء تحديث البيانات.'])->withInput();
        }
    }
    public function edit($id)
    {
        $user = User::find($id);
        $role_name = Role::find($user->u_role_id);
        $major_id = Major::where('m_id' , $user->u_major_id)->first();
        $role_id = Role::where('r_id' , $user->u_role_id)->first();
        $roles = Role::get();
        $majors = Major::get();
        $cities = CitiesModel::get();
        return view('project.admin.users.edit' , ['user' => $user , 'role_name' => $role_name->r_name , 'major_id' => $major_id , 'roles' => $roles , 'majors' => $majors , 'role_id' => $role_id,'cities'=>$cities]);
    }
    public function index_id($id)
    {
        $data = User::where('u_role_id' , $id)->get();
        $roles = Role::all();
        $major = Major::all();
        $role = Role::where('r_id' , $id)->first();
        $cities = CitiesModel::get();
        $role_name = $role->r_name;
        $supervisors = User::where('u_role_id' , 10)->get();
        $roles = Role::get();
        return view('project.admin.users.index' , ['data' => $data , 'roles' => $roles , 'user_role'=>$role , 'u_role_id' => $id , 'major' => $major , 'role_name' => $role_name,'cities'=>$cities , 'supervisors'=>$supervisors , 'roles'=>$roles]);
    }
    public function index()
    {
        $data = User::with('role')->get();
        foreach ($data as $key){
            $key->major = Major::where('m_id',$key->u_major_id)->first();
        }
        $roles = Role::all();
        $major = Major::all();
        $supervisors = User::where('u_role_id',10)->get();
        return view('project.admin.users.index', [
            'data' => $data,
            'roles' => $roles,
            'u_role_id' => null,
            'major' => $major,
            'role_name' => null,
            'user_role' => null,
            'supervisors'=>$supervisors
        ]);
    }
    public function create(Request $request)
    {
        $validatedData = $request->validate([
            'u_username' => [
                'required',
                'regex:/^[a-zA-Z0-9_]+$/u', // Only allows English letters, numbers, and underscores
            ],
            'name' => 'required',
            'email' => [
                'required',
                'email',
                'unique:users,email',
                'regex:/^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/u' // Email pattern validation
            ],
            'password' => 'required|min:8',
            'u_date_of_birth' => [
                'required',
                'date',
                'before_or_equal:' . Carbon::now()->format('Y-m-d'),
            ],
            'u_phone1' => 'required|digits:10',
            'u_phone2' => 'nullable|digits:10',
            'u_gender' => 'required'
        ],
        [
            'u_username.required' => __('translate.Username'), // اسم المستخدم حقل مطلوب
            'u_username.regex' => __('translate.Username should only contain English letters, numbers, and underscores'), // يجب أن يحتوي اسم المستخدم على أحرف وأرقام وشرطات سفلية باللغة الإنجليزية فقط
            'name.required' => __('translate.Name is required'), // الاسم حقل مطلوب
            'email.required' => __('translate.Email is required'), // البريد الإلكتروني حقل مطلوب
            'email.email' => __('translate.Email must be a valid email address'), // البريد الإلكتروني يجب أن يكون صالحًا
            'email.unique' => __('translate.Email has already been used'), // البريد الإلكتروني موجود بالفعل
            'email.regex' => __('translate.Email should contain English letters, numbers'), // يجب أن يحتوي البريد الإلكتروني على أحرف وأرقام وشرطات سفلية باللغة الإنجليزية فقط
            'password.required' => __('translate.Username is required'), // كلمة المرور حقل مطلوب
            'password.min' => __('translate.Password must be at least 8 characters long'), // يجب أن تتكون كلمة المرور من 8  أرقام أو حروف
            'u_date_of_birth.required' => __('translate.Date of Birth is required'), // تاريخ الميلاد حقل مطلوب
            'u_date_of_birth.date' => __('translate.Date of Birth is in an invalid format'), // صيغة تاريخ الميلاد غير صالحة
            'u_date_of_birth.before_or_equal' => __("translate.Date of Birth must be before today's date"), // يجب أن يكون تاريخ الميلاد في الماضي
            'u_phone1.required' => __('translate.Phone Number is required'), // رقم الجوال حقل مطلوب
            'u_phone1.digits' => __('translate.Phone Number must be exactly 10 digits'), // يجب أن يتكون رقم الجوال من عشرة أرقام فقط
            'u_phone2.digits' => __('translate.Alternative phone number must be exactly 10 digits'), // يجب أن يتكون رقم الجوال الاحتياطي من عشرة أرقام فقط
            'u_gender' => __('translate.Gender must be selected') // يجب اختيار ذكر أو أنثى
        ]
        );
        $user = new User();
        $user->name = $request->name;
        $user->email = $request->email;
        $user->password = bcrypt($request->password);
        $user->u_username = $request->u_username;
        $user->u_phone1 = $request->u_phone1;
        $user->u_phone2 = $request->u_phone2;
        $user->u_address = $request->u_address;
        $user->u_date_of_birth = $request->u_date_of_birth;
        $user->u_gender = $request->u_gender;
        $user->u_role_id = $request->u_role_id;
        $user->u_address_details = $request->u_address_details;
        $user->u_tawjihi_gpa = $request->u_tawjihi_gpa;
        $user->u_city_id = $request->u_city_id;
        if (isset($request->u_major_id)) {
            $user->u_major_id = $request->u_major_id;
        }
        else {
            $user->u_major_id = null;
        }
        if($request->u_role_id == 2) {
            $validatedData = $request->validate([
                'u_major_id' => 'required'
            ],
            [
                'u_major_id.required' => __('translate.Major is required') // التخصص حقل مطلوب
            ]
        );
        }
        if($user->save()) {
            $data = User::where('u_role_id', $request->u_role_id)->get();
            foreach ($data as $key){
                $key->major = Major::where('m_id',$key->u_major_id)->first();
            }
            $html = view('project.admin.users.ajax.usersList' , ['data' => $data, 'user_role' => $request->u_role_id])->render();
            return response()->json(['html' => $html]);
        }
        return response()->json(['errors' => ['Save failed']]);
    }
    public function check_email_not_duplicate(Request $request)
    {
        $user_email = User::where('email', $request->email)->first();
        if($user_email) {
            return response()->json(['status' => 'true']);
        }
        else {
            return response()->json(['status' => 'false']);
        }
    }
    public function searchStudentByName(Request $request)
    {
        $users = User::where('name', 'like', '%' . $request->value . '%')
                        ->pluck('u_id')
                        ->toArray();
        $company_id = Company::where('c_manager_id' , $request->user_id)
                                ->pluck('c_id')
                                ->toArray();
        $students = StudentCompany::whereIn('sc_company_id', $company_id)
                                    ->whereIn('sc_student_id' , $users)
                                    ->get();
        $html = view('project.admin.users.includes.student' , ['students' => $students])->render();
        return response()->json(['html' => $html]);
    }

    public function students_waiting_to_approve_cv(Request $request){
        $data = StudentCompanyNominationModel::
        whereIn('scn_student_id',function ($query) use ($request){
            $query->select('u_id')->from('users')->where('u_cv_status',0)
                ->where('name','like','%'.$request->input_search.'%');
        })
            ->when($request->filled('company_id'),function ($query) use ($request){
                $query->whereIn('scn_company_id',function ($query) use ($request){
                    $query->select('c_id')->from('companies')->where('c_id',$request->company_id);
                });
            })
            ->get();
        foreach ($data as $key){
            $key->student = User::where('u_id',$key->scn_student_id)->first();
            $key->company = Company::where('c_id',$key->scn_company_id)->first();
        }
        return response()->json([
            'success' => 'true',
            'view' => view('project.users.ajax.training_nominations_list',['data'=>$data])->render()
        ]);
    }

    public function change_status_from_cv(Request $request){
        $data = User::where('u_id',$request->id)->first();
        $data->u_cv_status = $request->status;
        if ($data->save()){
            return response()->json([
                'success' => ' true',
                'message' => 'تم تعديل الحالة بنجاح'
            ]);
        }
        else{
            return response()->json([
                'success' => ' false',
                'message' => 'هناك خلل ما'
            ]);
        }
    }

    public function students_files($id){
        $user = User::find($id);
        $data = FileAttachmentModel::where('table_name','users')->where('table_name_id',$id)->get();
        return view('project.admin.users.student_file_attachment',['data'=>$data,'user'=>$user]);
    }

    public function create_students_files(Request $request){
        $data = new FileAttachmentModel();
        $data->table_name = 'users';
        $data->table_name_id = $request->table_name_id;
        $data->added_by = auth()->user()->u_id;
        $data->note = $request->note;
        if ($request->hasFile('file')) {
            $file = $request->file('file');
            $extension = $file->getClientOriginalExtension();
            $filename = time() . '.' . $extension; // Unique filename
            $file->storeAs('students', $filename, 'public');
            $data->file = $filename;
        }
        if ($data->save()){
            return redirect()->route('admin.users.students.students_files',['id'=>$request->table_name_id])->with(['success' => 'تم اضافة البيانات بنجاح']);
        }
        else{
            return redirect()->route('admin.users.students.students_files',['id'=>$request->table_name_id])->with(['fail' => 'هناك خلل ما لم يتم اضافة البيانات']);
        }
    }

    public function change_user_role(Request $request){
        $data = User::where('u_id',$request->u_id)->first();
        $data->is_supervisor_id = $data->u_role_id;
        $data->u_role_id = $request->u_role_id;
        if($data->save()){
            return response()->json([
                'success'=>true,
                'message'=>'تم تعديل الصلاحية بنجاح'
            ]);
        }
    }
}

