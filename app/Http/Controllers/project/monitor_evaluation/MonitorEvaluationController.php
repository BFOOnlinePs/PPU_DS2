<?php

namespace App\Http\Controllers\project\monitor_evaluation;

use App\Exports\StudentAttendanceExport;
use App\Http\Controllers\Controller;
use App\Models\MeAttachmentModel;
use App\Models\StudentReport;
use Illuminate\Http\Request;
use App\Models\SemesterCourse;
use App\Models\SystemSetting;
use App\Models\Company;
use App\Models\StudentCompany;
use App\Models\Registration;
use App\Models\StudentAttendance;
use Carbon\Carbon;
use App\Models\CompaniesCategory;
use App\Models\Payment;
use App\Models\Currency;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;
use PDF;
use Illuminate\Support\Collection;
use App\Models\User;
use App\Models\Major;
use App\Models\CompanyBranch;

class MonitorEvaluationController extends Controller
{
    //
    public function index(){
        return view('project.monitor_evaluation.index');
    }

    public function user_details(){
        $data = User::where('u_id',auth()->user()->u_id)->first();
        return view('project.monitor_evaluation.user_details',['data'=>$data]);
    }

    public function update_password(Request $request){
        $data = User::where('u_id',auth()->user()->u_id)->first();
        if($request->filled('password')){
            $data->password = bcrypt($request->password);
        }
        if($data->save()){
            return redirect()->back()->with(['success' => 'تم تعديل كلمة المرور بنجاح']);
        }
    }

    public function companiesReport(){
        $systemSettings = SystemSetting::first();

        $semester = $systemSettings->ss_semester_type;
        $year = $systemSettings->ss_year;
        $companies = Company::where('c_id',0)->get();

        $data = Company::with('manager','companyCategories')->get();

        $categories = CompaniesCategory::get();

        foreach($data as $key){
            // $studentsTotal = StudentCompany::whereIn('sc_student_id', function ($query) use ($year, $semester) {
            //     $query->select('r_student_id')
            //         ->from('registration')
            //         ->where('r_year', $year)
            //         ->where('r_semester', $semester)
            //         ->distinct();
            // })
            $studentsTotal = StudentCompany::whereIn('sc_registration_id', function ($query) use ($year, $semester) {
                $query->select('r_id')
                    ->from('registration')
                    ->where('r_year', $year)
                    ->where('r_semester', $semester)
                    ->distinct();
            })
            ->where('sc_status', 1)
            ->where('sc_company_id',$key->c_id)
            ->select('sc_student_id')
            ->distinct()
            ->get();
            $key->studentsTotal = count($studentsTotal);
            $companies = $data->filter(function ($item) {
                return $item->studentsTotal > 0;
            });
        }

        $title = __("translate.Companies' Report");

        return view('project.monitor_evaluation.companiesReport',['data'=>$companies, 'semester'=>$semester,'categories'=>$categories,
        'companyCateg'=>__('translate.all_categories'),'companyType'=>__('translate.all_types'),'title'=>$title, 'year'=>$year
        ]);
    }

    public function companiesReportSearch(Request $request){
        $semester = $request->semester;
        $companyType = null;
        $companyCategory = null;
        $companies = Company::where('c_id',0)->get();
        $companyCateg = __('translate.all_categories');
        $companyTypeText =__('translate.all_types');

        $systemSettings = SystemSetting::first();
        $year = $systemSettings->ss_year;

        $categories = CompaniesCategory::get();

        if($request->companyType != 0 && $request->companyCategory != 0){ //هون بكون ببحث عن النوع والتصنيف
            $companyType = $request->companyType;
            $companyTypeText = $request->companyType;
            $companyCateg = CompaniesCategory::where('cc_id', $request->companyCategory)->value('cc_name');
            $companyCategory = $request->companyCategory;
            $data = Company::with('manager','companyCategories')->where('c_category_id',$companyCategory)
            ->where('c_type',$companyType)
            ->get();
        }
        else if($request->companyCategory != 0){ //هون بكون ببحث عن تصنيف الشركة
            $companyCateg = CompaniesCategory::where('cc_id', $request->companyCategory)->value('cc_name');
            $companyCategory = $request->companyCategory;
            $data = Company::with('manager','companyCategories')->where('c_category_id',$companyCategory)->get();
        }
        else if($request->companyType != 0){ //هون بكون ببحث عن نوع الشركة
            $companyType = $request->companyType;
            $companyTypeText = $request->companyType;
            $data = Company::with('manager','companyCategories')->where('c_type',$companyType)->get();
        }
        else if($companyType == 0 && $companyCategory == 0){ //هون بكون ببحث عن الفصل لحال
            $data = Company::with('manager','companyCategories')->get();
        }

        foreach($data as $key){
            if($semester != 0){
                // $studentsTotal = StudentCompany::whereIn('sc_student_id', function ($query) use ($year, $semester) {
                $studentsTotal = StudentCompany::whereIn('sc_registration_id', function ($query) use ($year, $semester) {
                    $query->select('r_id')
                        ->from('registration')
                        ->where('r_year', $year)
                        ->where('r_semester', $semester)
                        ->distinct();
                })
                // ->where('sc_status', 1)
                ->where('sc_company_id',$key->c_id)
                ->select('sc_student_id')
                ->distinct()
                ->get();
            }else{
                $year = __('translate.All Academic Years');
                // $studentsTotal = StudentCompany::whereIn('sc_student_id', function ($query) use ($year, $semester) {
                $studentsTotal = StudentCompany::
                // whereIn('sc_registration_id', function ($query) use ($year, $semester) {
                //     $query->select('r_id')
                //     ->from('registration')
                //     ->distinct();
                // })
                // ->where('sc_status', 1)
                // ->
                where('sc_company_id',$key->c_id)
                ->select('sc_student_id')
                ->distinct()
                ->get();
            }
            $key->studentsTotal = count($studentsTotal);
            $companies = $data->filter(function ($item) {
                return $item->studentsTotal > 0;
            });
        }

        return response()->json([
            'success'=>'true',
            'data'=> base64_encode(serialize($companies)),
            'view'=>view('project.monitor_evaluation.ajax.companiesReportTable',['data'=>$companies, 'semester'=>$semester,'categories'=>$categories])->render(),
            'semester'=>$semester,
            'companyCateg'=>$companyCateg,
            'companyType'=>$companyTypeText,
            'year'=>$year
        ]);

    }

