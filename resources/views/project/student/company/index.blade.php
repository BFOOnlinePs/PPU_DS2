@extends('layouts.app')
@section('title')
{{__('translate.Companies')}} {{--  الشركات --}}
@endsection
@section('header_title')
{{__('translate.Companies')}} {{--   الشركات --}}
@endsection
@section('header_title_link')
<a href="{{route('home')}}">{{__('translate.Main')}}{{-- الرئيسية --}}</a>
@endsection
@section('header_link')
<a href="{{route('students.company.index')}}">{{__('translate.Companies')}}{{-- الشركات  --}}</a>
@endsection
@section('style')
<link rel="stylesheet" type="text/css" href="{{ asset('assets/css/select2.css') }}">
@endsection
@section('content')
    <div class="container-fluid">
        <div id="content">
            @include('project.student.company.ajax.companyList')
        </div>
        @include('project.admin.users.modals.loading')
    </div>
@endsection
@section('script')
        <script>
            function DepartureRegistration(sa_student_company_id) {
                getLocation().then(function (position) {
                    navigator.permissions.query({ name: 'geolocation' }).then(function(permissionStatus) {
                        // Check the state of geolocation permission
                        if (permissionStatus.state === 'granted') {
                            document.getElementById("latitude").value = position.coords.latitude;
                            document.getElementById("longitude").value = position.coords.longitude;
                            $.ajax({
                                beforeSend: function(){
                                    $('#LoadingModal').modal('show');
                                },
                                url: "{{route('students.attendance.create_departure')}}",
                                method: 'post',
                                headers: {
                                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                                },
                                data: {
                                    'sa_student_company_id' : sa_student_company_id,
                                    'sa_end_time_latitude' : document.getElementById("latitude").value,
                                    'sa_end_time_longitude' : document.getElementById("longitude").value
                                },
                                success: function(response) {

                                    $('#content').html(response.html);
                                    if(response.alert_departure === true) {
                                        document.getElementById('alert_departure').style.display = 'block';
                                    }
                                    $('#LoadingModal').modal('hide');
                                    window.location.href = '{{ route("students.company.attendance.index_for_specific_student", ":id") }}'.replace(":id", sa_student_company_id);
                                },
                                complete: function(){
                                    $('#LoadingModal').modal('hide');
                                },
                                error: function(jqXHR) {
                                    alert(jqXHR.responseText);
                                    alert('Error fetching user data.');
                                }
                            });
                        }
                    });
                }).catch(function (error) {
                    alert('To record your departure, you need to allow access to your location from your browser settings. \n Or record attendance from application.');
                });
            }
            function getLocation() {
                return new Promise(function (resolve, reject) {
                    navigator.geolocation.getCurrentPosition(resolve, reject);
                });
            }
            function AttendanceRegistration(sa_student_company_id) {
                getLocation().then(function (position) {
                    navigator.permissions.query({ name: 'geolocation' }).then(function(permissionStatus) {
                    // Check the state of geolocation permission
                        if (permissionStatus.state === 'granted') {
                            document.getElementById("latitude").value = position.coords.latitude;
                            document.getElementById("longitude").value = position.coords.longitude;
                            $.ajax({
                                beforeSend: function(){
                                    $('#LoadingModal').modal('show');
                                },
                                url: "{{route('students.attendance.create_attendance')}}",
                                method: 'post',
                                headers: {
                                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                                },
                                data: {
                                    'sa_student_company_id' : sa_student_company_id,
                                    'sa_start_time_latitude' : document.getElementById("latitude").value,
                                    'sa_start_time_longitude' : document.getElementById("longitude").value
                                },
                                success: function(response) {
                                    // alert(response.html);
                                    //alert(response.html);
                                    $('#content').html(response.html);
                                    document.getElementById('attendance_id').textContent = "{{__('translate.Training Check-Out')}}";
                                    $('#LoadingModal').modal('hide');
                                    window.location.href = '{{ route("students.company.attendance.index_for_specific_student", ":id") }}'.replace(":id", sa_student_company_id);

                                },
                                complete: function(){
                                    $('#LoadingModal').modal('hide');
                                },
                                error: function(jqXHR) {
                                    alert(jqXHR.responseText);
                                    alert('Error fetching user data.');
                                }
                            });
                        }
                    });
                }).catch(function(error){
                    alert('To record your attendance, you need to allow access to your location from your browser settings. \n Or record attendance from application.');
                });
            }
    </script>
@endsection
