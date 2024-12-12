<header class="main-nav">
    {{-- <div class="sidebar-user text-center"><a class="setting-primary" href="javascript:void(0)"><i data-feather="settings"></i></a><img class="img-90 rounded-circle" src="{{asset('assets/images/dashboard/1.png')}}" alt="">
         <div class="badge-bottom"><span class="badge badge-primary">New</span></div><a href="user-profile.html">
           <h6 class="mt-3 f-14 f-w-600">{{auth::user()->name}}</h6></a>
       </div> --}}
    <div class="sidebar-user text-center pb-0" style="border-bottom:0px">
        <a href="#">
            <img
            src="{{ asset('public/assets/images/avtar/profile.png') }}"
                alt="" class="img-90 mt-2 p-2 rounded-circle flex-center">
            <div class="badge-bottom mt-3">
                <div class="badge badge-primary" dir="ltr">{{ auth()->user()->email}}</div>
            </div>
            <h6 class="mt-3 f-14 f-w-600">{{ auth()->user()->name }}</h6>
        </a>
    </div>
    <nav>
        <div class="main-navbar mt-4 bg-dark">

            <div class="left-arrow" id="left-arrow"><i data-feather="arrow-left"></i></div>
            <div id="mainnav" class="pb-5" style="overflow: scroll;max-height: 90vh">
                <ul class="nav-menu custom-scrollbar">
                    <li><a class="nav-link bg-dark text-white mt-1" href="{{ route('home') }}"><i
                                data-feather="home"></i><span>الرئيسية</span></a></li>
                    @if (auth()->user()->u_role_id == 1)
                        {{-- admin --}}
                        <li><a class="nav-link bg-dark text-white mt-1" href="{{ route('admin.users.index') }}"><i
                                    data-feather="users"></i><span>{{ __('translate.Users Management') }}{{-- المستخدمين --}}</span></a>
                        </li>
                        <li><a class="nav-link bg-dark text-white mt-1" href="{{ route('admin.majors.index') }}"><i
                                    data-feather="book-open"></i><span>{{ __('translate.Majors Management') }}{{-- التخصصات --}}</span></a>
                        </li>
                        <li><a class="nav-link bg-dark text-white mt-1" href="{{ route('admin.courses.index') }}"><i
                                    data-feather="book"></i><span>{{ __('translate.Courses') }}{{-- التدريبات العملية --}}</span></a>
                        </li>
                        <li><a class="nav-link bg-dark text-white mt-1"
                                href="{{ route('monitor_evaluation.attendance_and_departure_report_index') }}"><i
                                    data-feather="clock"></i><span>{{ __('translate.Student Attendance') }}{{-- سجل الحضور والمغادرة --}}</span></a>
                        </li>
                        <li><a class="nav-link bg-dark text-white mt-1" href="{{ route('admin.companies.index') }}"><i
                                    data-feather="briefcase"></i><span>{{ __('translate.Companies') }}{{-- الشركات --}}</span></a>
                        </li>
                        <li><a class="nav-link bg-dark text-white mt-1"
                                href="{{ route('admin.registration.index') }}"><i
                                    data-feather="user-check"></i><span>{{ __('translate.Registration') }}{{-- التسجيل --}}</span></a>
                        </li>
                        <li><a class="nav-link bg-dark text-white mt-1"
                                href="{{ route('admin.field_visits.index') }}"><i
                                    data-feather="briefcase"></i><span>الزيارات الميدانية</span></a></li>
                        <li><a class="nav-link bg-dark text-white mt-1"
                                href="{{ route('admin.evaluations.index') }}"><i
                                    data-feather="briefcase"></i><span>التقييمات</span></a></li>
                        <li><a class="nav-link bg-dark text-white mt-1"
                                href="{{ route('monitor_evaluation.semesterReport') }}"><i
                                    data-feather="calendar"></i><span>{{ __('translate.Reports') }}{{-- تقارير --}}</span></a>
                        </li>
                        <li><a class="nav-link bg-dark text-white mt-1" href="{{ route('admin.reports.index') }}"><i
                                    data-feather="calendar"></i><span>سجل التقارير</span></a></li>
                        <li><a class="nav-link bg-dark text-white mt-1"
                                href="{{ route('communications_manager_with_companies.follow_up_record.index') }}"><i
                                    data-feather="briefcase"></i><span>{{ __('translate.follow_up_record') }}</span></a>
                        </li>
                        {{--                            <li><a class="nav-link bg-dark text-white mt-1" href="{{route('admin.attendance.index')}}"><i data-feather="user-check"></i><span>{{__('translate.Student Attendance')}} سجل الحضور والمغادرة </span></a></li> --}}
                        <li><a class="nav-link bg-dark text-white mt-1" href="{{ route('admin.settings') }}"><i
                                    data-feather="settings"></i><span>{{ __('translate.Settings') }}{{-- الإعدادات --}}</span></a>
                        </li>
                    @elseif(auth()->user()->u_role_id == 2)
                        {{-- Student --}}
                        <li><a class="nav-link bg-dark text-white mt-1"
                                href="{{ route('students.personal_profile.index') }}"><i
                                    data-feather="user"></i><span>{{ __('translate.Profile') }}{{-- الملف الشخصي --}}</span></a>
                        </li>
                        <li><a class="nav-link bg-dark text-white mt-1" href="{{ route('students.company.index') }}"><i
                                    data-feather="briefcase"></i><span>{{ __('translate.Companies') }}{{-- الشركات --}}</span></a>
                        </li>
                        <li><a class="nav-link bg-dark text-white mt-1"
                                href="{{ route('students.attendance.index') }}"><i
                                    data-feather="check"></i><span>{{ __('translate.Attendance Logs') }}{{-- سجلات المتابعة --}}</span></a>
                        </li>
                        <li><a class="nav-link bg-dark text-white mt-1"
                                href="{{ route('students.payments.index') }}"><i
                                    data-feather="dollar-sign"></i><span>{{ __('translate.Payments') }}{{-- الدفعات --}}</span></a>
                        </li>
                        {{-- <li><a class="nav-link bg-dark text-white mt-1"
                                href="{{ route('students.evaluation.index') }}"><i
                                    data-feather="dollar-sign"></i><span>التقييمات</span></a></li> --}}
                        <li><a class="nav-link bg-dark text-white mt-1"
                                href="{{ route('training_supervisor.conversation.index') }}"><i
                                    data-feather="dollar-sign"></i><span>المراسلات</span></a></li>
                        <li><a class="nav-link bg-dark text-white mt-1"
                                href="{{ route('students.final_reports.index') }}"><i
                                    data-feather="dollar-sign"></i><span>تسليم التقرير النهائي</span></a></li>
                    @elseif (auth()->user()->u_role_id == 3)
                        {{-- Supervisor --}}
                        <li><a class="nav-link bg-dark text-white mt-1"
                                href="{{ route('supervisors.majors.index', ['id' => auth()->user()->u_id]) }}"><i
                                    data-feather="book-open"></i><span>{{ __('translate.Majors') }}{{-- التخصصات --}}</span></a>
                        </li>
                        <li><a class="nav-link bg-dark text-white mt-1"
                                href="{{ route('supervisors.students.index', ['id' => auth()->user()->u_id]) }}"><i
                                    data-feather="users"></i><span>{{ __('translate.Students') }}{{-- الطلاب --}}</span></a>
                        </li>
                        <li><a class="nav-link bg-dark text-white mt-1"
                                href="{{ route('supervisors.companies.index') }}"><i
                                    data-feather="briefcase"></i><span>{{ __('translate.Training Places') }}{{-- أماكن التدريب --}}</span></a>
                        </li>
                        <li><a class="nav-link bg-dark text-white mt-1"
                                href="{{ route('monitor_evaluation.semesterReport') }}"><i
                                    data-feather="calendar"></i><span>{{ __('translate.Reports') }}{{-- تقارير --}}</span></a>
                        </li>
                        <li><a class="nav-link bg-dark text-white mt-1"
                                href="{{ route('admin.companies.index') }}"><i
                                    data-feather="briefcase"></i><span>{{ __('translate.Companies') }}{{-- الشركات --}}</span></a>
                        </li>
                        <li><a class="nav-link bg-dark text-white mt-1"
                                href="{{ route('supervisors.training_nominations.index') }}"><i
                                    data-feather="briefcase"></i><span>{{ __('translate.training_nominations') }}{{-- ترشيحات التدريب --}}</span></a>
                        </li>
                        <li><a class="nav-link bg-dark text-white mt-1"
                                href="{{ route('supervisors.supervisors.student_marks') }}"><i
                                    data-feather="user"></i><span>علامات الطلاب</span></a>
                        </li>
                    @elseif (auth()->user()->u_role_id == 4)
                        {{-- Assistant --}}
                        <li><a class="nav-link bg-dark text-white mt-1"
                                href="{{ route('supervisor_assistants.majors.index', ['id' => auth()->user()->u_id]) }}"><i
                                    data-feather="book-open"></i><span>{{ __('translate.Majors') }}{{-- التخصصات --}}</span></a>
                        </li>
                        <li><a class="nav-link bg-dark text-white mt-1"
                                href="{{ route('supervisor_assistants.students.index', ['ms_major_id' => null]) }}"><i
                                    data-feather="users"></i><span>{{ __('translate.Students') }}{{-- الطلاب --}}</span></a>
                        </li>
                        <li><a class="nav-link bg-dark text-white mt-1"
                                href="{{ route('supervisor_assistants.companies.index') }}"><i
                                    data-feather="briefcase"></i><span>{{ __('translate.Training Places') }}{{-- أماكن التدريب --}}</span></a>
                        </li>
                        <li><a class="nav-link bg-dark text-white mt-1"
                                href="{{ route('admin.companies.index') }}"><i
                                    data-feather="briefcase"></i><span>{{ __('translate.Companies') }}{{-- الشركات --}}</span></a>
                        </li>
                        <li><a class="nav-link bg-dark text-white mt-1"
                                href="{{ route('supervisors.training_nominations.index') }}"><i
                                    data-feather="briefcase"></i><span>{{ __('translate.training_nominations') }}{{-- ترشيحات التدريب --}}</span></a>
                        </li>
                    @elseif (auth()->user()->u_role_id == 5)
                        {{-- M&E --}}
                        <li><a class="nav-link bg-dark text-white mt-1" href="{{ route('home') }}"><i
                                    data-feather="home"></i><span>{{ __('translate.Main') }}{{-- الرئيسية --}}</span></a>
                        </li>
                        <li><a class="nav-link bg-dark text-white mt-1"
                                href="{{ route('monitor_evaluation.user_details') }}"><i
                                    data-feather="user"></i><span>الملف الشخصي</span></a>
                        </li>
                        <li><a class="nav-link bg-dark text-white mt-1"
                                href="{{ route('monitor_evaluation.semesterReport') }}"><i
                                    data-feather="calendar"></i><span>{{ __('translate.Reports') }}{{-- تقارير --}}</span></a>
                        </li>
                        <li><a class="nav-link bg-dark text-white mt-1"
                                href="{{ route('monitor_evaluation.companiesReport') }}"><i
                                    data-feather="briefcase"></i><span>{{ __("translate.Companies' Report") }}{{-- تقرير الشركات --}}</span></a>
                        </li>
                        <li><a class="nav-link bg-dark text-white mt-1"
                                href="{{ route('monitor_evaluation.attendance_and_departure_report_index') }}"><i
                                    data-feather="clock"></i><span>{{ __('translate.Student Attendance') }}{{-- تقرير الحضور والمغادرة --}}</span></a>
                        </li>
                        <li><a class="nav-link bg-dark text-white mt-1"
                                href="{{ route('monitor_evaluation.companiesPaymentsReport') }}"><i
                                    data-feather="users"></i><span>{{ __("translate.Companies Payments' Report") }}{{-- تقرير دفعات الشركات --}}</span></a>
                        </li>
                        <li><a class="nav-link bg-dark text-white mt-1"
                                href="{{ route('monitor_evaluation.paymentsReport') }}"><i
                                    data-feather="dollar-sign"></i><span>{{ __("translate.Payments' Report") }}{{-- تقرير الدفعات المالية --}}</span></a>
                        </li>
                        <li><a class="nav-link bg-dark text-white mt-1"
                                href="{{ route('monitor_evaluation.files.files_index') }}"><i
                                    data-feather="file"></i><span>{{ __('translate.files') }}{{-- الملفات --}}</span></a>
                        </li>
                    @elseif (auth()->user()->u_role_id == 6)
                        {{-- Company Manager --}}
                        <li><a class="nav-link bg-dark text-white mt-1"
                                href="{{ route('company_manager.students.index') }}"><i
                                    data-feather="users"></i><span>{{ __('translate.Students') }}{{-- الطلاب --}}</span></a>
                        </li>
                        <li><a class="nav-link bg-dark text-white mt-1"
                                href="{{ route('company_manager.records.index') }}"><i
                                    data-feather="list"></i><span>{{ __('translate.Attendance Logs') }}{{-- سجلات المتابعة --}}</span></a>
                        </li>
                        <li><a class="nav-link bg-dark text-white mt-1"
                                href="{{ route('company_manager.attendance.index') }}"><i
                                    data-feather="user-check"></i><span>{{ __('translate.Student Attendance') }}{{-- سجل الحضور والمغادرة --}}</span></a>
                        </li>
                        <li><a class="nav-link bg-dark text-white mt-1"
                                href="{{ route('company_manager.student_nominations.index') }}"><i
                                    data-feather="user-check"></i><span>{{ __('translate.student_nominations') }}{{-- ترشيحات التدريب --}}</span></a>
                        </li>
                        <li><a class="nav-link bg-dark text-white mt-1"
                                href="{{ route('company_manager.payments.index') }}"><i
                                    data-feather="dollar-sign"></i><span>{{ __('translate.Payments') }}{{-- الدفعات --}}</span></a>
                        </li>
                        @if (App\Models\EvaluationsModel::where('e_evaluator_role_id', 6)->first()->e_status == 1)
                            <li><a class="nav-link bg-dark text-white mt-1"
                                    href="{{ route('students.evaluation.index') }}"><i
                                        data-feather="dollar-sign"></i><span>التقييمات</span></a></li>
                        @endif
                        <li><a class="nav-link bg-dark text-white mt-1"
                                href="{{ route('training_supervisor.conversation.index') }}"><i
                                    data-feather="dollar-sign"></i><span>المراسلات</span></a></li>
                    @elseif (auth()->user()->u_role_id == 8)
                        {{-- Communications Manager with Companies --}}
                        <li><a class="nav-link bg-dark text-white mt-1"
                                href="{{ route('admin.companies.index') }}"><i
                                    data-feather="briefcase"></i><span>{{ __('translate.Companies') }}{{-- الشركات --}}</span></a>
                        </li>
                        <li><a class="nav-link bg-dark text-white mt-1"
                                href="{{ route('communications_manager_with_companies.students.index') }}"><i
                                    data-feather="users"></i><span>{{ __('translate.Students') }}{{-- الطلاب --}}</span></a>
                        </li>
                        <li><a class="nav-link bg-dark text-white mt-1"
                                href="{{ route('communications_manager_with_companies.companies.index') }}"><i
                                    data-feather="briefcase"></i><span>{{ __('translate.Training Places') }}{{-- أماكن التدريب --}}</span></a>
                        </li>
                        <li><a class="nav-link bg-dark text-white mt-1"
                                href="{{ route('monitor_evaluation.attendance_and_departure_report_index') }}"><i
                                    data-feather="clock"></i><span>{{ __('translate.Student Attendance') }}{{-- سجل الحضور والمغادرة --}}</span></a>
                        </li>
                        <li><a class="nav-link bg-dark text-white mt-1"
                                href="{{ route('monitor_evaluation.semesterReport') }}"><i
                                    data-feather="calendar"></i><span>{{ __('translate.Reports') }}{{-- تقارير --}}</span></a>
                        </li>
                        <li><a class="nav-link bg-dark text-white mt-1"
                                href="{{ route('supervisors.training_nominations.index') }}"><i
                                    data-feather="briefcase"></i><span>{{ __('translate.training_nominations') }}{{-- ترشيحات التدريب --}}</span></a>
                        </li>
                        <li><a class="nav-link bg-dark text-white mt-1"
                                href="{{ route('communications_manager_with_companies.follow_up_record.index') }}"><i
                                    data-feather="briefcase"></i><span>{{ __('translate.follow_up_record') }}</span></a>
                        </li>
                        <li><a class="nav-link bg-dark text-white mt-1"
                                href="{{ route('admin.field_visits.index') }}"><i
                                    data-feather="briefcase"></i><span>زيارات المشرفين</span></a>
                        </li>
                    @elseif (auth()->user()->u_role_id == 10)
                        {{-- Communications Manager with Companies --}}
                        <li><a class="nav-link bg-dark text-white mt-1"
                                href="{{ route('training_supervisor.my_students.index') }}"><i
                                    data-feather="briefcase"></i><span>طلابي</span></a></li>
                        <li><a class="nav-link bg-dark text-white mt-1"
                                href="{{ route('training_supervisor.field_visits.index') }}"><i
                                    data-feather="briefcase"></i><span>تسجيل الزيارات</span></a></li>
                        <li><a class="nav-link bg-dark text-white mt-1"
                                href="{{ route('training_supervisor.conversation.index') }}"><i
                                    data-feather="briefcase"></i><span>المراسلات</span></a></li>
                        <li><a class="nav-link bg-dark text-white mt-1"
                                href="{{ route('training_supervisor.final_reports.index') }}"><i
                                    data-feather="briefcase"></i><span>التقارير النهائية</span></a></li>
                        @if (App\Models\EvaluationsModel::where('e_evaluator_role_id', 10)->first()->e_status == 1)
                            <li><a class="nav-link bg-dark text-white mt-1"
                                    href="{{ route('students.evaluation.index') }}"><i
                                        data-feather="briefcase"></i><span>التقييمات</span></a></li>
                        @endif
                    @endif

                    @if (auth()->user()->u_role_id != 2 && auth()->user()->u_role_id != 6)
                        {{--                        <li><a class="nav-link bg-dark text-white mt-1" href="{{route('admin.announcements.index')}}"><i data-feather="inbox"></i><span>{{__('translate.announcements')}} --}}{{--  الاعلانات --}}{{-- </span></a></li> --}}
                    @endif
                    {{--                        <li><a class="nav-link bg-dark text-white mt-1" href="{{route('admin.survey.index')}}"><i data-feather="clipboard"></i><span>{{__('translate.surveys')}} --}}{{--  الاستبيانات </span></a></li> --}}
                    {{--                         <li class="dropdown-basic"> --}}
                    {{--                     <div class="dropdown"> --}}
                    {{--                         <div class="dropbtn"> --}}
                    {{--                            <a class="nav-link bg-dark text-white mt-1" href="{{ route('admin.survey.index') }}"> --}}
                    {{--                                <i data-feather="clipboard"></i> --}}
                    {{--                                <span>{{__('translate.Survey')}}</span> --}}
                    {{--                            </a> --}}
                    {{--                            <div class="dropdown-content"> --}}
                    {{--                                <a href="{{ route('admin.survey.index') }}">{{__('translate.surveys')}}</a> --}}
                    {{--                                @if (auth()->user()->u_role_id != 2)    <a href="{{ route('admin.survey.addSurvey') }}">{{__('translate.add_survey')}}</a> @endif --}}
                    {{--                                --}}{{-- <a href="{{ route('admin.registration.semesterStudents') }}">{{__("translate.Current Semester's Students")}}</a> --}}
                    {{--                            </div> --}}
                    {{--                        </div> --}}
                    {{--                     </div> --}}
                    {{--                 </li> --}}
                </ul>
            </div>
            <div class="right-arrow" id="right-arrow"><i data-feather="arrow-right"></i></div>
        </div>
    </nav>
</header>