    public function semesterReport(){
        $years = SemesterCourse::distinct()->pluck('sc_year')->toArray();
        $systemSettings = SystemSetting::first();

        $semester = $systemSettings->ss_semester_type;
        $year = $systemSettings->ss_year;

        $coursesStudentsTotal = count(Registration::where('r_year',$year)
        ->where('r_semester',$semester)
        ->select('r_student_id')
        ->distinct()
        ->get());

        $companiesTotal = count(Company::get());

        // $semesterCompanies = StudentCompany::whereIn('sc_student_id', function ($query) use ($year, $semester) {
        $semesterCompanies = StudentCompany::whereIn('sc_registration_id', function ($query) use ($year, $semester) {
            $query->select('r_id')
                ->from('registration')
                ->where('r_year', $year)
                ->where('r_semester', $semester)
                ->distinct();
        })
        // ->where('sc_status', 1)
        ->select('sc_company_id')
        ->get();

        $semesterCompaniesTotal = count(Company::whereIn('c_id',$semesterCompanies)->get());

        $semesterCoursesTotal = count(SemesterCourse::where('sc_semester',$semester)->where('sc_year',$year)->get());


        $trainings = StudentCompany::whereIn('sc_registration_id', function ($query) use ($year, $semester) {
            $query->select('r_id')
                ->from('registration')
                ->where('r_year', $year)
                ->where('r_semester', $semester)
                ->distinct();
        })
        ->pluck('sc_id')
        ->toArray();

        $attendanceRows = StudentAttendance::whereIn('sa_student_company_id', $trainings)->whereNotNull('sa_out_time')
        ->get();

        $hours = 0;
        $minutes = 0;

        foreach ($attendanceRows as $attendance) {
            $timeIn = Carbon::parse($attendance->sa_in_time);
            $timeOut = Carbon::parse($attendance->sa_out_time);

            $duration = $timeOut->diff($timeIn);

            $hours = $hours + $duration->format('%h');
            $minutes = $minutes + $duration->format('%i');

        }

        $hoursFromMinutes = (int)($minutes/60);
        $trainingHoursTotal= $hours + $hoursFromMinutes;
        $trainingMinutesTotal= $minutes - ($hoursFromMinutes*60);

        // $traineesTotal = count(StudentCompany::whereIn('sc_student_id', function ($query) use ($year, $semester) {
        $traineesTotal = count(StudentCompany::whereIn('sc_registration_id', function ($query) use ($year, $semester) {
            $query->select('r_id')
                ->from('registration')
                ->where('r_year', $year)
                ->where('r_semester', $semester)
                ->distinct();
        })
        // ->where('sc_status',1)
        ->select('sc_student_id')
        ->distinct()
        ->get());

        if($semester==1){
            // $semesterText = __('translate.First Semester Report');
            $semesterText = "الأول";
        }else if($semester==2){
            //  $semesterText = __('translate.Second Semester Report');
            $semesterText = "الثاني";
        }else{
            //  $semesterText = __('translate.Summer Semester Report');
            $semesterText = "الصيفي";
        }



        $yearText = __('translate.for Academic Year') . " " . $year;
        $concatenatedText = $semesterText . " " . $yearText;
        // $concatenatedText = "تقرير الفصل الدراسي"." ".$semesterText.;




        $data = [
            'title' => __('translate.Semester Report'),
            'semesterCompaniesTotal' => $semesterCompaniesTotal,
            'coursesStudentsTotal' => $coursesStudentsTotal,
            'companiesTotal'=>$companiesTotal,
            'semesterCoursesTotal'=>$semesterCoursesTotal,
            'traineesTotal'=>$traineesTotal,
            'trainingMinutesTotal'=>$trainingMinutesTotal,
            'trainingHoursTotal'=>$trainingHoursTotal,
            'gender'=>__('translate.all'),
            'semester'=>0,
            'company'=>__('translate.All Companies'),
            'branch'=>__('translate.all_branches'),
            'major'=>__('translate.All Majors'),
            'year'=>$year
        ];

        $majors = Major::get();
        $companies = Company::get();
        $company_no_student = Company::whereNotIn('c_id',StudentCompany::pluck('sc_company_id')->toArray())->where('c_status',1)->select('c_id')->count();

        return view('project.monitor_evaluation.semesterReport',['years'=>$years,'year'=>$year,'semester'=>$semester,'semesterCompaniesTotal'=>$semesterCompaniesTotal,
        'coursesStudentsTotal'=>$coursesStudentsTotal,'companiesTotal'=>$companiesTotal,'semesterCoursesTotal'=>$semesterCoursesTotal,
        'traineesTotal'=>$traineesTotal,'trainingMinutesTotal'=>$trainingMinutesTotal, 'trainingHoursTotal'=>$trainingHoursTotal, 'pdf'=> $data,
        'majors'=>$majors,'companies'=>$companies,'company_no_student'=>$company_no_student
        ]);
    }

