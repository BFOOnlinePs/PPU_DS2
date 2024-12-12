@extends('layouts.app')
@section('title')
    {{ __('translate.Main') }}{{-- الرئيسية --}}
@endsection
@section('header_title')
    {{ __('translate.Main') }}
@endsection
@section('header_title_link')
    {{ __('translate.Main') }}
@endsection
@section('header_link')
    {{ __('translate.Main') }}
@endsection
@section('style')
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/calendar.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/bootstrap.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/style.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css"
        integrity="sha512-QZFGL0W6qPrIFZdtYjZ3j+Y6OtHcCqHF5+yXvr3A3qoZtefksLyC5/CSlC5J8+h6FHUw0xRSoAFK43Z7Xlp3Hg=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
    {{-- <link rel="stylesheet" type="text/css" href="{{asset('assets/css/responsive.css')}}"> --}}
    <style>
        .announcemetsBody {
            padding-top: 8%
        }

        .announcement-header {
            background-color: #ef681a;
            color: white;
            width: 100%;
            border-radius: 5px;
            align-items: center;
            justify-content: space-around;
            display: flex;
        }
    </style>
@endsection
@if (auth()->user()->u_role_id == 1)
    @section('content')
        <div class="col-sm-6 col-xl-4 col-md-4 col-lg-6">
            <div class="card o-hidden border-0">
                <div class="bg-primary b-r-4 card-body">
                    <div class="media static-top-widget">
                        <div class="align-self-center text-center"><svg xmlns="http://www.w3.org/2000/svg" width="24"
                                height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                stroke-linecap="round" stroke-linejoin="round" class="feather feather-users">
                                <ellipse cx="12" cy="5" rx="9" ry="3"></ellipse>
                                <path d="M21 12c0 1.66-4 3-9 3s-9-1.34-9-3"></path>
                                <path d="M3 5v14c0 1.66 4 3 9 3s9-1.34 9-3V5"></path>
                            </svg></div>
                        <div class="media-body">
                            <span class="m-0">عدد الطلاب</span>
                            <h4 class="mb-0 counter">{{ $student_count }}</h4>
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                stroke-linejoin="round" class="feather feather-users icon-bg">
                                <ellipse cx="12" cy="5" rx="9" ry="3"></ellipse>
                                <path d="M21 12c0 1.66-4 3-9 3s-9-1.34-9-3"></path>
                                <path d="M3 5v14c0 1.66 4 3 9 3s9-1.34 9-3V5"></path>
                            </svg>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-sm-6 col-xl-4 col-md-4 col-lg-6">
            <div class="card o-hidden border-0">
                <div class="bg-danger b-r-4 card-body">
                    <div class="media static-top-widget">
                        <div class="align-self-center text-center"><svg xmlns="http://www.w3.org/2000/svg" width="24"
                                height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                stroke-linecap="round" stroke-linejoin="round" class="feather feather-shopping-bag">
                                <path d="M6 2L3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4z"></path>
                                <line x1="3" y1="6" x2="21" y2="6"></line>
                                <path d="M16 10a4 4 0 0 1-8 0"></path>
                            </svg></div>
                        <div class="media-body">
                            <span class="m-0">عدد الشركات</span>
                            <h4 class="mb-0 counter">{{ $company_count }}</h4>
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                stroke-linejoin="round" class="feather feather-shopping-bag icon-bg">
                                <path d="M6 2L3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4z"></path>
                                <line x1="3" y1="6" x2="21" y2="6"></line>
                                <path d="M16 10a4 4 0 0 1-8 0"></path>
                            </svg>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-sm-6 col-xl-4 col-md-4 col-lg-6">
            <div class="card o-hidden border-0">
                <div class="bg-primary b-r-4 card-body">
                    <div class="media static-top-widget">
                        <div class="align-self-center text-center"><svg xmlns="http://www.w3.org/2000/svg" width="24"
                                height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                stroke-linecap="round" stroke-linejoin="round" class="feather feather-message-circle">
                                <path
                                    d="M21 11.5a8.38 8.38 0 0 1-.9 3.8 8.5 8.5 0 0 1-7.6 4.7 8.38 8.38 0 0 1-3.8-.9L3 21l1.9-5.7a8.38 8.38 0 0 1-.9-3.8 8.5 8.5 0 0 1 4.7-7.6 8.38 8.38 0 0 1 3.8-.9h.5a8.48 8.48 0 0 1 8 8v.5z">
                                </path>
                            </svg></div>
                        <div class="media-body">
                            <span class="m-0">عدد المشرفين</span>
                            <h4 class="mb-0 counter">{{ $supervisor_count }}</h4>
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                stroke-linejoin="round" class="feather feather-message-circle icon-bg">
                                <path
                                    d="M21 11.5a8.38 8.38 0 0 1-.9 3.8 8.5 8.5 0 0 1-7.6 4.7 8.38 8.38 0 0 1-3.8-.9L3 21l1.9-5.7a8.38 8.38 0 0 1-.9-3.8 8.5 8.5 0 0 1 4.7-7.6 8.38 8.38 0 0 1 3.8-.9h.5a8.48 8.48 0 0 1 8 8v.5z">
                                </path>
                            </svg>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-12 mb-2">
            <h5>احصائيات الفصل الحالي : <span class="">{{ $system_settings->ss_year }}</span> الفصل -
                <span>{{ $system_settings->ss_semester_type }}</span>
            </h5>
        </div>
        <div class="col-sm-6 col-xl-3 col-lg-6">
            <div class="card o-hidden border-0">
                <div class="bg-dark b-r-4 card-body">
                    <div class="media static-top-widget">
                        <div class="align-self-center text-center"><svg xmlns="http://www.w3.org/2000/svg" width="24"
                                height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                class="feather feather-user-plus">
                                <path d="M16 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path>
                                <circle cx="8.5" cy="7" r="4"></circle>
                                <line x1="20" y1="8" x2="20" y2="14"></line>
                                <line x1="23" y1="11" x2="17" y2="11"></line>
                            </svg></div>
                        <div class="media-body">
                            <span class="m-0"><b>طلاب</b> <br>مسجلين ذكور</span>
                            <h4 class="mb-0 counter">{{ $student_male_count }}</h4>
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                stroke-linejoin="round" class="feather feather-user-plus icon-bg">
                                <path d="M16 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path>
                                <circle cx="8.5" cy="7" r="4"></circle>
                                <line x1="20" y1="8" x2="20" y2="14"></line>
                                <line x1="23" y1="11" x2="17" y2="11"></line>
                            </svg>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-sm-6 col-xl-3 col-lg-6">
            <div class="card o-hidden border-0">
                <div class="bg-dark b-r-4 card-body">
                    <div class="media static-top-widget">
                        <div class="align-self-center text-center"><svg xmlns="http://www.w3.org/2000/svg" width="24"
                                height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                class="feather feather-user-plus">
                                <path d="M16 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path>
                                <circle cx="8.5" cy="7" r="4"></circle>
                                <line x1="20" y1="8" x2="20" y2="14"></line>
                                <line x1="23" y1="11" x2="17" y2="11"></line>
                            </svg></div>
                        <div class="media-body">
                            <span class="m-0"><b>طلاب</b></span> <br>مسجلين اناث</span>
                            <h4 class="mb-0 counter">{{ $student_female_count }}</h4>
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                stroke-linejoin="round" class="feather feather-user-plus icon-bg">
                                <path d="M16 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path>
                                <circle cx="8.5" cy="7" r="4"></circle>
                                <line x1="20" y1="8" x2="20" y2="14"></line>
                                <line x1="23" y1="11" x2="17" y2="11"></line>
                            </svg>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-sm-6 col-xl-3 col-lg-6">
            <div class="card o-hidden border-0">
                <div class="bg-dark b-r-4 card-body">
                    <div class="media static-top-widget">
                        <div class="align-self-center text-center"><svg xmlns="http://www.w3.org/2000/svg" width="24"
                                height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                class="feather feather-user-plus">
                                <path d="M16 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path>
                                <circle cx="8.5" cy="7" r="4"></circle>
                                <line x1="20" y1="8" x2="20" y2="14"></line>
                                <line x1="23" y1="11" x2="17" y2="11"></line>
                            </svg></div>
                        <div class="media-body">
                            <span class="m-0"><b>شركات</b> <br>مسجل بها طلاب</span>
                            <h4 class="mb-0 counter">{{ $company_active }}</h4>
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                stroke-linejoin="round" class="feather feather-user-plus icon-bg">
                                <path d="M16 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path>
                                <circle cx="8.5" cy="7" r="4"></circle>
                                <line x1="20" y1="8" x2="20" y2="14"></line>
                                <line x1="23" y1="11" x2="17" y2="11"></line>
                            </svg>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-sm-6 col-xl-3 col-lg-6">
            <div class="card o-hidden border-0">
                <div class="bg-dark b-r-4 card-body">
                    <div class="media static-top-widget">
                        <div class="align-self-center text-center"><svg xmlns="http://www.w3.org/2000/svg" width="24"
                                height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                class="feather feather-user-plus">
                                <path d="M16 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path>
                                <circle cx="8.5" cy="7" r="4"></circle>
                                <line x1="20" y1="8" x2="20" y2="14"></line>
                                <line x1="23" y1="11" x2="17" y2="11"></line>
                            </svg></div>
                        <div class="media-body">
                            <span class="m-0"><b>شركات</b> <br>غير مسجل بها طلاب</span>
                            <h4 class="mb-0 counter">{{ $company_not_active }}</h4>
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                stroke-linejoin="round" class="feather feather-user-plus icon-bg">
                                <path d="M16 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path>
                                <circle cx="8.5" cy="7" r="4"></circle>
                                <line x1="20" y1="8" x2="20" y2="14"></line>
                                <line x1="23" y1="11" x2="17" y2="11"></line>
                            </svg>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-12 mb-2">
            <h5>اخر الزيارات الميدانية :</h5>
        </div>
        <div class="col-md-12">
            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-sm table-hover">
                                    <thead>
                                        <tr>
                                            <th>الطالب</th>
                                            <th>المشرف</th>
                                            <th>الشركة</th>
                                            <th>وقت الزيارة</th>
                                            <th>العمليات</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @if ($filed_visits->isEmpty())
                                            <tr>
                                                <td colspan="4" class="text-center">لا توجد زيارات</td>
                                            </tr>
                                        @else
                                            @foreach ($filed_visits as $key)
                                                <tr>
                                                    <td>{{ implode(', ', $key->student_names) }}</td>
                                                    <td>{{ $key->fv_supervisor_id }}</td>
                                                    <td>{{ $key->company->c_name }}</td>
                                                    <td>{{ $key->fv_vistit_time }}</td>
                                                    <td><a class="btn btn-primary btn-xs"
                                                            href="{{ route('training_supervisor.field_visits.details', ['id' => $key->fv_id]) }}"><span
                                                                class="fa fa-search"></span></a></td>
                                                </tr>
                                            @endforeach
                                        @endif
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-12">
            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-body">
                            <table class="table table-sm">
                                <thead>
                                    <tr>
                                        <th></th>
                                        <th>السنة</th>
                                        <th>الفصل</th>
                                        <th>عدد الذكور</th>
                                        <th>عدد الاناث</th>
                                        <th>المجموع الكلي</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($results as $key)
                                        <tr>
                                            <td>{{ $key->course_name }}</td>
                                            <td>{{ $key->r_year }}</td>
                                            <td>
                                                @if ($key->r_semester == '1')
                                                    فصل اول
                                                @else
                                                    فصل ثاني
                                                @endif
                                            </td>
                                            <td>{{ $key->male_count }}</td>
                                            <td>{{ $key->female_count }}</td>
                                            <td>{{ $key->total_count }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="card">
            <div class="card-body">
                <div class="row">
                    <div class="col-md-12">
                        <div class="col-md-12" style="padding-top:3%">
                            {{--                    <div class="announcement-header"> --}}
                            {{--                        <h2>اعلانات الكلية</h2> --}}
                            {{--                    </div> --}}
                            <div class="row">
                                <div class="col-md-12">
                                    <button class="btn btn-primary"
                                        onclick="show_add_event_modal()">{{ __('translate.Add event') }}{{-- إضافة حدث --}}</button><br><br>
                                    <div id="calendar">

                                    </div>
                                </div>
                                {{--                        <div class="col-md-12 mt-3"> --}}
                                {{--                            <div class="card"> --}}
                                {{--                                <div class="card-header"> --}}
                                {{--                                    <div class="header-top d-sm-flex align-items-center"> --}}
                                {{--                                        <h5> الاحصائيات </h5> --}}
                                {{--                                    </div> --}}
                                {{--                                </div> --}}
                                {{--                                <div class="card-body"> --}}
                                {{--                                    <p>عدد الطلاب : <span>{{ $student_count }}</span></p> --}}
                                {{--                                    <p>عدد الشركات : <span>{{ $company_count }}</span></p> --}}
                                {{--                                    <p>عدد المشرفين : <span>{{ $supervisor_count }}</span></p> --}}
                                {{--                                    <p>عدد الطلاب الذكور : <span>{{ $student_male_count }}</span></p> --}}
                                {{--                                    <p>عدد الطلاب الاناث : <span>{{ $student_female_count }}</span></p> --}}
                                {{--                                    <p>الشركات المفعلة : <span>{{ $company_active }}</span></p> --}}
                                {{--                                    <p>الشركات غير المفعلة : <span>{{ $company_not_active }}</span></p> --}}
                                {{--                                </div> --}}
                                {{--                            </div> --}}
                                {{--                        </div> --}}
                            </div>
                        </div>
                    </div>
                    @include('modals.addEvent')
                    @include('modals.showEvent')
                    @include('modals.alertToConfirmDelete')
                    @include('layouts.loader')
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <h4>اخبار الكلية</h4>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            @if (count($news) > 0)
                                @foreach ($news as $key)
                                    @foreach ($key['_embedded']['wp:featuredmedia'] as $item)
                                        <div class="col-sm-12 col-xl-6">
                                            <div class="card">
                                                <div class="card-header d-flex"><img
                                                        style="height: {{ $item['media_details']['sizes']['woocommerce_thumbnail']['height'] }};flex: 1"
                                                        src="{{ $item['media_details']['sizes']['woocommerce_thumbnail']['source_url'] }}"
                                                        alt="">
                                                </div>
                                                <div class="card-body">
                                                    {!! $key['excerpt']['rendered'] !!}
                                                        <a href="{{ route('news.details',['id'=>$key['id']]) }}" class="btn btn-primary">
                                                            تفاصيل
                                                        </a>

                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                @endforeach
                            @else
                                <p>لا يوجد اخبار</p>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endsection
    @section('script')
        <script src="https://laravel.pixelstrap.com/viho/assets/js/chart/apex-chart/apex-chart.js"></script>
        <script src="https://laravel.pixelstrap.com/viho/assets/js/dashboard/default.js"></script>

        <script src='https://cdn.jsdelivr.net/npm/fullcalendar@6.1.10/index.global.min.js'></script>
        <script>
            function clear_function() {
                let select = document.getElementById('e_type');
                select.innerHTML = '';
                let array_types = [
                    `{{ __('translate.Everyone') }}`, // الجميع
                    `{{ __('translate.Students of a specific major') }}`, // طلاب تخصص معين
                    `{{ __('translate.Students of a specific course') }}`, //طلاب مساق معين
                    `{{ __('translate.Trainees of a specific company') }}`, // متدربين شركة معينة
                    `{{ __('translate.For all academic supervisors') }}`, //لكل المشرفين الأكادميين
                ];
                for (let i = 0; i < array_types.length; i++) {
                    let option = document.createElement('option');
                    option.value = i;
                    option.text = array_types[i];
                    select.appendChild(option);
                }
                document.getElementById('show_event_information').reset();
                document.getElementById('addEventForm').reset();
            }

            function edit_event() {
                let e_id = document.getElementById('e_id').value;
                let e_title = document.getElementById('show_e_title').value;
                let e_color = document.getElementById('show_e_color').value;
                let e_description = document.getElementById('show_e_description').value;
                let e_type = document.getElementById('show_e_type').value;
                let e_id_type = document.getElementById('show_e_id_type').value;
                let e_start_date = document.getElementById('show_e_start_date').value;
                let e_end_date = document.getElementById('show_e_end_date').value;
                $.ajax({
                    beforeSend: function() {
                        $('#ShowEventModal').modal('hide');
                        $('#LoadingModal').modal('show');
                    },
                    url: "{{ route('admin.calendar.ajax.edit_event') }}",
                    method: 'post',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    data: {
                        'e_id': e_id,
                        'e_title': e_title,
                        'e_color': e_color,
                        'e_description': e_description,
                        'e_type': e_type,
                        'e_id_type': e_id_type,
                        'e_start_date': e_start_date,
                        'e_end_date': e_end_date
                    },
                    success: function(response) {
                        toastr.success(
                            `{{ __('translate.The event information has been successfully updated') }}`
                            ); // تم تعديل معلومات الحدث بنجاح
                        $('#LoadingModal').modal('hide');
                        display_events();
                        clear_function();
                    },
                    error: function(jqXHR) {
                        $('#LoadingModal').modal('hide');
                    }
                });
            }

            function delete_event() {
                $.ajax({
                    url: "{{ route('admin.calendar.ajax.delete_event') }}",
                    method: 'post',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    data: {
                        'e_id': document.getElementById('e_id').value
                    },
                    success: function(response) {
                        $('#confirmDeleteEvent').modal('hide');
                        display_events();
                        toastr.success(
                            `{{ __('translate.The event has been successfully deleted') }}`); // تم حذف الحدث بنجاح
                        clear_function();
                    },
                    error: function(jqXHR) {}
                });
            }

            function show_alert_delete() {
                $('#ShowEventModal').modal('hide');
                $('#confirmDeleteEvent').modal('show');
            }

            function show_add_event_modal() {
                document.getElementById('e_id_type').innerHTML = "";
                document.getElementById('e_id_type').disabled = true;
                $('#AddEventModal').modal('show');
            }

            function action_listener_when_choose_option(option_number, id) {
                if (option_number == 1) {
                    ajax_to_get_majors(id);
                } else if (option_number == 2) {
                    ajax_to_get_courses(id);
                } else if (option_number == 3) {
                    ajax_to_get_companies(id);
                } else {
                    document.getElementById(id).innerHTML = "";
                    document.getElementById(id).disabled = true;
                }
            }

            function ajax_to_get_courses(id) {
                $.ajax({
                    url: "{{ route('admin.calendar.ajax.ajax_to_get_courses') }}",
                    method: 'post',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    data: {},
                    success: function(response) {
                        let e_id_type = document.getElementById(id);
                        e_id_type.innerHTML = "";
                        e_id_type.disabled = false;
                        response.semester_courses.forEach(function(semester_course) {
                            let option = document.createElement('option');
                            option.value = semester_course.sc_course_id;
                            option.text = semester_course.sc_course.c_name;
                            e_id_type.appendChild(option);
                        });
                    },
                    error: function(jqXHR) {}
                });
            }

            function ajax_to_get_majors(id) {
                $.ajax({
                    url: "{{ route('admin.calendar.ajax.ajax_to_get_majors') }}",
                    method: 'post',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    data: {},
                    success: function(response) {
                        let e_id_type = document.getElementById(id);
                        e_id_type.innerHTML = "";
                        e_id_type.disabled = false;
                        response.majors.forEach(function(major) {
                            let option = document.createElement('option');
                            option.value = major.m_id;
                            option.text = major.m_name;
                            e_id_type.appendChild(option);
                        });
                    },
                    error: function(jqXHR) {}
                });
            }

            function ajax_to_get_companies(id) {
                $.ajax({
                    url: "{{ route('admin.calendar.ajax.ajax_to_get_companies') }}",
                    method: 'post',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    data: {},
                    success: function(response) {
                        let e_id_type = document.getElementById(id);
                        e_id_type.innerHTML = "";
                        e_id_type.disabled = false;
                        response.companies.forEach(function(key) {
                            let option = document.createElement('option');
                            option.value = key.c_id;
                            option.text = key.c_name;
                            e_id_type.appendChild(option);
                        });
                    },
                    error: function(jqXHR) {}
                });
            }

            function ajax_to_get_event_information(id) {
                $.ajax({
                    url: "{{ route('admin.calendar.ajax.show_event_information') }}",
                    method: 'post',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    data: {
                        'id': id
                    },
                    success: function(response) {
                        document.getElementById('e_id').value = response.event.e_id;
                        document.getElementById('show_e_color').value = response.event.e_color;
                        document.getElementById('show_e_title').value = response.event.e_title;
                        document.getElementById('show_e_description').value = response.event.e_description;
                        let e_type;
                        if (response.event.e_type === 0) {
                            e_type = `{{ __('translate.Everyone') }}`; // الجميع
                        } else if (response.event.e_type === 1) {
                            e_type = `{{ __('translate.Students of a specific major') }}`; // طلاب تخصص معين
                        } else if (response.event.e_type === 2) {
                            e_type = `{{ __('translate.Students of a specific course') }}`; // طلاب مساق معين
                        } else if (response.event.e_type === 3) {
                            e_type = `{{ __('translate.Trainees of a specific company') }}`; // متدربين شركة معينة
                        } else if (response.event.e_type === 4) {
                            e_type =
                                `{{ __('translate.For all academic supervisors') }}`; // لكل المشرفين الأكادميين
                        }
                        let array_types = [
                            `{{ __('translate.Everyone') }}`, // الجميع
                            `{{ __('translate.Students of a specific major') }}`, // طلاب تخصص معين
                            `{{ __('translate.Students of a specific course') }}`, //طلاب مساق معين
                            `{{ __('translate.Trainees of a specific company') }}`, // متدربين شركة معينة
                            `{{ __('translate.For all academic supervisors') }}`, //لكل المشرفين الأكادميين
                        ];
                        let select = document.getElementById('show_e_type');
                        select.innerHTML = '';
                        let option = document.createElement('option');
                        option.value = response.event.e_type
                        option.text = e_type;
                        select.appendChild(option);
                        for (let i = 0; i < array_types.length; i++) {
                            if (array_types[i] !== e_type) {
                                let option = document.createElement('option');
                                option.value = i;
                                option.text = array_types[i];
                                select.appendChild(option);
                            }
                        }
                        let select_type_name = document.getElementById('show_e_id_type');
                        select_type_name.innerHTML = '';
                        if (response.event_name_type !== null && e_type ===
                            `{{ __('translate.Students of a specific course') }}`) { // طلاب مساق معين
                            document.getElementById('show_e_id_type').disabled = false;
                            let option = document.createElement('option');
                            option.value = response.event_id_type;
                            option.text = response.event_name_type;
                            select_type_name.appendChild(option);
                            for (let i = 0; i < response.data.length; i++) {
                                let option = document.createElement('option');
                                option.value = response.data[i].sc_course_id;
                                option.text = response.data[i].course_name;
                                select_type_name.appendChild(option);
                            }
                        } else if (response.event_name_type !== null && e_type ==
                            `{{ __('translate.Students of a specific major') }}`) { // طلاب تخصص معين
                            document.getElementById('show_e_id_type').disabled = false;
                            let option = document.createElement('option');
                            option.value = response.event_id_type;
                            option.text = response.event_name_type;
                            select_type_name.appendChild(option);
                            for (let i = 0; i < response.data.length; i++) {
                                let option = document.createElement('option');
                                option.value = response.data[i].m_id;
                                option.text = response.data[i].m_name;
                                select_type_name.appendChild(option);
                            }
                        } else if (response.event_name_type !== null && e_type ==
                            `{{ __('translate.Trainees of a specific company') }}`) { // متدربين شركة معينة
                            document.getElementById('show_e_id_type').disabled = false;
                            let option = document.createElement('option');
                            option.value = response.event_id_type;
                            option.text = response.event_name_type;
                            select_type_name.appendChild(option);
                            for (let i = 0; i < response.data.length; i++) {
                                let option = document.createElement('option');
                                option.value = response.data[i].c_id;
                                option.text = response.data[i].c_name;
                                select_type_name.appendChild(option);
                            }
                        } else {
                            document.getElementById('show_e_id_type').disabled = true;
                        }
                        document.getElementById('show_e_start_date').value = response.event.e_start_date;
                        document.getElementById('show_e_end_date').value = response.event.e_end_date;
                        $('#ShowEventModal').modal('show');
                    },
                    error: function(jqXHR) {}
                });
            }
            let AddEventForm = document.getElementById("addEventForm");
            AddEventForm.addEventListener("submit", (e) => {
                e.preventDefault();
                create_event();
            })

            function create_event() {
                let e_title = document.getElementById('e_title').value;
                let e_color = document.getElementById('e_color').value;
                let e_description = document.getElementById('e_description').value;
                let e_type = document.getElementById('e_type').value;
                let e_id_type = document.getElementById('e_id_type').value;
                let e_start_date = document.getElementById('e_start_date').value;
                let e_end_date = document.getElementById('e_end_date').value;
                $.ajax({
                    beforeSend: function() {
                        $('#LoadingModal').modal('show');
                    },
                    url: "{{ route('admin.calendar.create_event') }}",
                    method: 'post',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    data: {
                        'e_title': e_title,
                        'e_color': e_color,
                        'e_description': e_description,
                        'e_type': e_type,
                        'e_id_type': e_id_type,
                        'e_start_date': e_start_date,
                        'e_end_date': e_end_date
                    },
                    success: function(response) {
                        toastr.success(
                            `{{ __('translate.The event has been successfully added') }}`); // تم إضافة الحدث بنجاح
                        $('#LoadingModal').modal('hide');
                        $('#AddEventModal').modal('hide');
                        display_events();
                        clear_function();
                    },
                    error: function(jqXHR) {
                        $('#LoadingModal').modal('hide');
                        clear_function();
                    }
                });
            }

            function display_events() {
                let events = [];
                $.ajax({
                    url: "{{ route('admin.calendar.display_events') }}",
                    method: 'post',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    data: {},
                    success: function(response) {
                        events = response.events;
                        let calendarEl = document.getElementById('calendar');
                        let currentDate = new Date();
                        let calendar = new FullCalendar.Calendar(calendarEl, {
                            initialView: 'dayGridMonth',
                            initialDate: currentDate.toISOString().slice(0, 10), // Set initial current date
                            events: events,
                            eventClick: function(info) {
                                ajax_to_get_event_information(info.event.id);
                            },
                            dateClick: function(info) {
                                var clickedDate = info.date;
                            }
                        });
                        calendar.render();
                    },
                    error: function(jqXHR) {}
                });
            }
            document.addEventListener('DOMContentLoaded', function() {
                display_events();
                clear_function();
            });

            var options = {
                series: [76, 67, 61, 90],
                chart: {
                    height: 390,
                    type: 'radialBar',
                },
                plotOptions: {
                    radialBar: {
                        offsetY: 0,
                        startAngle: 0,
                        endAngle: 270,
                        hollow: {
                            margin: 5,
                            size: '30%',
                            background: 'transparent',
                            image: undefined,
                        },
                        dataLabels: {
                            name: {
                                show: false,
                            },
                            value: {
                                show: false,
                            }
                        },
                        barLabels: {
                            enabled: true,
                            useSeriesColors: true,
                            margin: 8,
                            fontSize: '16px',
                            formatter: function(seriesName, opts) {
                                return seriesName + ":  " + opts.w.globals.series[opts.seriesIndex]
                            },
                        },
                    }
                },
                colors: ['#1ab7ea', '#0084ff', '#39539E', '#0077B5'],
                labels: ['Vimeo', 'Messenger', 'Facebook', 'LinkedIn'],
                responsive: [{
                    breakpoint: 480,
                    options: {
                        legend: {
                            show: false
                        }
                    }
                }]
            };

            var chart = new ApexCharts(document.querySelector("#chart"), options);
            chart.render();
        </script>
    @endsection
@else
    @section('content')
        <div class="card">
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        @if (auth()->user()->u_role_id == 2)
                            {{-- Student --}}
                            @include('includes.studentCard')
                        @elseif (auth()->user()->u_role_id == 3)
                            {{-- Supervisor --}}
                            @include('includes.academicSupervisorCard')
                        @elseif (auth()->user()->u_role_id == 4)
                            {{-- Assistant --}}
                            @include('includes.assistantCard')
                        @elseif (auth()->user()->u_role_id == 5)
                            {{-- M&E --}}
                            @include('includes.monitorEvaluationCard')
                        @elseif (auth()->user()->u_role_id == 6)
                            {{-- Company Manager --}}
                            @include('includes.companyManagerCard')
                        @elseif (auth()->user()->u_role_id == 8)
                            {{-- Communications Manager with Companies --}}
                            @include('includes.communicationsManagerWithCompaniesCard')
                        @endif

                    </div>
                    <div class="col-md-6">
                        <div id="calendar">
                        </div>
                        @include('modals.showEventForAll')
                    </div>
                </div>
                <div class="col-md-12" style="padding-top:1%">
                    <div class="announcement-header">
                        <h2>اعلانات الكلية</h2>
                    </div>
                    <div class="announcemetsBody" style="padding-top:3%">
                        @foreach ($data as $key)
                            {{ $key->created_at->format('F') }}
                            {{ $key->created_at->format('d') }}
                            <br>
                            <a href='{{ route('admin.announcements.edit', ['id' => $key->a_id]) }}'> {{ $key->a_title }}
                            </a>
                            <hr>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <h4>اخبار الكلية</h4>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            @if (count($news) > 0)
                                @foreach ($news as $key)
                                    @foreach ($key['_embedded']['wp:featuredmedia'] as $item)
                                        <div class="col-sm-12 col-xl-6">
                                            <div class="card">
                                                <div class="card-header d-flex"><img
                                                        style="height: {{ $item['media_details']['sizes']['woocommerce_thumbnail']['height'] }};flex: 1"
                                                        src="{{ $item['media_details']['sizes']['woocommerce_thumbnail']['source_url'] }}"
                                                        alt="">
                                                </div>
                                                <div class="card-body">
                                                    {!! $key['excerpt']['rendered'] !!}
                                                        <a href="{{ route('news.details',['id'=>$key['id']]) }}" class="btn btn-primary">
                                                            تفاصيل
                                                        </a>

                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                @endforeach
                            @else
                                <p>لا يوجد اخبار</p>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endsection
    @section('script')
        <script src="https://laravel.pixelstrap.com/viho/assets/js/chart/chartist/chartist.js"></script>
        <script src="https://laravel.pixelstrap.com/viho/assets/js/chart/chartist/chartist-plugin-tooltip.js"></script>
        <script src="https://laravel.pixelstrap.com/viho/assets/js/chart/knob/knob.min.js"></script>
        <script src="https://laravel.pixelstrap.com/viho/assets/js/chart/knob/knob-chart.js"></script>
        <script src="https://laravel.pixelstrap.com/viho/assets/js/chart/apex-chart/apex-chart.js"></script>
        <script src="https://laravel.pixelstrap.com/viho/assets/js/chart/apex-chart/stock-prices.js"></script>
        <script src="https://laravel.pixelstrap.com/viho/assets/js/prism/prism.min.js"></script>
        <script src="https://laravel.pixelstrap.com/viho/assets/js/clipboard/clipboard.min.js"></script>
        <script src="https://laravel.pixelstrap.com/viho/assets/js/counter/jquery.waypoints.min.js"></script>
        <script src="https://laravel.pixelstrap.com/viho/assets/js/counter/jquery.counterup.min.js"></script>
        <script src="https://laravel.pixelstrap.com/viho/assets/js/counter/counter-custom.js"></script>
        <script src="https://laravel.pixelstrap.com/viho/assets/js/custom-card/custom-card.js"></script>
        <script src="https://laravel.pixelstrap.com/viho/assets/js/notify/bootstrap-notify.min.js"></script>
        <script src="https://laravel.pixelstrap.com/viho/assets/js/vector-map/jquery-jvectormap-2.0.2.min.js"></script>
        <script src="https://laravel.pixelstrap.com/viho/assets/js/vector-map/map/jquery-jvectormap-world-mill-en.js"></script>
        <script src="https://laravel.pixelstrap.com/viho/assets/js/vector-map/map/jquery-jvectormap-us-aea-en.js"></script>
        <script src="https://laravel.pixelstrap.com/viho/assets/js/vector-map/map/jquery-jvectormap-uk-mill-en.js"></script>
        <script src="https://laravel.pixelstrap.com/viho/assets/js/vector-map/map/jquery-jvectormap-au-mill.js"></script>
        <script src="https://laravel.pixelstrap.com/viho/assets/js/vector-map/map/jquery-jvectormap-chicago-mill-en.js">
        </script>
        <script src="https://laravel.pixelstrap.com/viho/assets/js/vector-map/map/jquery-jvectormap-in-mill.js"></script>
        <script src="https://laravel.pixelstrap.com/viho/assets/js/vector-map/map/jquery-jvectormap-asia-mill.js"></script>
        <script src="https://laravel.pixelstrap.com/viho/assets/js/dashboard/default.js"></script>
        <script src="https://laravel.pixelstrap.com/viho/assets/js/notify/index.js"></script>
        <script src="https://laravel.pixelstrap.com/viho/assets/js/datepicker/date-picker/datepicker.js"></script>
        <script src="https://laravel.pixelstrap.com/viho/assets/js/datepicker/date-picker/datepicker.en.js"></script>
        <script src="https://laravel.pixelstrap.com/viho/assets/js/datepicker/date-picker/datepicker.custom.js"></script>

        <script src='https://cdn.jsdelivr.net/npm/fullcalendar@6.1.10/index.global.min.js'></script>
        <script src="{{ asset('assets/js/jquery-3.5.1.min.js') }}"></script>
        <script src="{{ asset('assets/js/script.js') }}"></script>
        <script>
            function ajax_to_get_event_information(id) {
                $.ajax({
                    url: "{{ route('allUsersWithoutAdmin.calendar.show_event_information') }}",
                    method: 'post',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    data: {
                        'id': id
                    },
                    success: function(response) {
                        document.getElementById('show_e_title_for_all').value = response.event.e_title;
                        document.getElementById('show_e_description_for_all').value = response.event.e_description;
                        let e_type;
                        if (response.event.e_type === 0) {
                            e_type = `{{ __('translate.Everyone') }}`; // الجميع
                        } else if (response.event.e_type === 1) {
                            e_type = `{{ __('translate.Students of a specific major') }}`; // طلاب تخصص معين
                        } else if (response.event.e_type === 2) {
                            e_type = `{{ __('translate.Students of a specific course') }}`; // طلاب مساق معين
                        } else if (response.event.e_type === 3) {
                            e_type = `{{ __('translate.Trainees of a specific company') }}`; // متدربين شركة معينة
                        } else if (response.event.e_type === 4) {
                            e_type =
                                `{{ __('translate.For all academic supervisors') }}`; // لكل المشرفين الأكادميين
                        }
                        document.getElementById('show_e_type_for_all').value = e_type;
                        document.getElementById('show_e_id_type_for_all').value = response.event_name_type;
                        document.getElementById('show_e_start_date_for_all').value = response.event.e_start_date;
                        document.getElementById('show_e_end_date_for_all').value = response.event.e_end_date;
                        $('#ShowEventModalForAll').modal('show');
                    },
                    error: function(jqXHR) {}
                });
            }

            function display_events() {
                let events = [];
                $.ajax({
                    url: "{{ route('allUsersWithoutAdmin.calendar.display_events') }}",
                    method: 'post',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    data: {},
                    success: function(response) {
                        events = response.events;
                        let calendarEl = document.getElementById('calendar');
                        let currentDate = new Date();
                        let calendar = new FullCalendar.Calendar(calendarEl, {
                            initialView: 'dayGridMonth',
                            initialDate: currentDate.toISOString().slice(0, 10), // Set initial current date
                            events: events,
                            eventClick: function(info) {
                                ajax_to_get_event_information(info.event.id);
                            },
                            dateClick: function(info) {}
                        });
                        calendar.render();
                    },
                    error: function(jqXHR) {}
                });
            }
            document.addEventListener('DOMContentLoaded', function() {
                display_events();
            });
        </script>
    @endsection
@endif
