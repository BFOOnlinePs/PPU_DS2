@extends('layouts.app')
@section('title')
    {{__('translate.Student Attendance')}}{{-- سجل الحضور والمغادرة --}}
@endsection
@section('header_title')
    {{__('translate.Student Attendance')}}{{-- سجل الحضور والمغادرة --}}
@endsection
@section('header_title_link')
    {{__('translate.Student Attendance')}}{{-- سجل الحضور والمغادرة --}}
@endsection
@section('header_link')
@endsection
@section('style')
@endsection
@section('content')
    <div class="container-fluid">
        <div class="card">
            <div class="card-body">
                <div class="row">
                    <div class="col-md-11">
                    </div>
                    <div class="col-md-1">
                        <button class="btn btn-primary btn-xs" onclick="exportToExcel()" type="button"><span class="fa fa-file-excel-o"></span></button>
                    </div>
                </div>
                <br>
                <div class="row">
                    {{-- الاسم --}}
                    <div class="col-md-6">
                        <input type="search" onkeyup="fillter()" class="form-control" placeholder="{{__('translate.Search')}}" aria-label="Search" id="name_fillter" /> {{-- بحث --}}
                    </div>
                    {{-- الشركة --}}
                    <div class="col-md-6">
                        <select autofocus class="js-example-basic-single col-sm-12" onchange="fillter()" id="company_fillter">
                            <option value="">{{__('translate.All Companies')}}{{-- جميع الشركات --}}</option>
                            @foreach ($companies as $company)
                                <option value="{{$company->c_id}}">{{$company->c_name}}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <br>
                <div class="row">
                    {{-- السنة --}}
                    <div class="col-md-2">
                        <input type="number" value="{{$system_settings->ss_year}}" class="form-control" onchange="fillter()" id="year_fillter">
                    </div>
                    {{-- الفصل --}}
                    <div class="col-md-2">
                        <select autofocus class="js-example-basic-single col-sm-12" onchange="fillter()" id="semester_fillter">
                            @if ($system_settings->ss_semester_type == 1)
                                <option value="1">الفصل الأول</option>
                                <option value="2">الفصل الثاني</option>
                                <option value="3">الفصل الصيفي</option>
                            @elseif ($system_settings->ss_semester_type == 2)
                                <option value="2">الفصل الثاني</option>
                                <option value="3">الفصل الصيفي</option>
                                <option value="1">الفصل الأول</option>
                            @elseif ($system_settings->ss_semester_type == 3)
                                <option value="3">الفصل الصيفي</option>
                                <option value="1">الفصل الأول</option>
                                <option value="2">الفصل الثاني</option>
                            @endif
                        </select>
                    </div>
                    {{-- من --}}
                    <div class="col-md-4">
                        <input type="date" class="form-control digits" onchange="fillter()" value="{{date('Y-01-01')}}" id="from_fillter">
                    </div>
                    {{-- إلى --}}
                    <div class="col-md-4">
                        <input type="date" class="form-control digits" onchange="fillter()" value="{{date('Y-m-d')}}" id="to_fillter">
                    </div>
                </div>
                <br>
                <div class="row">
                    <div id="content">
                    </div>
                </div>
            </div>
        </div>
        @include('project.admin.attendance.modals.details')
    </div>
@endsection
@section('script')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.17.0/xlsx.full.min.js"></script>
    <script>
        function exportToExcel() {
            const table = document.getElementById("dataTable");
            const ws = XLSX.utils.table_to_sheet(table);
            const wb = XLSX.utils.book_new();
            XLSX.utils.book_append_sheet(wb, ws, "Sheet1");
            XLSX.writeFile(wb, "data.xlsx");
        }
        $(document).ready(function () {
            fillter();
        });
        function fillter()
        {
            let name = document.getElementById('name_fillter').value;
            let company = document.getElementById('company_fillter').value;
            let year = document.getElementById('year_fillter').value;
            let semester = document.getElementById('semester_fillter').value;
            let from = document.getElementById('from_fillter').value;
            let to = document.getElementById('to_fillter').value;
            $.ajax({
                url: "{{route('admin.attendance.fillter')}}",
                method: 'post',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                beforeSend: function () {

                },
                data: {
                    'name' : name ,
                    'company' : company ,
                    'year' : year ,
                    'semester' : semester ,
                    'from' : from ,
                    'to' : to ,
                },
                success: function (data) {
                    $('#content').html(data.html);
                },
                error: function (xhr, status, error) {

                }
            });
        }
        function details(sa_id)
        {
            $.ajax({
                url: "{{route('admin.attendance.details')}}",
                method: 'post',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                beforeSend: function () {

                },
                data: {
                    'sa_id' : sa_id
                },
                success: function (response) {
                    document.getElementById('name_modal').value = response.data.student.name;
                    document.getElementById('company_modal').value = response.data.company.c_name;
                    document.getElementById('in_time_modal').value = response.data.sa_in_time;
                    document.getElementById('out_time_modal').value = response.data.sa_out_time;
                    if(response.data.report_text != null) {
                        document.getElementById('report_text_modal').value = response.data.report_text;
                    }
                    else {
                        document.getElementById('report_text_modal').value = ``;
                    }
                    if(response.data.attached_file != null) {
                        document.getElementById('attachment_file_modal').href = `{{asset('public/storage/student_reports/${response.data.attached_file}')}}`;
                    }
                    else {
                        document.getElementById('attachment_file_modal').href = ``;
                    }
                    if(response.data.notes_company != null) {
                        document.getElementById('notes_company_modal').value = response.data.notes_company;
                    }
                    else {
                        document.getElementById('notes_company_modal').value = ``;
                    }
                    if(response.data.notes_supervisor != null) {
                        document.getElementById('notes_supervisor_modal').value = response.data.notes_supervisor;
                    }
                    else {
                        document.getElementById('notes_supervisor_modal').value = ``;
                    }
                    if(response.data.notes_company != null) {
                        document.getElementById('notes_company_modal').value = response.data.notes_company;
                    }
                    else {
                        document.getElementById('notes_company_modal').value = ``;
                    }
                    $('#DetailsModal').modal('show');
                },
                error: function (xhr, status, error) {

                }
            });
        }
    </script>
@endsection