    public function semesterReportAjax(Request $request){

        $semester = $request->semester;
        $year = $request->year;
        $genderText = __('translate.all');
        $majorText = __('translate.All Majors');
        $companyText = __('translate.All Companies');
        $branchText = __('translate.all_branches');



        // if($request->gender === 0){
        //     return 'REEM';
        //     $genderText = "ذكور";
        // }else{
        //     $genderText = "إناث";
        // }

        $query = User::query();
        if ($request->gender != -1) {
            $query->where('u_gender', $request->gender);

        }
        if ($request->major != -1) {
            $majorText = Major::where('m_id', $request->major)->value('m_name');
            $query->where('u_major_id', $request->major);
        }
        $students = $query->select('u_id');


        //إجمالي الطلاب المسجلين بالمساقات
        $coursesStudentsTotal = count(Registration::where('r_year',$year)
        ->where('r_semester',$semester)
        ->whereIn('r_student_id',$students)
        ->select('r_student_id')
        ->distinct()
        ->get());

        //حذف لاحقاً
        $companiesTotal = count(Company::get());

        // $semesterCompanies = StudentCompany::whereIn('sc_student_id', function ($query) use ($year, $semester) {


        $semesterCompanies = StudentCompany::whereIn('sc_registration_id', function ($query) use ($year, $semester,$students) {
            $query->select('r_id')
            ->from('registration')
            ->where('r_year', $year)
            ->where('r_semester', $semester)
            ->whereIn('r_student_id',$students)//new
            ->distinct();
        })
        // ->where('sc_status', 1)
        // ->where('sc_status','!=', 3)
        ->select('sc_company_id')
        ->get();


        if($request->company!=0){
            $semesterCompaniesTotal = count(Company::whereIn('c_id',$semesterCompanies)->where('c_id',$request->company)->get());
        }else{
            $semesterCompaniesTotal = count(Company::whereIn('c_id',$semesterCompanies)->get());
        }


        $semesterCoursesTotal = count(SemesterCourse::where('sc_semester',$semester)->where('sc_year',$year)->get());

        //here
        $query2 = StudentCompany::query();
        if ($request->company != 0) {
            $companyText = Company::where('c_id', $request->company)->value('c_name');
            $query2->where('sc_company_id', $request->company);
        }
        if($request->branch!=0){
            $branchText = CompanyBranch::where('b_id', $request->branch)->value('b_address');
            $query2->where('sc_branch_id', $request->branch);
        }
        $companyTrainings = $query2->select('sc_id');

        $trainings = StudentCompany::whereIn('sc_registration_id', function ($query) use ($year, $semester,$students) {
            $query->select('r_id')
                ->from('registration')
                ->where('r_year', $year)
                ->where('r_semester', $semester)
                ->whereIn('r_student_id',$students)
                ->distinct();
        })
        ->pluck('sc_id')
        ->toArray();

        $attendanceRows = StudentAttendance::whereIn('sa_student_company_id', $trainings)->whereNotNull('sa_out_time')
        ->whereIn('sa_student_company_id',$companyTrainings)
        ->get();

        $hours = 0;
        $minutes = 0;

        foreach ($attendanceRows as $attendance) {
            $timeIn = Carbon::parse($attendance->sa_in_time);
            $timeOut = Carbon::parse($attendance->sa_out_time);

            $duration = $timeOut->diff($timeIn);

            $hours = $hours + $duration->format('%h');
            $minutes = $minutes + $duration->format('%i');

        }

        $hoursFromMinutes = (int)($minutes/60);
        $trainingHoursTotal= $hours + $hoursFromMinutes;
        $trainingMinutesTotal= $minutes - ($hoursFromMinutes*60);

        // $traineesTotal = count(StudentCompany::whereIn('sc_student_id', function ($query) use ($year, $semester) {

        //here
        if($request->company!=0){
            if($request->branch!=0){
                $traineesTotal = count(StudentCompany::whereIn('sc_registration_id', function ($query) use ($year, $semester,$students) {
                    $query->select('r_id')
                        ->from('registration')
                        ->where('r_year', $year)
                        ->where('r_semester', $semester)
                        ->whereIn('r_student_id',$students)
                        ->distinct();
                })
                // ->where('sc_status',1) التأكد منها لاحقاً وبال index
                ->where('sc_company_id',$request->company)
                ->where('sc_branch_id',$request->branch)
                // ->where('sc_status','!=', 3)
                ->select('sc_student_id')
                ->distinct()
                ->get());
            }else{
                $traineesTotal = count(StudentCompany::whereIn('sc_registration_id', function ($query) use ($year, $semester,$students) {
                    $query->select('r_id')
                        ->from('registration')
                        ->where('r_year', $year)
                        ->where('r_semester', $semester)
                        ->whereIn('r_student_id',$students)
                        ->distinct();
                })
                // ->where('sc_status',1) التأكد منها لاحقاً وبال index
                ->where('sc_company_id',$request->company)
                // ->where('sc_status','!=', 3)
                ->select('sc_student_id')
                ->distinct()
                ->get());
            }
        }else{
            $traineesTotal = count(StudentCompany::whereIn('sc_registration_id', function ($query) use ($year, $semester,$students) {
                $query->select('r_id')
                    ->from('registration')
                    ->where('r_year', $year)
                    ->where('r_semester', $semester)
                    ->whereIn('r_student_id',$students)
                    ->distinct();
            })
            // ->where('sc_status',1) التأكد منها لاحقاً وبال index
            // ->where('sc_status','!=', 3)
            ->select('sc_student_id')
            ->distinct()
            ->get());
        }

        if($semester==1){
           $semesterText = __('translate.First Semester Report');
        }else if($semester==2){
            $semesterText = __('translate.Second Semester Report');
        }else{
            $semesterText = __('translate.Summer Semester Report');
        }

        $yearText = __('translate.for Academic Year') . " " . $year;
        $concatenatedText = $semesterText . " " . $yearText;

        $data = [
            'title' => __('translate.Semester Report'),
            'semesterCompaniesTotal' => $semesterCompaniesTotal,
            'coursesStudentsTotal' => $coursesStudentsTotal,
            'companiesTotal'=>$companiesTotal,
            'semesterCoursesTotal'=>$semesterCoursesTotal,
            'traineesTotal'=>$traineesTotal,
            'trainingMinutesTotal'=>$trainingMinutesTotal,
            'trainingHoursTotal'=>$trainingHoursTotal,
            'gender'=>$request->gender,
            'company'=>$companyText,
            'branch'=>$branchText,
            'major'=>$majorText,
            'year'=>$year
            // 'hi'=>0
        ];

        $data = base64_encode(serialize($data));

        $branches = null;
        if($request->company!=0){
            $branches = CompanyBranch::where('b_company_id',$request->company)->get();
        }


        return response()->json([
            'success'=>'true',
            'view'=>view('project.monitor_evaluation.ajax.semesterReportTable',[
            'coursesStudentsTotal'=>$coursesStudentsTotal,'companiesTotal'=>$companiesTotal,'semesterCoursesTotal'=>$semesterCoursesTotal,'semesterCompaniesTotal'=>$semesterCompaniesTotal,
            'traineesTotal'=>$traineesTotal,'trainingMinutesTotal'=>$trainingMinutesTotal, 'trainingHoursTotal'=>$trainingHoursTotal])->render(),
            'semester'=>$semester,'year'=>$year, 'pdf'=> $data, 'branches'=> $branches
        ]);

    }

    public function semesterReportPDF(Request $request){
        // $pdfData = unserialize(base64_decode($data));
        $pdfData = unserialize(base64_decode($request->test));
        $system_settings = SystemSetting::first();
        // $pdf = PDF::loadView('project.monitor_evaluation.pdf.semesterReportPDF', $pdfData);
        $pdf = PDF::loadView('project.monitor_evaluation.pdf.semesterReportPDF', ['data'=>$pdfData,'semester'=>$request->semesterTex , 'system_settings'=>$system_settings]);

        // Use the stream method to open the PDF in a new tab
        return $pdf->stream('semesterReport.pdf');
    }

    public function companiesReportPDF(Request $request){
        //return $request->test;

        $pdfData = unserialize(base64_decode($request->test));
        //return $pdfData;
        $pdf = PDF::loadView('project.monitor_evaluation.pdf.companiesReportPDF', ['data'=>$pdfData,
        'semester'=>$request->semesterText, 'title'=>$request->title, 'companyCateg'=>$request->companyCateg,'companyType'=>$request->companyTypeText,
        'year'=>$request->yearText
        ]);

        // Use the stream method to open the PDF in a new tab
        return $pdf->stream('semesterReport.pdf');
    }

    public function companiesPaymentsReport(){
        $years = SemesterCourse::distinct()->pluck('sc_year')->toArray();
        $systemSettings = SystemSetting::first();

        $semester = $systemSettings->ss_semester_type;
        $year = $systemSettings->ss_year;

        $companiesID = StudentCompany::whereIn('sc_registration_id', function ($query) use ($year, $semester) {
                        $query->select('r_id')
                        ->from('registration')
                        ->where('r_year', $year)
                        ->where('r_semester', $semester)
                        ->distinct();
                    })
                    ->where('sc_status', 1)
                    ->select('sc_company_id')
                    ->distinct()
                    ->get();

        $companies = Company::whereIn('c_id', $companiesID)->get();

        $trainingIDs = StudentCompany::whereIn('sc_registration_id', function ($query) use ($year, $semester) {
            $query->select('r_id')
                ->from('registration')
                ->where('r_year', $year)
                ->where('r_semester', $semester);
        })
        ->select('sc_id')
        ->get();

        $endIDs = Payment::whereIn('p_student_company_id', $trainingIDs)
        ->get()
        ->unique('p_student_company_id')
        ->pluck('p_student_company_id');


        $currencies = Currency::select('c_id','c_symbol')->get();

        $paymentCollection = new Collection();


        $currunciesKeysToCheck = Currency::select('c_id')->pluck('c_id')->toArray();
        foreach($endIDs as $test){

            //هون عندي كل الدفعات اللي للتدريب هاد
            $paymentsForTrain = Payment::with('userStudent', 'payments')->where('p_student_company_id', $test)->get();

            //بدي احط اوبجيكت جديد عشان احط فيه حقول جديدة
            $objectToreturnView = $paymentsForTrain->first();



            $paymentsTotalCollection = new Collection();
            $approvedPaymentsTotalCollection = new Collection();

            foreach($currunciesKeysToCheck as $key){
                $currencyTotal = $paymentsForTrain->where('p_currency_id',$key)->sum('p_payment_value');//هون بعطيني المجموع لكل عملة واذا ما كانت موجودة بعطي 0
                $paymentsTotalCollection->add(['c_id' => $key, 'total' => $currencyTotal, 'symbol'=>$currencies->where('c_id',$key)->first()->c_symbol]);

                $currencyApprovedTotal = $paymentsForTrain->where('p_currency_id',$key)->where('p_status',1)->sum('p_payment_value');//هون بعطيني المجموع لكل عملة واذا ما كانت موجودة بعطي 0
                $approvedPaymentsTotalCollection->add(['c_id' => $key, 'total' => $currencyApprovedTotal, 'symbol'=>$currencies->where('c_id',$key)->first()->c_symbol]);
            }

            $objectToreturnView->paymentsTotalCollection = $paymentsTotalCollection;
            $objectToreturnView->approvedPaymentsTotalCollection = $approvedPaymentsTotalCollection;

            $paymentCollection->add($objectToreturnView);

        }

        $allPayments = Payment::whereIn('p_student_company_id', $trainingIDs)
        ->get();

        $totalCollection = new Collection();
        $totalApprovedCollection = new Collection();

        foreach($currunciesKeysToCheck as $key){
            $currencyTotal = $allPayments->where('p_currency_id',$key)->sum('p_payment_value');//هون بعطيني المجموع لكل عملة واذا ما كانت موجودة بعطي 0
            $totalCollection->add(['c_id' => $key, 'total' => $currencyTotal, 'symbol'=>$currencies->where('c_id',$key)->first()->c_symbol]);

            $currencyApprovedTotal = $allPayments->where('p_currency_id',$key)->where('p_status',1)->sum('p_payment_value');//هون بعطيني المجموع لكل عملة واذا ما كانت موجودة بعطي 0
            $totalApprovedCollection->add(['c_id' => $key, 'total' => $currencyApprovedTotal, 'symbol'=>$currencies->where('c_id',$key)->first()->c_symbol]);
        }

        // return $totalApprovedCollection;

        return view('project.monitor_evaluation.companiesPaymentsReport',['years'=>$years,'year'=>$year,'semester'=>$semester,
        'companies'=>$companies,'companiesPayments'=>$paymentCollection,'totalApprovedCollection'=>$totalApprovedCollection,
        'totalCollection'=>$totalCollection
        ]);

    }

