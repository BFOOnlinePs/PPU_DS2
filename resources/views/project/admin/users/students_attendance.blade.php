
@extends('layouts.app')
@section('title')
{{__('translate.Attendance Logs')}} {{-- سِجل الحضور و المغادرة --}}
@endsection
@section('header_title')
{{__('translate.Attendance Logs')}} {{-- سِجل الحضور و المغادرة --}}
@endsection
@section('header_title_link')
<a href="{{route('admin.users.index')}}">{{__('translate.Users')}}{{-- المستخدمين --}}</a>
@endsection
@section('header_link')
<a href="{{route('admin.users.details' , ['id'=>$user->u_id])}}">{{$user->name}}</a> / {{__('translate.Attendance Logs')}} {{-- سِجل الحضور و المغادرة --}}
@endsection


@section('style')
<link rel="stylesheet" type="text/css" href="{{ asset('assets/css/select2.css') }}">
@endsection
@section('content')
<div class="container-fluid">
    <div class="page-header pb-1">
      <div class="row">
        <div class="col-sm-6">
        </div>
        <div class="col-sm-6">
        </div>
      </div>
    </div>
  </div>
<div class="container-fluid">
    @if (auth()->user()->u_role_id == 2) {{-- For student doesn't show menu student --}}

    @else
        <div class="p-2 pt-0 row">
            @include('project.admin.users.includes.menu_student')
        </div>
    @endif
    <div class="edit-profile">
      <div class="row">
        @if (auth()->user()->u_role_id == 2) {{-- For student doesn't show information card student --}}
            <div class="col-xl-12">
        @else
            <div class="col-xl-3">
                @include('project.admin.users.includes.information_edit_card_student')
            </div>
            <div class="col-xl-9">
        @endif
          <form class="card">
            <div class="card-header pb-0">
              {{-- <h4 class="card-title mb-0">{{__('translate.Attendance Logs')}} سِجل الحضور و المغادرة</h4> --}}
              <div class="card-options"><a class="card-options-collapse" href="#" data-bs-toggle="card-collapse"><i class="fe fe-chevron-up"></i></a><a class="card-options-remove" href="#" data-bs-toggle="card-remove"><i class="fe fe-x"></i></a></div>
            </div>
            <div class="card-body">
              <div class="row" id="content">
                @include('project.admin.users.ajax.studentsAttendanceList')
              </div>
            </div>
          </form>
        </div>
      </div>
    </div>
    @include('project.admin.users.modals.map_attendance')
    <div id="report_student_modal">
        @include('project.admin.users.modals.report_student')
    </div>
  </div>
@endsection
@section('script')
    <script>
        function submit_notes_supervisor(sr_id)
        {
            let sr_notes = document.getElementById("sr_notes").value;
            $.ajax({
                beforeSend: function(){
                },
                url: "{{route('admin.users.report.student.edit')}}",
                method: 'post',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                data: {
                    'sr_id' : sr_id,
                    'sr_notes' : sr_notes
                },
                success: function(response) {
                    $('#EditStudentReportModal').modal('hide');
                },
                complete: function(){

                },
                error: function(jqXHR) {
                    alert(jqXHR.responseText);
                    alert('Error fetching user data.');
                }
            });
        }
        function report(sa_id)
        {
            $.ajax({
                beforeSend: function(){
                },
                url: "{{route('admin.users.report.student.display')}}",
                method: 'post',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                data: {
                    'sa_id' : sa_id
                },
                success: function(response) {
                    $('#report_student_modal').html(response.modal);
                    $('#EditStudentReportModal').modal('show');
                },
                complete: function(){

                },
                error: function(jqXHR) {
                    alert(jqXHR.responseText);
                    alert('Error fetching user data.');
                }
            });
        }
        var latitude1 , longitude1 , latitude2 , longitude2;
        function map(a , b , c , d){
            latitude1 = parseFloat(a); // Your latitude
            longitude1 = parseFloat(b); // Your longitude
            latitude2 = parseFloat(c); // Your latitude
            longitude2 = parseFloat(d); // Your longitude
            initMap();
            $('#studentsAttendanceModal').modal('show');
        }
        function initMap() {
            var map1 = new google.maps.Map(document.getElementById('map1'), {
                center: { lat: latitude1, lng: longitude1 },
                zoom:12
            });

            var marker = new google.maps.Marker({
                position: { lat: latitude1, lng: longitude1 },
                map: map1,
                title: `{{__("translate.Student's Check-In Location")}}`
            });

            var map2 = new google.maps.Map(document.getElementById('map2'), {
                center: { lat: latitude2, lng: longitude2 },
                zoom:12
            });

            var marker = new google.maps.Marker({
                position: { lat: latitude2, lng: longitude2 },
                map: map2,
                title: `{{__("translate.Student's Check-Out Location")}}`
            });
        }
    </script>
    <script async defer
    src="https://maps.googleapis.com/maps/api/js?key=AIzaSyDnw9Vg4m3Vh6LM4krLUJ8Vn8AD6pRYXVI&callback=initMap&libraries=drawing&callback=initMap">
    </script>
@endsection
