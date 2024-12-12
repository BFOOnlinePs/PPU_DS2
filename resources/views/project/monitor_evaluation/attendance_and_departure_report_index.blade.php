@extends('layouts.app')
@section('title')
{{__("translate.attendance_and_departure_report")}}{{-- تقرير الحضور والمغادرة --}}
@endsection
@section('header_title')
{{__("translate.attendance_and_departure_report")}}{{-- تقرير الحضور والمغادرة --}}
@endsection
@section('header_title_link')
<a href="{{route('home')}}">{{__('translate.Main')}}{{-- الرئيسية --}}</a>
@endsection
@section('header_link')
<!-- <a href="{{route('monitor_evaluation.companiesPaymentsReport')}}">{{__("translate.Companies Payments' Report")}}{{-- تقرير الحضور والمغادرة --}}</a> -->
@endsection

@section('style')

<style>
.loader-container {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background-color: rgba(0, 0, 0, 0.35); /* خلفية شفافة لشاشة التحميل */
    display: flex;
    justify-content: center;
    align-items: center;
    z-index: 9999; /* يجعل شاشة التحميل فوق جميع العناصر الأخرى */
    }
</style>
@endsection
@section('content')
<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-body">
                <div class="row">
                    <div class="col-md-12">
                        <a class="btn btn-success btn-sm" href="{{ route('monitor_evaluation.export_student_attendance') }}">تصدير الى اكسيل</a>
                        <a class="btn btn-primary btn-sm" href="{{ route('monitor_evaluation.statistic_attendance.statistic_attendance_index') }}">احصائيات الحضور والمغادرة</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-body">
                <table class="table table-sm">
                    <thead>
                        <tr>
                            <th></th>
                            <th>عدد الطلاب</th>
                            <th>من اصل</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>عدد الطلاب المداومين في جميع الشركات</td>
                            <td>230</td>
                            <td>500</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-body">

                <div class="row mt-3">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="">{{ __('translate.student_name') }}</label>
                            <input onkeyup="attendance_and_departure_report()" type="text" id="student_search" class="form-control" id="search_student" placeholder="اسم الطالب">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="">{{ __('translate.company_name') }}</label>
                            <select onchange="attendance_and_departure_report()" class="js-example-basic-single" name="" id="company_id">
                                <option value="">اختر شركة ...</option>
                                @foreach($comapny as $key)
                                    <option value="{{ $key->c_id }}">{{ $key->c_name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="">{{ __('translate.Semester') }}</label>
                            <select onchange="attendance_and_departure_report()" class="form-control" name="" id="semester">
                                <option value="">اختر فصل</option>
                                <option value="1">الفصل الاول</option>
                                <option value="2">الفصل الثاني</option>
                                <option value="3">الفصل الصيفي</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="">{{ __('translate.Year') }}</label>
                            <input onchange="attendance_and_departure_report()" id="year" type="number" min="1900" max="2099" step="1" value="{{ $system_settings->ss_year ?? date('Y') }}" class="form-control" />
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="">{{ __('translate.in_time') }}</label>
                            <input onchange="attendance_and_departure_report()" id="from" type="date" class="form-control">
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="">{{ __('translate.out_time') }}</label>
                            <input onchange="attendance_and_departure_report()" id="to" type="date" class="form-control">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-md-12">
        <div class="card" style="padding-left:0px; padding-right:0px;">
            <div class="card-body" >
                <div class="row">
                    <div class="col-md-12">
                        <div id="attendance_and_departure_report_table">

                        </div>
                    </div>
                </div>
            </div>
        </div>
        @include('project.monitor_evaluation.modals.show_report_for_attendance_modal')
    </div>
</div>
@endsection
@section('script')

<script>

    $(document).ready(function(){
        attendance_and_departure_report();
    });

    function attendance_and_departure_report(){
        var csrfToken = $('meta[name="csrf-token"]').attr('content');
            // Send an AJAX request with the CSRF token
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': csrfToken
                }
            });
            $('#attendance_and_departure_report_table').html(`<div class="loader-box">
                    <div class="loader-3"></div>
                  </div>`);
            $.ajax({
                type: 'POST',
                url: "{{ route('monitor_evaluation.attendance_and_departure_report_table') }}",
                data: {
                    'company_id' : $('#company_id').val(),
                    'student_search' : $('#student_search').val(),
                    'semester' : $('#semester').val(),
                    'year' : $('#year').val(),
                    'from' : $('#from').val(),
                    'to' : $('#to').val(),

                },
                dataType: 'json',
                success: function(response) {
                    $('#attendance_and_departure_report_table').html(response.view)
                },
                error: function(xhr, status, error) {
                    console.error(xhr.responseText);
                }
            });
        }

        function show_report_from_modal(text_report) {
            $('#report_text').html(`${text_report}`);
        }
</script>


@endsection