    public function companyPaymentDetailes(Request $request){

        $trainingPayment = unserialize(base64_decode($request->test));

        $trainingID = Payment::where('p_id',$trainingPayment->p_id)->select('p_student_company_id')->get()->first()->p_student_company_id;

        $trainingPayments = Payment::with('userStudent', 'payments','currency')->where('p_student_company_id',$trainingID)->get();


        return view('project.monitor_evaluation.companyPaymentDetailes',['payments'=>$trainingPayments,'trainingPayment'=>$trainingPayment]);
    }

    public function companiesPaymentsSearch(Request $request){

        $company_id = $request->company;
        $semester = $request->semester;
        $year = $request->year;

        if($company_id != 0){//يعني هون ببحث عن شركة

            //التدريبات التي تنتمي إلى هذا الفصل والعام والشركة
            $trainingIDs = StudentCompany::whereIn('sc_registration_id', function ($query) use ($year, $semester) {
                $query->select('r_id')
                    ->from('registration')
                    ->where('r_year', $year)
                    ->where('r_semester', $semester);
            })
            ->where('sc_company_id', $company_id)
            ->select('sc_id')
            ->get();



        }else{
            $trainingIDs = StudentCompany::whereIn('sc_registration_id', function ($query) use ($year, $semester) {
                $query->select('r_id')
                    ->from('registration')
                    ->where('r_year', $year)
                    ->where('r_semester', $semester);
            })
            ->select('sc_id')
            ->get();
        }

        $endIDs = Payment::whereIn('p_student_company_id', $trainingIDs)
        ->get()
        ->unique('p_student_company_id')
        ->pluck('p_student_company_id');


        $currencies = Currency::select('c_id','c_symbol')->get();

        $paymentCollection = new Collection();


        $currunciesKeysToCheck = Currency::select('c_id')->pluck('c_id')->toArray();
        foreach($endIDs as $test){

            //هون عندي كل الدفعات اللي للتدريب هاد
            $paymentsForTrain = Payment::with('userStudent', 'payments')->where('p_student_company_id', $test)->get();

            //بدي احط اوبجيكت جديد عشان احط فيه حقول جديدة
            $objectToreturnView = $paymentsForTrain->first();



            $paymentsTotalCollection = new Collection();
            $approvedPaymentsTotalCollection = new Collection();

            foreach($currunciesKeysToCheck as $key){
                $currencyTotal = $paymentsForTrain->where('p_currency_id',$key)->sum('p_payment_value');//هون بعطيني المجموع لكل عملة واذا ما كانت موجودة بعطي 0
                $paymentsTotalCollection->add(['c_id' => $key, 'total' => $currencyTotal, 'symbol'=>$currencies->where('c_id',$key)->first()->c_symbol]);

                $currencyApprovedTotal = $paymentsForTrain->where('p_currency_id',$key)->where('p_status',1)->sum('p_payment_value');//هون بعطيني المجموع لكل عملة واذا ما كانت موجودة بعطي 0
                $approvedPaymentsTotalCollection->add(['c_id' => $key, 'total' => $currencyApprovedTotal, 'symbol'=>$currencies->where('c_id',$key)->first()->c_symbol]);
            }

            $objectToreturnView->paymentsTotalCollection = $paymentsTotalCollection;
            $objectToreturnView->approvedPaymentsTotalCollection = $approvedPaymentsTotalCollection;

            $paymentCollection->add($objectToreturnView);

        }

        $allPayments = Payment::whereIn('p_student_company_id', $trainingIDs)
        ->get();

        $totalCollection = new Collection();
        $totalApprovedCollection = new Collection();

        foreach($currunciesKeysToCheck as $key){
            $currencyTotal = $allPayments->where('p_currency_id',$key)->sum('p_payment_value');//هون بعطيني المجموع لكل عملة واذا ما كانت موجودة بعطي 0
            $totalCollection->add(['c_id' => $key, 'total' => $currencyTotal, 'symbol'=>$currencies->where('c_id',$key)->first()->c_symbol]);

            $currencyApprovedTotal = $allPayments->where('p_currency_id',$key)->where('p_status',1)->sum('p_payment_value');//هون بعطيني المجموع لكل عملة واذا ما كانت موجودة بعطي 0
            $totalApprovedCollection->add(['c_id' => $key, 'total' => $currencyApprovedTotal, 'symbol'=>$currencies->where('c_id',$key)->first()->c_symbol]);
        }


        return response()->json([
            'success'=>'true',
            'data'=>$paymentCollection,
            'view'=>view('project.monitor_evaluation.ajax.companiesPaymentsReportTable',['companiesPayments'=>$paymentCollection
            ,'totalApprovedCollection'=>$totalApprovedCollection,'totalCollection'=>$totalCollection
            ])->render(),
        ]);
    }

    public function paymentsReport(){

        $years = SemesterCourse::distinct()->pluck('sc_year')->toArray();
        $systemSettings = SystemSetting::first();

        $semester = $systemSettings->ss_semester_type;
        $year = $systemSettings->ss_year;

        $trainingIDs = StudentCompany::whereIn('sc_registration_id', function ($query) use ($year, $semester) {
            $query->select('r_id')
            ->from('registration')
            ->where('r_year', $year)
            ->where('r_semester', $semester)
            ->distinct();
        })
        // ->where('sc_status', 1)
        ->select('sc_id')
        ->distinct()
        ->get();

        $companiesID = StudentCompany::whereIn('sc_registration_id', function ($query) use ($year, $semester) {
            $query->select('r_id')
            ->from('registration')
            ->where('r_year', $year)
            ->where('r_semester', $semester)
            ->distinct();
        })
        // ->where('sc_status', 1)
        ->select('sc_company_id')
        ->distinct()
        ->get();

        $companies = Company::whereIn('c_id', $companiesID)->get();
        $payments = Payment::with('userStudent', 'payments','currency')->whereIn('p_student_company_id',$trainingIDs)->get();

        $students = User::whereIn('u_id', function ($query) use ($year, $semester) {
            $query->select('r_student_id')
            ->from('registration')
            ->where('r_year', $year)
            ->where('r_semester', $semester)
            ->distinct();
        })
        ->get();

        return view('project.monitor_evaluation.paymentsReport',['years'=>$years,'year'=>$year,
        'semester'=>$semester,'payments'=>$payments,'companies'=>$companies,'students'=>$students]);
    }

