@extends('layouts.app')
@section('title')
{{__('translate.Users')}}{{-- المستخدمين --}}
@endsection
@section('header_title_link')
{{__('translate.Users')}}{{-- المستخدمين --}}
@endsection
@section('header_link')
@endsection
@section('content')
<div class="container-fluid">
    <div class="edit-profile">
        <div class="col-xl-12">
            <form class="card">
                <div class="card-header pb-0">
                    <h4 class="card-title mb-0">{{__('translate.Attendance At')}}{{--تسجيل الحضور و المغادرة لدى شركة--}} {{$student_company->company->c_name}}</h4>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <input type="hidden" id="latitude">
                                <input type="hidden" id="longitude">
                                @if ($last_date != $date_today)
                                    <button id="AttendanceRegistrationButton" class="btn btn-primary btn-sm custom-btn" onclick="AttendanceRegistration({{$student_company->sc_id}} , {{$student_company->sc_student_id}})" type="button"><span class="fa fa-plus"></span>{{__('translate.Training Check-In')}}{{-- تسجيل حضور --}}</button>
                                @endif
                            </div>
                            @include('project.admin.users.modals.loading')
                            {{-- @include('project.admin.users.modals.confirm_attendance') --}}
                        </div>
                    </div>
                    <div class="row" id="content">
                        @include('project.admin.users.ajax.submitAttendanceList')
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
@section('script')
<script>
    function submitFile(input, sa_id) {
            let file = input.files[0];
            if (file) {
                let formData = new FormData();
                formData.append('sa_id', sa_id);
                formData.append('file_report_student', file);

                $(`#progress-container${sa_id}`).show();

                // Make an AJAX request to submit the file
                $.ajax({
                    url: "{{ route('student.submit.report') }}",
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    data: formData,
                    contentType: false,
                    processData: false,
                    xhr: function () {
                        var xhr = new XMLHttpRequest();
                        xhr.upload.addEventListener("progress", function (event) {
                            if (event.lengthComputable) {
                                var percentComplete = (event.loaded / event.total) * 100;
                                $(`#progress-bar${sa_id}`).css('width', percentComplete + '%');
                                $(`#progress-bar${sa_id}`).attr('aria-valuenow', percentComplete);
                                $(`#progress-text${sa_id}`).text('Uploading: ' + percentComplete.toFixed(2) + '%');
                            }
                        }, false);
                        return xhr;
                    },
                    success: function (response) {
                        // Handle success, if needed
                        // $('#content').html(response.html);
                        alert(response.html);
                        $(`#progress-container${sa_id}`).hide();
                    },
                    error: function (error) {
                        // Handle error, if needed
                        console.error(error);
                        $('#progress-container').hide();
                    }
                });
            }
        }
    function getLocation(sa_student_company_id , sa_student_id) {
        if ("geolocation" in navigator) {
                navigator.geolocation.getCurrentPosition(function (position) {
                    var latitude = position.coords.latitude;
                    var longitude = position.coords.longitude;
                    document.getElementById("latitude").textContent = latitude;
                    document.getElementById("longitude").textContent = longitude;
                    $.ajax({
                        beforeSend: function(){
                            $('#LoadingModal').modal('show');
                        },
                        url: "{{route('student.submit.attendance')}}",
                        method: 'post',
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        data: {
                            'sa_student_company_id' : sa_student_company_id,
                            'sa_student_id' : sa_student_id,
                            'sa_start_time_latitude' : latitude,
                            'sa_start_time_longitude' : longitude
                        },
                        success: function(response) {
                            $('#content').html(response.html);
                            document.getElementById('AttendanceRegistrationButton').style.display = 'none';
                        },
                        complete: function(){
                            $('#LoadingModal').modal('hide');
                        },
                        error: function(jqXHR) {
                            alert(jqXHR.responseText);
                            alert('Error fetching user data.');
                        }
                    });
                });
            } else {
                alert("Geolocation is not available in your browser.");
            }
    }
    function AttendanceRegistration(sa_student_company_id , sa_student_id) {
        getLocation(sa_student_company_id , sa_student_id);
    }
    function getLocationDeparture(sa_id) {
        if ("geolocation" in navigator) {
                navigator.geolocation.getCurrentPosition(function (position) {
                    var latitude = position.coords.latitude;
                    var longitude = position.coords.longitude;
                    document.getElementById("latitude").textContent = latitude;
                    document.getElementById("longitude").textContent = longitude;
                    $.ajax({
                        beforeSend: function(){
                            $('#LoadingModal').modal('show');
                        },
                        url: "{{route('student.submit.departure')}}",
                        method: 'post',
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        data: {
                            'sa_id' : sa_id,
                            'sa_end_time_latitude' : latitude,
                            'sa_end_time_longitude' : longitude
                        },
                        success: function(response) {
                            $('#content').html(response.html);
                        },
                        complete: function(){
                            $('#LoadingModal').modal('hide');
                        },
                        error: function(jqXHR) {
                            alert(jqXHR.responseText);
                            alert('Error fetching user data.');
                        }
                    });
                });
            } else {
                alert("Geolocation is not available in your browser.");
            }
        }
    function confirm_departure(sa_id) {
        getLocationDeparture(sa_id);
    }
</script>

@endsection
