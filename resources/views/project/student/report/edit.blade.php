@extends('layouts.app')
@section('title')
{{__('translate.Submit Report')}}{{--تسليم التقرير--}}
@endsection
@section('header_title')
{{__('translate.Submit Report')}}{{--تسليم التقرير--}}
@endsection
@section('header_title_link')
<a href="{{route('home')}}">{{__('translate.Main')}}{{-- الرئيسية --}}</a>
@endsection
@section('header_link')
<a href="{{route('students.attendance.index')}}">{{__('translate.Attendance Logs')}} {{-- سِجل الحضور و المغادرة --}}</a>
@endsection
@section('content')
@section('style')
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/dropzone.css') }}">
@endsection
<div class="container-fluid">
    <div class="email-wrap">
        <div class="row">
            <div class="col-xl-12 col-md-12">
                <div class="email-right-aside">
                    <div class="card email-body">
                        <div class="email-profile">
                            <div class="email-body">
                                <div class="email-compose">

                                    <div class="email-wrapper">
                                        @if(session('success'))
                                            <div class="alert alert-success" id="success">
                                                {{ session('success') }}
                                            </div>
                                        @elseif(session('danger'))
                                            <div class="alert alert-danger">
                                                {{ session('danger') }}
                                            </div>
                                        @endif
                                        <form action="{{route('students.attendance.report.submit')}}" class="theme-form" method="post" enctype="multipart/form-data" id="myForm">
                                            @csrf
                                            <div class="alert alert-danger" id="error" style="display: none">
                                                {{__('translate.Notes must be written on the report')}}{{--يجب كتابة ملاحظات عن التقرير--}}
                                            </div>
                                            <input type="hidden" id="latitude" name="latitude">
                                            <input type="hidden" id="longitude" name="longitude">
                                            <input type="text" value="{{$sa_id}}" name="sa_id" id="sa_id" hidden>
                                            <div class="form-group">
                                                <label class="col-form-label pt-0">{{__('translate.Report Notes')}}{{--ملاحظات عن التقرير--}}</label>
                                                <textarea name="sr_report_text" class="form-control" cols="4" rows="4" id="sr_report_text">@if (isset($student_report->sr_report_text)){{$student_report->sr_report_text}}@endif</textarea>
                                            </div>
                                            <div class="form-group">
                                                <label class="dropzone digits text-center dz-clickable" id="singleFileUpload">
                                                    <input type="file" onchange="submitFile(this)" id="input-file" name="file" hidden>
                                                    <div class="dz-message needsclick">
                                                        <i class="icon-cloud-up"></i>
                                                        <h6>{{__('translate.Drag the file here or click to upload')}}{{--قم بسحب الملف هنا أو انقر للرفع--}}</h6>
                                                    </div>
                                                    <div id="progress-container" style="display: none;">
                                                        <div class="progress">
                                                            <div class="progress-bar bg-primary progress-bar-striped" id="progress-bar" role="progressbar" style="width: 0%;" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100"></div>
                                                        </div>
                                                        <span id="progress-text">Uploading...</span>
                                                    </div>
                                                    @if (isset($student_report->sr_attached_file))
                                                        <button class="btn-close" type="button" onclick="remove_file()" id="remove_button"></button>
                                                        <input type="hidden" name="save_file" id="save_file" value="true">
                                                        <a href="{{ asset('public/storage/student_reports/'.$student_report->sr_attached_file) }}" id="downloadLink" download>{{$student_report->sr_attached_file}}</a>
                                                    @else
                                                        <button class="btn-close" type="button" onclick="remove_file()" style="display: none" id="remove_button"></button>
                                                        <a href="" id="downloadLink" style="display: none"></a>
                                                        <input type="hidden" name="save_file" id="save_file" value="false">
                                                    @endif
                                                </label>
                                            </div>
                                            <button type="submit" class="btn btn-secondary" id="submitButton"><i class="fa fa-paper-plane me-2"></i>{{__('translate.Submit Report')}}{{--تسليم التقرير--}}</button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