    public function paymentsReportSearch(Request $request){

        $semester = $request->semester;
        $year = $request->year;
        $trainings = StudentCompany::where('sc_id',0)->get();

        $trainingIDs = StudentCompany::whereIn('sc_registration_id', function ($query) use ($year, $semester) {
            $query->select('r_id')
            ->from('registration')
            ->where('r_year', $year)
            ->where('r_semester', $semester)
            ->distinct();
        })
        // ->select('sc_id')
        // ->distinct()
        ->get();

        $trainingsFilterCompany = StudentCompany::whereIn('sc_registration_id', function ($query) use ($year, $semester) {
            $query->select('r_id')
            ->from('registration')
            ->where('r_year', $year)
            ->where('r_semester', $semester)
            ->distinct();
        })
        // ->where('sc_status', 1)
        ->select('sc_id')
        ->distinct()
        ->get();

        if($request->company!=0 && $request->student!=0){

            $trainingsFilterCompany = $trainingIDs->filter(function ($item) use ($request){
                return ($item->sc_company_id == $request->company && $item->sc_student_id == $request->student);
            })->pluck('sc_id')->toArray();

        }



        if($request->company!=0&&$request->student==0){
            //do filter according to company
            $trainingsFilterCompany = $trainingIDs->filter(function ($item) use ($request) {
                return $item->sc_company_id == $request->company;
            })->pluck('sc_id')->toArray();
        }
        if($request->student!=0&&$request->company==0){

            $trainingsFilterCompany = $trainingIDs->filter(function ($item) use ($request){
                return $item->sc_student_id == $request->student;
            })->pluck('sc_id')->toArray();

        }

        // $payments = Payment::with('userStudent', 'payments','currency')->whereIn('p_student_company_id',$trainings)->get();
        if($request->company==0&&$request->student==0){
            $payments = Payment::with('userStudent', 'payments','currency')->whereIn('p_student_company_id',$trainingsFilterCompany)->get();
        }else{
            $payments = Payment::with('userStudent', 'payments','currency')->whereIn('p_student_company_id',$trainingsFilterCompany)->get();
        }

        if($request->status!=2){
            //do filter according to status
            $payments = Payment::with('userStudent', 'payments','currency')
            ->whereIn('p_student_company_id',$trainingsFilterCompany)
            ->where('p_status',$request->status)
            ->get();
        }

        return response()->json([
            'success'=>'true',
            'trainings'=>$payments,
            'view'=>view('project.monitor_evaluation.ajax.paymentsReportTable',['payments'=>$payments])->render(),
        ]);
    }

    //new reports
    public function students_courses_report(){

        $years = SemesterCourse::distinct()->pluck('sc_year')->toArray();
        $majors = Major::get();

        $systemSettings = SystemSetting::first();

        $semester = $systemSettings->ss_semester_type;
        $year = $systemSettings->ss_year;


        $data = Registration::select('r_student_id')->with('courses','users')->distinct('r_student_id')
        ->where('r_semester',$semester)
        ->where('r_year',$year)
        ->groupBy('r_student_id')->get();

        // if($semester==1){
        //     $semesterText =
        // }else if($semester==2){

        // }else{

        // }


        $title = __('translate.student_enrolled_in_courses_report');

        foreach($data as $key){
            $key->coursesNum = Registration::select('r_course_id')
            ->where('r_student_id',$key->r_student_id)
            ->where('r_semester',$semester)
            ->where('r_year',$year)
            ->get()
            ->count();
        }

        return view('project.monitor_evaluation.students_courses_report',['data'=>$data,'semester'=>$semester,
        'year'=>$year,'years'=>$years,'majors'=>$majors,'majorText'=>__('translate.All Majors'),'gender'=>__('translate.all'),'title'=>$title]);

    }
    public function courses_registered_report(){
        //إجمالي المساقات لهذا الفصل
        $systemSettings = SystemSetting::first();

        $semester = $systemSettings->ss_semester_type;
        $year = $systemSettings->ss_year;
        $years = SemesterCourse::distinct()->pluck('sc_year')->toArray();

        $data = Registration::select('r_course_id')->with('courses','users')->distinct('r_course_id')
        ->where('r_semester',$semester)
        ->where('r_year',$year)
        ->groupBy('r_course_id')->get();

        foreach($data as $key){
            $key->studentsNum = Registration::select('r_student_id')
            ->where('r_course_id',$key->r_course_id)
            ->where('r_semester',$semester)
            ->where('r_year',$year)
            ->get()
            ->count();
        }

        $title = __('translate.enrolled_courses_report');

        return view('project.monitor_evaluation.courses_registered_report',['data'=>$data,'semester'=>$semester
        , 'year'=>$year,'years'=>$years,'gender'=>__('translate.all'),'title'=>$title]);
    }
    public function training_hours_report(){
        $systemSettings = SystemSetting::first();

        $semester = $systemSettings->ss_semester_type;
        $year = $systemSettings->ss_year;
        $years = SemesterCourse::distinct()->pluck('sc_year')->toArray();

        $students_have_trainings = StudentCompany::whereIn('sc_registration_id', function ($query) use ($year, $semester) {
            $query->select('r_id')
            ->from('registration')
            ->where('r_year', $year)
            ->where('r_semester', $semester)
            ->distinct();
        })
        ->select('sc_student_id')->distinct('sc_student_id')
        ->groupBy('sc_student_id')
        ->get();


        $students_have_trainings = StudentCompany::select('sc_student_id')->with('users')->distinct('sc_student_id')
        ->whereIn('sc_registration_id', function ($query) use ($year, $semester) {
            $query->select('r_id')
            ->from('registration')
            ->where('r_year', $year)
            ->where('r_semester', $semester)
            ->distinct();
        })
        ->groupBy('sc_student_id')
        ->get();

        foreach($students_have_trainings as $key){
            $studentID = $key->sc_student_id;

            //فيها تدريبات الطالب للوقت الحالي
            $student_companies = StudentCompany::select('sc_id')
            ->whereIn('sc_registration_id', function ($query) use ($year, $semester) {
                $query->select('r_id')
                ->from('registration')
                ->where('r_year', $year)
                ->where('r_semester', $semester)
                ->distinct();
            })
            ->where('sc_student_id',$studentID)
            ->select('sc_id')
            ->pluck('sc_id')
            ->toArray();

            $attendanceRows = StudentAttendance::whereIn('sa_student_id', function ($query) use ($year, $semester) {
                $query->select('r_student_id')
                    ->from('registration')
                    ->where('r_year', $year)
                    ->where('r_semester', $semester)
                    ->distinct();
            })->whereNotNull('sa_out_time')
            ->whereIn('sa_student_company_id',$student_companies)
            ->get();

            $key->attendanceRows = $attendanceRows;

            $hours = 0;
            $minutes = 0;

            foreach ($attendanceRows as $attendance) {
                $timeIn = Carbon::parse($attendance->sa_in_time);
                $timeOut = Carbon::parse($attendance->sa_out_time);

                $duration = $timeOut->diff($timeIn);

                $hours = $hours + $duration->format('%h');
                $minutes = $minutes + $duration->format('%i');

            }

            $hoursFromMinutes = (int)($minutes/60);
            $key->trainingHoursTotal = $hours + $hoursFromMinutes;
            $key->trainingMinutesTotal = $minutes - ($hoursFromMinutes*60);
        }

        $totalHours = 0;
        $totalMinutes = 0;
        foreach($students_have_trainings as $key){
            $totalHours = $totalHours + $key->trainingHoursTotal;
            $totalMinutes = $totalMinutes + $key->trainingMinutesTotal;
        }

        $hoursFromMinutes2 = (int)($totalMinutes/60);
        $totalHours = $totalHours + $hoursFromMinutes2;
        $totalMinutes = $totalMinutes - ($hoursFromMinutes2*60);


        $majors = Major::get();
        $title = __('translate.trainees_training_hours_report');

        return view('project.monitor_evaluation.training_hours_report',['data'=>$students_have_trainings,'semester'=>$semester
        , 'year'=>$year,'years'=>$years,'majors'=>$majors
        ,'majorText'=>__('translate.All Majors'),'gender'=>__('translate.all'),'title'=>$title,'totalMinutes'=>$totalMinutes,'totalHours'=>$totalHours
        ]);
    }
    public function students_companies_report(){
        $systemSettings = SystemSetting::first();

        $semester = $systemSettings->ss_semester_type;
        $year = $systemSettings->ss_year;
        $years = SemesterCourse::distinct()->pluck('sc_year')->toArray();

        $majors = Major::get();

        $data = StudentCompany::select('sc_student_id')->with('users')->distinct('sc_student_id')
        ->whereIn('sc_registration_id', function ($query) use ($year, $semester) {
            $query->select('r_id')
            ->from('registration')
            ->where('r_year', $year)
            ->where('r_semester', $semester)
            ->distinct();
        })
        ->groupBy('sc_student_id')->get();

        foreach($data as $key){
            $key->companiesNum = StudentCompany::select('sc_company_id')
            ->where('sc_student_id',$key->sc_student_id)
            ->whereIn('sc_registration_id', function ($query) use ($year, $semester) {
                $query->select('r_id')
                ->from('registration')
                ->where('r_year', $year)
                ->where('r_semester', $semester)
                ->distinct();
            })
            ->distinct('sc_company_id')
            ->get()
            ->count();
        }

        // return $data;
        $title = __('translate.trainees_report');

        return view('project.monitor_evaluation.students_companies_report',['data'=>$data,'semester'=>$semester,
         'year'=>$year,'years'=>$years,'majors'=>$majors
         ,'majorText'=>__('translate.All Majors'),'gender'=>__('translate.all'),'title'=>$title]);
    }

