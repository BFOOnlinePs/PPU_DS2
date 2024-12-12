
        <!--
        <div class="sidebar-user text-center">
            <img class="img-90 rounded-circle"
                src="https://laravel.pixelstrap.com/viho/assets/images/dashboard/1.png" alt="">
            <div class="badge-bottom"><span class="badge badge-primary">New</span></div>
            <a href="user-profile" data-bs-original-title="" title="">
                <h6 class="mt-3 f-14 f-w-600">{{ auth()->user()->name }}</h6>
            </a>
            <p class="mb-0 font-roboto">{{ auth()->user()->email }}</p>
        </div>
        -->
        <div class="main-navbar">
            <div id="mainnav">
                <ul class="nav-menu custom-scrollbar d-flex">
                    @if(auth()->user()->u_role_id == 2) {{-- Student --}}
                        <li class=""><a class="nav-link" href="{{ route('students.personal_profile.index') }}"><i
                                    data-feather="user"></i><span>{{__('translate.Profile')}}</span></a></li>
                        <li class=""><a class="nav-link" href="{{ route('students.company.index') }}"><i
                                    data-feather="briefcase"></i><span>{{__('translate.Companies')}}</span></a></li>
                        <li class=""><a class="nav-link" href="{{ route('students.attendance.index') }}"><i
                                    data-feather="check"></i><span>{{__('translate.Attendance Logs')}}</span></a></li>
                        <li class=""><a class="nav-link" href="{{ route('students.payments.index') }}"><i
                                    data-feather="dollar-sign"></i><span>{{__('translate.Payments')}}</span></a></li>
                    @elseif (auth()->user()->u_role_id == 3) {{-- Supervisor --}}
                        <li class=""><a class="nav-link" href="{{ route('supervisors.majors.index' , ['id' => auth()->user()->u_id]) }}"><i
                                    data-feather="book-open"></i><span>{{__('translate.Majors')}}</span></a></li>
                        <li class=""><a class="nav-link" href="{{ route('supervisors.students.index' , ['id' => auth()->user()->u_id]) }}"><i
                                    data-feather="users"></i><span>{{__('translate.Students')}}</span></a></li>
                        <li class=""><a class="nav-link" href="{{ route('supervisors.companies.index') }}"><i
                                    data-feather="briefcase"></i><span>{{__('translate.Training Places')}}</span></a></li>
                        <li class="dropdown-basic">
                            <div class="dropdown">
                                <div class="dropbtn">
                                    <a class="nav-link" href="{{ route('admin.companies.index') }}">
                                        <i data-feather="briefcase"></i>
                                        <span>{{__('translate.Companies')}}</span>
                                    </a>
                                    <div class="dropdown-content">
                                        <a href="{{ route('admin.companies.index') }}">{{__('translate.Display Companies')}}</a>
                                        <a href="{{ route('admin.companies.company') }}">{{__('translate.Add Company')}}</a>
                                        <a href="{{ route('admin.companies_categories.index') }}">{{__('translate.Companies Categories')}}</a>
                                    </div>
                                </div>
                            </div>
                        </li>
                    @elseif (auth()->user()->u_role_id == 4) {{-- Assistant --}}
                        <li class=""><a class="nav-link" href="{{ route('supervisor_assistants.majors.index' , ['id' => auth()->user()->u_id]) }}"><i
                                    data-feather="book-open"></i><span>{{__('translate.Majors')}}</span></a></li>
                        <li class=""><a class="nav-link" href="{{ route('supervisor_assistants.students.index' , ['ms_major_id' => null]) }}"><i
                                    data-feather="users"></i><span>{{__('translate.Students')}}</span></a></li>
                        <li class=""><a class="nav-link" href="{{ route('supervisor_assistants.companies.index') }}"><i
                                    data-feather="briefcase"></i><span>{{__('translate.Training Places')}}</span></a></li>
                        <li class="dropdown-basic">
                            <div class="dropdown">
                                <div class="dropbtn">
                                    <a class="nav-link" href="{{ route('admin.companies.index') }}">
                                        <i data-feather="briefcase"></i>
                                        <span>{{__('translate.Companies')}}</span>
                                    </a>
                                    <div class="dropdown-content">
                                        <a href="{{ route('admin.companies.index') }}">{{__('translate.Display Companies')}}</a>
                                        <a href="{{ route('admin.companies.company') }}">{{__('translate.Add Company')}}</a>
                                        <a href="{{ route('admin.companies_categories.index') }}">{{__('translate.Companies Categories')}}</a>
                                    </div>
                                </div>
                            </div>
                        </li>
                    @elseif (auth()->user()->u_role_id == 6) {{-- Company Manager --}}
                        <li class=""><a class="nav-link" href="{{ route('company_manager.students.index') }}"><i
                                    data-feather="users"></i><span>{{__('translate.Students')}}</span></a></li>
                        <li class=""><a class="nav-link" href="{{ route('company_manager.records.index') }}"><i
                                    data-feather="list"></i><span>{{__('translate.Attendance Logs')}}</span></a></li>
                        <li class=""><a class="nav-link" href="{{ route('company_manager.payments.index') }}"><i
                                    data-feather="dollar-sign"></i><span>{{__('translate.Payments')}}</span></a></li>
                    @elseif (auth()->user()->u_role_id == 5) {{-- M&E --}}
                        <li class=""><a class="nav-link" href="{{ route('home') }}"><i
                                    data-feather="home"></i><span>{{__('translate.Main')}}</span></a></li>
                        {{-- <li class=""><a class="nav-link" href="{{ route('monitor_evaluation.semesterReport') }}"><i
                                    data-feather="calendar"></i><span>{{__("translate.Semester's Report")}}</span></a></li> --}}
                        <li class="dropdown-basic">
                            <div class="dropdown">
                                <div class="dropbtn">
                                    <a class="nav-link" href="{{ route('monitor_evaluation.semesterReport') }}">
                                        <i data-feather="calendar"></i>
                                        <span>تقارير</span>
                                    </a>
                                    <div class="dropdown-content">
                                        <a href="{{route("monitor_evaluation.semesterReport")}}">{{__("translate.Semester's Report")}}</a>
                                        <a href="{{route("monitor_evaluation.students_courses_report")}}">تقرير الطلاب المسجلين في التدريبات العملية</a>
                                        <a href="{{ route('monitor_evaluation.courses_registered_report') }}">تقرير التدريبات العملية المسجلة</a>
                                        <a href="{{ route('monitor_evaluation.training_hours_report') }}">تقرير ساعات التدريب للطلاب</a>
                                        <a href="{{ route('monitor_evaluation.students_companies_report') }}">تقرير المتدربين</a>
                                    </div>
                                </div>
                            </div>
                        </li>
                        <li class=""><a class="nav-link" href="{{ route('monitor_evaluation.companiesReport') }}"><i
                                    data-feather="briefcase"></i><span>{{__("translate.Companies' Report")}}</span></a></li>
                        <li class=""><a class="nav-link" href="{{ route('monitor_evaluation.companiesPaymentsReport') }}"><i
                                    data-feather="users"></i><span>{{__("translate.Companies Payments' Report")}}</span></a></li>
                        <li class=""><a class="nav-link" href="{{ route('monitor_evaluation.paymentsReport') }}"><i
                                    data-feather="dollar-sign"></i><span>{{__("translate.Payments' Report")}}</span></a></li>
                    @elseif (auth()->user()->u_role_id == 8) {{-- Communications Manager with Companies --}}
                        <li class="dropdown-basic">
                            <div class="dropdown">
                                <div class="dropbtn">
                                    <a class="nav-link" href="{{ route('admin.companies.index') }}">
                                        <i data-feather="briefcase"></i>
                                        <span>{{__('translate.Companies')}}</span>
                                    </a>
                                    <div class="dropdown-content">
                                        <a href="{{ route('admin.companies.index') }}">{{__('translate.Display Companies')}}</a>
                                        <a href="{{ route('admin.companies.company') }}">{{__('translate.Add Company')}}</a>
                                        <a href="{{ route('admin.companies_categories.index') }}">{{__('translate.Companies Categories')}}</a>
                                    </div>
                                </div>
                            </div>
                        </li>
                        <li class=""><a class="nav-link" href="{{ route('communications_manager_with_companies.students.index') }}"><i
                                    data-feather="users"></i><span>{{__('translate.Students')}}</span></a></li>
                        <li class=""><a class="nav-link" href="{{ route('communications_manager_with_companies.companies.index') }}"><i
                                    data-feather="briefcase"></i><span>{{__('translate.Training Places')}}</span></a></li>
                    @else
                        <li>
                            <a class="nav-link" href="{{ route('home') }}">
                                <i data-feather="home"></i>
                                <span>{{__('translate.Main')}}</span>
                            </a>
                        </li>
                        <li class="dropdown-basic">
                            <div class="dropdown">
                                <div class="dropbtn">
                                    <a class="nav-link" href="{{ route('admin.users.index') }}" >
                                        <i data-feather="users"></i>
                                        <span>{{__('translate.Users Management')}}</span>
                                    </a>
                                    <div class="dropdown-content">
                                        <a href="{{route('admin.users.index_id' , ['id'=>1])}}" >
                                             {{__('translate.Admin')}} {{-- أدمن --}} </a>
                                        <a href="{{route('admin.users.index_id' , ['id'=>2])}}">
                                             {{__('translate.Student')}} {{-- طالب --}}</a>
                                        <a href="{{route('admin.users.index_id' , ['id'=>3])}}">
                                             {{__('translate.Academic Supervisor')}} {{-- مشرف أكاديمي --}}</a>
                                        <a href="{{route('admin.users.index_id' , ['id'=>4])}}" >
                                             {{__('translate.Academic Supervisor Assistant')}} {{-- مساعد إداري --}}</a>
                                        <a href="{{route('admin.users.index_id' , ['id'=>5])}}" >
                                             {{__('translate.M&E')}} {{-- مسؤول متابعة وتقييم --}}</a>
                                        <a href="{{route('admin.users.index_id' , ['id'=>6])}}" >
                                             {{__('translate.Company Manager')}} {{-- مدير شركة --}}</a>
                                        <a href="{{route('admin.users.index_id' , ['id'=>7])}}" >
                                             {{__('translate.Training Supervisor')}} {{-- مسؤول تدريب --}}</a>
                                        <a href="{{route('admin.users.index_id' , ['id'=>8])}}" >
                                             {{__('translate.Program Coordinator')}} {{-- مسسؤول التواصل مع الشركات --}}</a>
                                    </div>
                                </div>
                            </div>
                        </li>
                        <li class="dropdown">
                            <a class="nav-link" href="{{route('admin.majors.index')}}">
                                <i data-feather="book-open"></i>
                                <span>{{__('translate.Majors Management')}}</span>
                            </a>
                        </li>
                        <li class="dropdown-basic">
                            <div class="dropdown">
                                <div class="dropbtn">
                                    <a class="nav-link" href="{{ route('admin.courses.index') }}">
                                        <i data-feather="book"></i>
                                        <span>{{__('translate.Courses')}}</span>
                                    </a>
                                    <div class="dropdown-content">
                                        <a href="{{ route('admin.courses.index') }}">{{__('translate.Courses Management')}}</a>
                                        <a href="{{ route('admin.semesterCourses.index') }}">{{__('translate.Current Semester Courses')}}</a>
                                    </div>
                                </div>
                            </div>
                        </li>
                        <li class="dropdown-basic">
                            <div class="dropdown">
                                <div class="dropbtn">
                                    <a class="nav-link" href="{{ route('admin.companies.index') }}">
                                        <i data-feather="briefcase"></i>
                                        <span>{{__('translate.Companies')}}</span>
                                    </a>
                                    <div class="dropdown-content">
                                        <a href="{{ route('admin.companies.index') }}">{{__('translate.Display Companies')}}</a>
                                        <a href="{{ route('admin.companies.company') }}">{{__('translate.Add Company')}}</a>
                                        <a href="{{ route('admin.companies_categories.index') }}">{{__('translate.Companies Categories')}}</a>
                                    </div>
                                </div>
                            </div>
                        </li>
                        <li class="dropdown-basic">
                            <div class="dropdown">
                                <div class="dropbtn">
                                    <a class="nav-link" href="{{ route('admin.registration.index') }}">
                                        <i data-feather="user-check"></i>
                                        <span>{{__('translate.Registration')}}</span>
                                    </a>
                                    <div class="dropdown-content">
                                        <a href="{{ route('admin.registration.index') }}">{{__('translate.Registration')}}</a>
                                        <a href="{{ route('admin.registration.semesterStudents') }}">{{__("translate.Current Semester's Students")}}</a>
                                    </div>
                                </div>
                            </div>
                        </li>
                        <li class="dropdown">
                            <a class="nav-link" href="{{ route('admin.settings') }}">
                                <i data-feather="settings"></i>
                                <span>{{__('translate.Settings')}}</span>
                            </a>
                        </li>
                    @endif
                </ul>
            </div>
        </div>