@section('script')
    <script src="{{ asset('assets/js/dropzone/dropzone.js') }}"></script>
    <script src="{{ asset('assets/js/dropzone/dropzone-script.js') }}"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/dropzone/5.7.0/dropzone.min.js"></script>
    <script>
        const dropArea = document.getElementById('singleFileUpload');
        const inputFile = document.getElementById('input-file');

        dropArea.addEventListener("dragover", function(e) {
            e.preventDefault();
        });
        dropArea.addEventListener("drop", function(e) {
            e.preventDefault();
            inputFile.files = e.dataTransfer.files;
            submitFile(inputFile);
        });
        function remove_file() {
            document.getElementById('remove_button').style.display = "none";
            document.getElementById('save_file').value = false;
            document.getElementById('downloadLink').href = "";
            document.getElementById('downloadLink').style.display = "none";
            document.getElementById('input-file').value = '';
        }
        function download_file() {
            let file = document.getElementById('input-file').files[0];
            const blob = new Blob([file], { type: 'application/octet-stream' });
            const url = URL.createObjectURL(blob);

            // Create an invisible anchor element to trigger the download
            const a = document.createElement('a');
            a.href = url;
            a.download = file.name;
            // a.style.display = 'none';

            // Trigger the click event to initiate the download
            document.body.appendChild(a);
            a.click();

            // Clean up
            URL.revokeObjectURL(url);
            document.body.removeChild(a);
        }
        function submitFile(input) {
            document.getElementById('save_file').value = true;
            let file = input.files[0];
            if (file) {
                let formData = new FormData();
                formData.append('input-file', file);
                $(`#progress-container`).show();
                // Make an AJAX request to submit the file
                $.ajax({
                    url: "{{ route('students.attendance.report.upload') }}",
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
                                $(`#progress-bar`).css('width', percentComplete + '%');
                                $(`#progress-bar`).attr('aria-valuenow', percentComplete);
                                $(`#progress-text`).text('Uploading: ' + percentComplete.toFixed(2) + '%');
                            }
                        }, false);
                        return xhr;
                    },
                    success: function (response) {
                        $(`#progress-container`).hide();
                        $('#downloadLink').text(response.newHref);
                        let file = document.getElementById('input-file').files[0];
                        const blob = new Blob([file], { type: 'application/octet-stream' });
                        const url = URL.createObjectURL(blob);

                        // Create an invisible anchor element to trigger the download
                        const a = document.createElement('a');
                        a.href = url;
                        a.download = file.name;

                        // Trigger the click event to initiate the download
                        document.body.appendChild(a);

                        // Clean up
                        URL.revokeObjectURL(url);

                        $('#downloadLink').attr('href', url);
                        $("#downloadLink").css("display", "");
                        $("#remove_button").css("display", "");
                        const downloadLink = document.getElementById('downloadLink');
                        downloadLink.removeAttribute('download');
                        downloadLink.onclick = function(event) {
                            event.preventDefault();
                            download_file();
                        };
                    },
                    error: function (error) {
                        // Handle error, if needed
                        console.error(error);
                        $('#progress-container').hide();
                    }
                });
            }
        }
        var submitButton = document.getElementById('submitButton');
        function showPosition(position) {
            document.querySelector('#latitude').value = position.coords.latitude;
            document.querySelector('#longitude').value = position.coords.longitude;
            let sr_report_text = document.getElementById('sr_report_text').value;
            if(sr_report_text == '')
            {
                document.getElementById('error').style.display = '';
                document.getElementById('success').style.display = 'none';
            }
            else {
                document.getElementById('myForm').submit();
            }
        }
        submitButton.addEventListener('click', function (event) {
            event.preventDefault(); // Prevent the form from submitting (optional)

            navigator.permissions.query({ name: 'geolocation' }).then(function(permissionStatus) {
                // Check the state of geolocation permission
                if (permissionStatus.state === 'granted') {
                    // Geolocation is allowed
                    navigator.geolocation.getCurrentPosition(showPosition);
                }
                else if(permissionStatus.state === 'denied') {
                    alert('To submit your report, you need to allow access to your location. Please go to the : \n \'chrome://settings/content/location\' \n to enable location access in your browser settings:\n(Or you can manually enable it by going to "Settings" -> "Privacy and security" -> "Site settings" -> "Permissions" -> "Location")');
                }
                else {
                    // Geolocation is denied or prompt is blocked
                    // Handle accordingly
                    alert('To submit your report, you need to allow access to your location.');
                    navigator.geolocation.getCurrentPosition(showPosition);
                }
            });
        });
        </script>
@endsection