    //new reports ajax
    public function studentsCoursesAjax(Request $request){
        $semester = $request->semester;
        $year = $request->year;
        $majorText=__('translate.All Majors');

        $query = User::query();
        if ($request->gender != -1) {
            $query->where('u_gender', $request->gender);
        }
        if ($request->major != 0) {
            $majorText = Major::where('m_id', $request->major)->value('m_name');
            $query->where('u_major_id', $request->major);
        }
        $students = $query->select('u_id');

        $data = Registration::select('r_student_id')->with('courses','users')->distinct('r_student_id')
        ->where('r_semester',$semester)
        ->where('r_year',$year)
        ->whereIn('r_student_id',$students)
        ->groupBy('r_student_id')->get();

        foreach($data as $key){
            $key->coursesNum = Registration::select('r_course_id')
            ->where('r_student_id',$key->r_student_id)
            ->where('r_semester',$semester)
            ->where('r_year',$year)
            ->get()
            ->count();
        }


        return response()->json([
            'success'=>'true',
            'data'=> base64_encode(serialize($data)),
            'view'=>view('project.monitor_evaluation.ajax.studentsCoursesReportTable',['data'=>$data])->render(),
            'gender'=>$request->gender,
            'semester'=>$request->semester,
            'majorText'=>$majorText,
            'year'=>$year
        ]);


    }

    public function registeredCoursesAjax(Request $request){
        $semester = $request->semester;
        $year = $request->year;

        $query = User::query();
        if ($request->gender != -1) {
            $query->where('u_gender', $request->gender);
        }

        $students = $query->select('u_id');

        $data = Registration::select('r_course_id')->with('courses','users')->distinct('r_course_id')
        ->where('r_semester',$semester)
        ->where('r_year',$year)
        ->groupBy('r_course_id')->get();

        foreach($data as $key){
            $key->studentsNum = Registration::select('r_student_id')
            ->where('r_course_id',$key->r_course_id)
            ->where('r_semester',$semester)
            ->where('r_year',$year)
            ->whereIn('r_student_id',$students)
            ->get()
            ->count();
        }

        return response()->json([
            'success'=>'true',
            'data'=> base64_encode(serialize($data)),
            'view'=>view('project.monitor_evaluation.ajax.registeredCoursesReportTable',['data'=>$data])->render(),
            'gender'=>$request->gender,
            'semester'=>$semester,
            'year'=>$year
        ]);


    }

    public function trainingHoursAjax(Request $request){

        $semester = $request->semester;
        $year = $request->year;
        $majorText=__('translate.All Majors');

        $query = User::query();
        if ($request->gender != -1) {
            $query->where('u_gender', $request->gender);
        }
        if ($request->major != 0) {
            $majorText = Major::where('m_id', $request->major)->value('m_name');
            $query->where('u_major_id', $request->major);
        }

        $students = $query->select('u_id');



        $students_have_trainings = StudentCompany::select('sc_student_id')->with('users')->distinct('sc_student_id')
        ->whereIn('sc_registration_id', function ($query) use ($year, $semester,$students) {
            $query->select('r_id')
            ->from('registration')
            ->where('r_year', $year)
            ->where('r_semester', $semester)
            ->whereIn('r_student_id',$students)
            ->distinct();
        })
        ->groupBy('sc_student_id')
        ->get();

        foreach($students_have_trainings as $key){
            $studentID = $key->sc_student_id;

            //فيها تدريبات الطالب للوقت الحالي
            $student_companies = StudentCompany::select('sc_id')
            ->whereIn('sc_registration_id', function ($query) use ($year, $semester,$students) {
                $query->select('r_id')
                ->from('registration')
                ->where('r_year', $year)
                ->where('r_semester', $semester)
                ->whereIn('r_student_id',$students)
                ->distinct();
            })
            ->where('sc_student_id',$studentID)
            ->select('sc_id')
            ->pluck('sc_id')
            ->toArray();

            $attendanceRows = StudentAttendance::whereIn('sa_student_id', function ($query) use ($year, $semester) {
                $query->select('r_student_id')
                    ->from('registration')
                    ->where('r_year', $year)
                    ->where('r_semester', $semester)
                    ->distinct();
            })->whereNotNull('sa_out_time')
            ->whereIn('sa_student_company_id',$student_companies)
            ->get();

            $key->attendanceRows = $attendanceRows;

            $hours = 0;
            $minutes = 0;

            foreach ($attendanceRows as $attendance) {
                $timeIn = Carbon::parse($attendance->sa_in_time);
                $timeOut = Carbon::parse($attendance->sa_out_time);

                $duration = $timeOut->diff($timeIn);

                $hours = $hours + $duration->format('%h');
                $minutes = $minutes + $duration->format('%i');

            }

            $hoursFromMinutes = (int)($minutes/60);
            $key->trainingHoursTotal = $hours + $hoursFromMinutes;
            $key->trainingMinutesTotal = $minutes - ($hoursFromMinutes*60);
        }

        $totalHours = 0;
        $totalMinutes = 0;
        foreach($students_have_trainings as $key){
            $totalHours = $totalHours + $key->trainingHoursTotal;
            $totalMinutes = $totalMinutes + $key->trainingMinutesTotal;
        }

        $hoursFromMinutes2 = (int)($totalMinutes/60);
        $totalHours = $totalHours + $hoursFromMinutes2;
        $totalMinutes = $totalMinutes - ($hoursFromMinutes2*60);

        return response()->json([
            'success'=>'true',
            'data'=> base64_encode(serialize($students_have_trainings)),
            'view'=>view('project.monitor_evaluation.ajax.trainingHoursTable',['data'=>$students_have_trainings,'totalMinutes'=>$totalMinutes,'totalHours'=>$totalHours])->render(),
            'gender'=>$request->gender,
            'semester'=>$request->semester,
            'majorText'=>$majorText,
            'year'=>$year
        ]);


    }

