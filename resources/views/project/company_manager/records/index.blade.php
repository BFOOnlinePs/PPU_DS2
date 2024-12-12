@extends('layouts.app')
@section('title')
{{__('translate.Attendance Logs')}} {{-- سِجل الحضور و المغادرة --}}
@endsection
@section('header_title')
{{__('translate.Attendance Logs')}} {{-- سِجل الحضور و المغادرة --}}
@endsection
@section('header_title_link')
<a href="{{route('home')}}">{{__('translate.Main')}}{{-- الرئيسية --}}</a>
@endsection
@section('header_link')
<a href="{{route('company_manager.records.index')}}">{{__('translate.Attendance Logs')}} {{-- سِجل الحضور و المغادرة --}}</a>
@endsection
@section('content')
    <div class="container-fluid">
        <div class="card">
            <div class="card-header pb-0">
                <h4 class="card-title mb-0">{{__("translate.Company's Trainees Attendance")}}{{-- سِجل المتابعة لجميع متدربي الشركة --}}</h4>
            </div>
            <div class="row card-body">
                <div class="col-md-6">
                    <input type="text" class="form-control" id="searchByName" onkeyup="function_to_filltering()">
                </div>
                <div class="col-md-3">
                    <input type="date" class="form-control digits" id="from" onchange="function_to_filltering()" value="{{date('Y-01-01')}}">
                </div>
                <div class="col-md-3">
                    <input type="date" class="form-control digits" id="to" onchange="function_to_filltering()" value="{{date('Y-m-d')}}">
                </div>
            </div>
            <div class="card-body" id="content">
            </div>
        </div>
        @include('project.company_manager.students.reports.modals.reportModal')
    </div>
    @include('project.student.attendance.ajax.loading')
@endsection
@section('script')
    <script>
        $(document).ready(function() {
            function_to_filltering();
        });
        function function_to_filltering()
        {
            let from = $('#from').val();
            let to = $('#to').val();
            let searchByName = document.getElementById('searchByName').value;
            $.ajax({
                    url: "{{ route('company_manager.records.search') }}",
                    method: 'post',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    beforeSend: function () {
                        document.getElementById('loading').style.display = '';
                    },
                    data: {
                        'searchByName': searchByName,
                        'from': from,
                        'to': to
                    },
                    success: function (data) {
                        if(data.html == '') {
                            document.getElementById('loading').style.display = 'none';
                        }
                        else {
                            $('#content').html(data.html);
                            document.getElementById('loading').style.display = 'none';
                        }
                    },
                    error: function (xhr, status, error) {
                        document.getElementById('loading').style.display = 'none';
                    }
                });
        }
        function openReportModal(report_sr_id) {
            document.getElementById('report_sr_id').value = report_sr_id;
            $.ajax({
                beforeSend: function(){
                },
                url: "{{route('company_manager.students.reports.showReport')}}",
                method: 'post',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                data: {
                    'report_sr_id' : report_sr_id,
                },
                success: function(response) {
                    document.getElementById('sr_report_text').value = response.sr_report_text;
                    if(response.sr_attached_file != null){
                        document.getElementById('sr_attached_file').href = `{{asset('public/storage/student_reports/${response.sr_attached_file}')}}`;
                        document.getElementById('sr_attached_file').style.display = '';
                    }
                },
                complete: function(){

                },
                error: function(jqXHR) {

                }
            });
            $('#StudentReportModal').modal('show');
        }
    </script>
@endsection

