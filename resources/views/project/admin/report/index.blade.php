@extends('layouts.app')
@section('title')
    {{__("translate.Semesters' Courses")}}{{-- التدريبات العملية للفصول --}}
@endsection
@section('header_title')
    {{__("translate.Semesters' Courses")}}{{-- التدريبات العملية للفصول --}}
@endsection
@section('header_title_link')
    <a href="{{route('home')}}">{{__('translate.Main')}}{{-- الرئيسية --}}</a>
@endsection
@section('header_link')
    <a href="{{route('admin.courses.index')}}">{{__('translate.Courses')}}{{--التدريبات العملية--}}</a>
@endsection

@section('style')

@endsection

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <input onkeyup="list_report_history()" class="form-control" id="course_name" type="text" placeholder="اسم التدريب">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <input onkeyup="list_report_history()" class="form-control" id="year" type="text" placeholder="السنة">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <select onchange="list_report_history()" class="form-control" name="" id="semester">
                                    <option value="">جميع الفصول</option>
                                    <option value="1">فصل أول</option>
                                    <option value="2">فصل ثاني</option>
                                </select>
                            </div>
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
                    <div id="report_history_table" class="table-responsive">

                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('script')
    <script>
        $(document).ready(function () {
            list_report_history();
        });
        function list_report_history() {
            var csrfToken = $('meta[name="csrf-token"]').attr('content');
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': csrfToken
                }
            });
            $.ajax({
                type: 'POST',
                url: "{{ route('admin.reports.report_history_ajax') }}",
                data: {
                    course_name : $('#course_name').val(),
                    year : $('#year').val(),
                    semester : $('#semester').val(),
                },
                dataType: 'json',
                success: function(response) {
                    $('#report_history_table').html(response.view);
                },
                error: function(xhr, status, error) {
                    alert("nooo");
                    console.error(xhr.responseText);
                }
            });
        }
    </script>
@endsection