    public function studentsCompaniesAjax(Request $request){
        $semester = $request->semester;
        $year = $request->year;
        $majorText=__('translate.All Majors');

        $query = User::query();
        if ($request->gender != -1) {
            $query->where('u_gender', $request->gender);
        }
        if ($request->major != 0) {
            $query->where('u_major_id', $request->major);
            $majorText = Major::where('m_id', $request->major)->value('m_name');
        }
        $students = $query->select('u_id');

        $data = StudentCompany::select('sc_student_id')->with('users')->distinct('sc_student_id')
        ->whereIn('sc_registration_id', function ($query) use ($year, $semester, $students) {
            $query->select('r_id')
            ->from('registration')
            ->where('r_year', $year)
            ->where('r_semester', $semester)
            ->whereIn('r_student_id',$students)
            ->distinct();
        })
        ->groupBy('sc_student_id')->get();

        foreach($data as $key){
            $key->companiesNum = StudentCompany::select('sc_company_id')
            ->where('sc_student_id',$key->sc_student_id)
            ->whereIn('sc_registration_id', function ($query) use ($year, $semester) {
                $query->select('r_id')
                ->from('registration')
                ->where('r_year', $year)
                ->where('r_semester', $semester)
                ->distinct();
            })
            ->distinct('sc_company_id')
            ->get()
            ->count();
        }

        return response()->json([
            'success'=>'true',
            'data'=> base64_encode(serialize($data)),
            'view'=>view('project.monitor_evaluation.ajax.studentsCompaniesReportTable',['data'=>$data])->render(),
            'gender'=>$request->gender,
            'semester'=>$request->semester,
            'majorText'=>$majorText,
            'year'=>$year
        ]);


    }

    //new reports pdf
    public function studentsCoursesPDF(Request $request){

        $pdfData = unserialize(base64_decode($request->test));

        $pdf = PDF::loadView('project.monitor_evaluation.pdf.studentsCoursesPDF'
        , ['data'=>$pdfData,'gender'=>$request->genderText,
        'majorText'=>$request->majorText, 'semester'=>$request->semesterText, 'title'=>$request->title, 'year'=>$request->yearText]);

        // Use the stream method to open the PDF in a new tab
        return $pdf->stream('studentsCoursesPDF.pdf');
    }

    public function registeredCoursesPDF(Request $request){

        $pdfData = unserialize(base64_decode($request->test));

        $pdf = PDF::loadView('project.monitor_evaluation.pdf.registeredCoursesPDF', ['data'=>$pdfData,
        'semester'=>$request->semesterText, 'title'=>$request->title,'gender'=>$request->genderText, 'year'=>$request->yearText]);

        return $pdf->stream('registeredCoursesPDF.pdf');
    }

    public function  trainingHoursPDF(Request $request){

        $pdfData = unserialize(base64_decode($request->test));

        $pdf = PDF::loadView('project.monitor_evaluation.pdf.trainingHoursPDF', ['data'=>$pdfData,
        'gender'=>$request->genderText,
        'majorText'=>$request->majorText, 'semester'=>$request->semesterText, 'title'=>$request->title, 'year'=>$request->yearText]);

        return $pdf->stream('trainingHoursPDF.pdf');
    }

    public function  studentsCompaniesPDF(Request $request){

        $pdfData = unserialize(base64_decode($request->test));

        $pdf = PDF::loadView('project.monitor_evaluation.pdf.studentsCompaniesPDF', ['data'=>$pdfData,
        'gender'=>$request->genderText,
        'majorText'=>$request->majorText, 'semester'=>$request->semesterText, 'title'=>$request->title, 'year'=>$request->yearText]);

        return $pdf->stream('studentsCompaniesPDF.pdf');
    }


    //details
    public function companyStudents($id){

        $systemSettings = SystemSetting::first();

        $semester = $systemSettings->ss_semester_type;
        $year = $systemSettings->ss_year;

        // $years = SemesterCourse::distinct()->pluck('sc_year')->toArray();

        $majors = Major::get();


        $data = StudentCompany::select('sc_student_id')->with('users')->where('sc_company_id',$id)->distinct('sc_student_id')
        ->whereIn('sc_registration_id', function ($query) use ($year, $semester) {
            $query->select('r_id')
            ->from('registration')
            ->where('r_year', $year)
            ->where('r_semester', $semester)
            ->distinct();
        })
        ->groupBy('sc_student_id')->get();

        foreach($data as $key){
            $major = $key->users->u_major_id;
            $majorText = Major::where('m_id', $major)->value('m_name');
            $key->major = $majorText;
        }
        // return $data;

        $companyName = Company::where('c_id', $id)->value('c_name');

        return view('project.monitor_evaluation.companyStudents',['data'=>$data,'semester'=>$semester,
        'majors'=>$majors, 'company_name'=>$companyName]);
    }

    public function companyStudentsReportSearch(Request $request){


        $semester = $request->semester;

        $query = User::query();
        if ($request->gender != -1) {
            $query->where('u_gender', $request->gender);
        }
        if ($request->major != -1) {
            $query->where('u_major_id', $request->major);
        }
        $students = $query->select('u_id');


        if($semester==0){
            $data = StudentCompany::select('sc_student_id')->with('users')->distinct('sc_student_id')
            ->whereIn('sc_registration_id', function ($query) use ($semester) {
                $query->select('r_id')
                ->from('registration')
                ->distinct();
            })
            ->whereIn('sc_student_id',$students)
            ->groupBy('sc_student_id')->get();
        }else{
            $data = StudentCompany::select('sc_student_id')->with('users')->distinct('sc_student_id')
            ->whereIn('sc_registration_id', function ($query) use ($semester) {
                $query->select('r_id')
                ->from('registration')
                ->where('r_semester', $semester)
                ->distinct();
            })
            ->whereIn('sc_student_id',$students)
            ->groupBy('sc_student_id')->get();
        }

        // $data = StudentCompany::select('sc_student_id')->with('users')->distinct('sc_student_id')
        // ->whereIn('sc_registration_id', function ($query) use ($semester) {
        //     $query->select('r_id')
        //     ->from('registration')
        //     ->where('r_year', $year)
        //     ->where('r_semester', $semester)
        //     ->distinct();
        // })
        // ->groupBy('sc_student_id')->get();

        foreach($data as $key){
            $major = $key->users->u_major_id;
            $majorText = Major::where('m_id', $major)->value('m_name');
            $key->major = $majorText;
        }
        // return $data;

        // $companyName = Company::where('c_id', $id)->value('c_name');

        // return view('project.monitor_evaluation.companyStudents',['data'=>$data,'semester'=>$semester,
        // 'majors'=>$majors, 'company_name'=>$companyName]);

        return response()->json([
            'success'=>'true',
            'view'=>view('project.monitor_evaluation.ajax.companyStudentsTable',['data'=>$data])->render()
        ]);
    }

    // Edit By Mohamad Maraqa
    public function attendance_and_departure_report_index(){
        $comapny = Company::get();
        $users = User::where('u_role_id',2)->get();
        $system_settings = SystemSetting::first();
        return view('project.monitor_evaluation.attendance_and_departure_report_index',['comapny'=>$comapny,'users'=>$users,'system_settings'=>$system_settings]);
    }

    public function attendance_and_departure_report_table(Request $request){
        $data = StudentAttendance::whereIn('sa_student_id',function($query) use ($request){
            $query->select('u_id')->from('users')->where('name','like','%'.$request->student_search.'%');
        })
        ->when($request->filled('company_id'),function($query) use ($request){
            $query->whereIn('sa_student_company_id',function($query) use ($request){
                $query->select('sc_id')->from('students_companies')->where('sc_company_id',$request->company_id);
            });
        })
        ->when($request->filled('from') && $request->filled('to'),function($query) use ($request){
            $query->whereBetween('sa_in_time',[$request->from,$request->to]);
        })
        ->whereIn('sa_student_id', function($query) use ($request) {
            $query->select('r_student_id')
                  ->from('registration')
                  ->when($request->filled('semester'),function($query) use ($request){
                    $query->where('r_semester',$request->semester);
                  })
                  ->when($request->filled('year'),function($query) use ($request){
                    $query->where('r_year',$request->year);
                  });

        })->get();
        foreach($data as $key){
            $key->user = User::where('u_id',$key->sa_student_id)->first();
            $key->company = Company::where('c_id',StudentCompany::where('sc_id',$key->sa_student_company_id)->first()->sc_company_id)->first();
            $key->report_attendance = StudentReport::where('sr_student_attendance_id',$key->sa_id)->first();
        }
        return response()->json([
            'success' => 'true',
            'view' => view('project.monitor_evaluation.ajax.attendance_and_departure_report',['data'=>$data])->render(),
        ]);
    }

    public function export_student_attendance(){
        return Excel::download(new StudentAttendanceExport,'student_attendance.xlsx');
    }

    public function files_index(){
        $data = MeAttachmentModel::where('mea_user_id',auth()->user()->u_id)->where('mea_attachment_id',-1)->get();
        foreach ($data as $key){
            $key->versions = MeAttachmentModel::where('mea_attachment_id',$key->mea_id)->get();
        }
        return view('project.monitor_evaluation.files.index',['data'=>$data]);
    }

    public function create_me_attachment(Request $request){
        $data = new MeAttachmentModel();
        $data->mea_user_id = auth()->user()->u_id;
        $data->mea_description = $request->mea_description;
        if ($request->hasFile('file')) {
            $file = $request->file('file');
            $extension = $file->getClientOriginalExtension();
            $filename = time() . '.' . $extension;
            $file->storeAs('files', $filename, 'public');
            $data->mea_file = $filename;
        }
        $data->mea_attachment_id = $request->mea_attachment_id;
        if ($data->save()){
            return redirect()->route('monitor_evaluation.files.files_index')->with(['success' => 'تم اضافة الملف بنجاح']);
        }
        else{
            return redirect()->route('monitor_evaluation.files.files_index')->with(['fail' => 'هناك خلل ما لم يتم اضافة الملف']);
        }
    }

    public function create_me_version_attachment(Request $request){
        $check_if_find = MeAttachmentModel::where('mea_user_id',auth()->user()->u_id)->where('mea_attachment_id',$request->mea_attachment_id)->get();
        if (!$check_if_find->isEmpty()){
//            $data->mea_attachment_id = MeAttachmentModel::where('mea_user_id',auth()->user()->u_id)->latest('mea_id')->first()->mea_id;
            if (count($check_if_find) >= 1){
                $data = new MeAttachmentModel();
                $data->mea_user_id = auth()->user()->u_id;
                $data->mea_description = $request->mea_description;
                if ($request->hasFile('file')) {
                    $file = $request->file('file');
                    $extension = $file->getClientOriginalExtension();
                    $filename = time() . '.' . $extension;
                    $file->storeAs('files', $filename, 'public');
                    $data->mea_file = $filename;
                }
                $data->mea_attachment_id = $request->mea_attachment_id;
                $first_file = MeAttachmentModel::where('mea_user_id',auth()->user()->u_id)->where('mea_id',$request->mea_attachment_id)->first();
                $next_file = MeAttachmentModel::where('mea_user_id', auth()->user()->u_id)
                    ->where('mea_id', '>', $first_file->mea_id)
                    ->orderBy('mea_id')
                    ->first();

                $filePath = 'files/' . $first_file->mea_file;
//                Storage::disk('public')->delete(asset('monitor_trainer/' . $first_file->mea_file));
                Storage::disk('public')->delete($filePath);
                $first_file->delete();


                $next_file->mea_attachment_id = -1;
                $next_file->save();

                $last_row =  MeAttachmentModel::where('mea_user_id',auth()->user()->u_id)->latest('mea_id')->first();
                $data->mea_attachment_id = $last_row->mea_id;
                $last_row->save();
                if ($data->save()){
                    return redirect()->route('monitor_evaluation.files.files_index')->with(['success' => 'تم اضافة الملف بنجاح']);
                }
                else{
                    return redirect()->route('monitor_evaluation.files.files_index')->with(['fail' => 'هناك خلل ما لم يتم اضافة الملف']);
                }
            }
        }
        else{
            $data = new MeAttachmentModel();
            $data->mea_user_id = auth()->user()->u_id;
            $data->mea_description = $request->mea_description;
            if ($request->hasFile('file')) {
                $file = $request->file('file');
                $extension = $file->getClientOriginalExtension();
                $filename = time() . '.' . $extension;
                $file->storeAs('files', $filename, 'public');
                $data->mea_file = $filename;
            }
            $data->mea_attachment_id = $request->mea_attachment_id;
            if ($data->save()){
                return redirect()->route('monitor_evaluation.files.files_index')->with(['success' => 'تم اضافة الملف بنجاح']);
            }
            else{
                return redirect()->route('monitor_evaluation.files.files_index')->with(['fail' => 'هناك خلل ما لم يتم اضافة الملف']);
            }
        }
    }

    public function statistic_attendance_index()
    {
        return view('project.monitor_evaluation.statistic_attendance.index');
    }

    public function list_statistic_attendance_ajax(Request $request)
    {
        $data = StudentAttendance::with('user')->get();
        return response()->json([
            'success' => true,
            'view' => view('project.monitor_evaluation.statistic_attendance.ajax.list_statistic_attendance_ajax',['data'=>$data])->render()
        ]);
    }
}
