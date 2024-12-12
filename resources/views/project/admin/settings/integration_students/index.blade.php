@extends('layouts.app')
@section('title')
@endsection
@section('header_title')
@endsection
@section('header_title_link')
    <a href="{{route('home')}}">{{__('translate.Main')}}{{-- الرئيسية --}}</a>
@endsection
@section('header_link')
    <a href="{{route('admin.settings')}}">{{__('translate.Settings')}}{{-- إعدادات  --}}</a>
@endsection
@section('style')
@endsection
@section('content')
    <div class="card" style="padding-left:0px; padding-right:0px;">
        <div class="card-body" >
        <!--loading whole page-->
            <div class="loader-container loader-box" id="loaderContainer" hidden>
                <div class="loader-3"></div>
            </div>
        <div>
        </div>
        <form class="f1" method="post" id="companyForm">
            <div class="f1-steps">
                <div class="f1-progress">
                    <div class="f1-progress-line"></div>
                </div>
                <div class="f1-step active">
                    <div class="f1-step-icon"><i class="fa fa-file-excel-o"></i></div>
                    <p>{{__('translate.Upload Excel File')}}{{-- رفع ملف إكسل --}}</p>
                </div>
                <div class="f1-step">
                    <div class="f1-step-icon"><i class="fa fa-database"></i></div>
                    <p>{{__('translate.Columns Selection')}}{{-- تحديد الأعمدة --}}</p>
                </div>
                <div class="f1-step">
                    <div class="f1-step-icon"><i class="fa fa-refresh"></i></div>
                    <p>{{__('translate.Synchronization')}}{{-- مزامنة --}}</p>
                </div>
            </div>
            <fieldset>
                <div id="errorPageOne">
                </div>
                <div class="row" id="step1">
                    <div class="col-md-6">
                        <div class="mb-3 form-group">
                            <label for="f1-first-name">{{__('translate.Upload Excel File')}}:{{-- رفع ملف إكسل --}}</label>
                            <div class="input-container">
                                <input class="form-control" type="file" id="excel_file" name="excel_file" required="" onchange="upload_excel_file(this)" accept=".xlsx, .xls">
                                <input type="hidden" id="name_file_hidden">
                            </div>
                            <div id="progress-container" style="display: none;">
                                <div class="progress">
                                    <div class="progress-bar bg-primary progress-bar-striped" id="progress-bar" role="progressbar" style="width: 0%;" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100"></div>
                                </div>
                                <span id="progress-text">{{__('translate.Uploading')}}...{{-- جارٍ التحميل --}}</span>
                            </div>
                            <br>
                            <div class="card" style="background-color: #fff891">
                                <ul>
                                    <li>
                                        {{__('translate.You must upload Excel file contains the following headings')}}{{-- يجب رفع ملف إكسل تحتوي على العناوين التالية --}} :
                                    </li>
                                    <ul style="list-style-type: circle">
                                        <li>الرقم الجامعي</li>
                                        <li>الاسم</li>
                                        <li>التخصص</li>
                                        <li>رقم الهاتف المحمول</li>
                                    </ul>
                                </ul>
                            </div>
                            {{-- <a href="{{ asset('FileSample/BeFoundOnline.xlsx') }}" download>{{__('translate.Download Example File')}}تحميل مثال لملف</a> --}}
                            <br><br>
                            <ul>
                                <li>
                                    {{__('translate.Example')}}{{-- مثال --}} :
                                    @if (app()->getLocale() == 'en')
                                        {{-- <img src="{{asset('FileSample/BeFoundOnlineEN.PNG')}}" alt=""> --}}
                                    @else
                                        {{-- <img src="{{asset('FileSample/BeFoundOnlineAR.PNG')}}" alt=""> --}}
                                    @endif
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="f1-buttons">
                    <button class="btn btn-primary btn-next" onclick="validate_step_one()" type="button" id="next1">{{__('translate.Next')}}{{-- التالي --}}</button>
                </div>
            </fieldset>
            <fieldset>
                <div class="row" id="step2">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="f1-last-name">الرقم الجامعي</label>
                            <select id="student_number" name="student_number" class="js-example-basic-single col-sm-12">
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="f1-last-name">الاسم</label>
                            <select id="student_name" name="student_name" class="js-example-basic-single col-sm-12">
                            </select>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="f1-last-name">التخصص</label>
                            <select id="student_major" name="student_major" class="js-example-basic-single col-sm-12">
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="f1-last-name">رقم الهاتف المحمول</label>
                            <select id="student_phone" name="student_phone" class="js-example-basic-single col-sm-12">
                            </select>
                        </div>
                    </div>
                </div>
                <div class="f1-buttons">
                    <button class="btn btn-primary btn-next" type="button">{{__('translate.Next')}}{{-- التالي --}}</button>
                </div>
            </fieldset>
            <fieldset>
                <div class="row p-3 m-5 mt-3">
                    <div class="col-md-12">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <h6>{{__("translate.In this section, clicking on 'Synchronize' will update the fields, establishing a seamless integration between the database and the Excel file")}}{{-- في هذا القسم عند الضغط على مزامنة يتم مزامنة الحقول وعمل تكامل ما بين قاعدة البيانات وملف إكسل --}}</h6>
                                    <div id="progress" style="height: 200px; background-color: #fff891 ;overflow: scroll; ">
                                    </div>
                                    <div id="summary">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                @include('project.admin.users.modals.loading')
                <div class="f1-buttons">
                    <button class="btn btn-primary" onclick="show_confirm_alert()" type="button">{{__('translate.Synchronization')}}{{-- مزامنة --}}</button>
                </div>
            </fieldset>
        </form>
    </div>
        @include('project.admin.settings.integration_students.includes.alertToConfirmIntegration')
    </div>
@endsection
@section('script')
<script src="{{ asset('assets/js/form-wizard/form-wizard-three.js') }}"></script>
<script src="{{asset('assets/js/form-wizard/jquery.backstretch.min.js')}}"></script>
<script src="{{ asset('assets/js/select2/select2.full.min.js') }}"></script>
<script src="{{ asset('assets/js/select2/select2-custom.js') }}"></script>
<script>
    function validate_step_one()
    {
        let file = document.getElementById('excel_file').files[0];
        let nextButton = document.getElementById('next1');
        if (file) {
            let formData = new FormData();
            formData.append('input-file', file);
            $.ajax({
                url: "{{ route('integration.validateStepOne') }}",
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                data: formData,
                contentType: false,
                processData: false,
                success: function (response) {
                    if(response.status === 0) {
                        nextButton.disabled = true;
                        document.getElementById('errorPageOne').innerHTML = `
                            <div class="alert alert-danger">
                                {{__('translate.Please choose Exel file only')}}
                            </div>
                        `;
                    }
                    else {
                        nextButton.disabled = false;
                        document.getElementById('errorPageOne').innerHTML = ``;
                    }
                },
                error: function (error) {
                }
            });
        }
        else {
            nextButton.disabled = true;
            document.getElementById('errorPageOne').innerHTML = `
                <div class="alert alert-danger">
                    {{__('translate.Please choose Exel file only')}}
                </div>
            `;
        }
    }
    function show_confirm_alert()
    {
        $('#confirmIntegrationModal').modal('show');
    }
    function submit_form() {
        let data = [];
        data.push('student_number');
        data.push(document.getElementById('student_number').value);
        data.push('student_name');
        data.push(document.getElementById('student_name').value);
        data.push('student_major');
        data.push(document.getElementById('student_major').value);
        data.push('student_phone');
        data.push(document.getElementById('student_phone').value);
        let file = document.getElementById('excel_file').files[0];
        let name_file_hidden = document.getElementById('name_file_hidden').value;
        if (file) {
            let formData = new FormData();
            formData.append('input-file', file);
            formData.append('data' , data);
            formData.append('name_file_hidden' , name_file_hidden);
            $.ajax({
                beforeSend: function() {
                    $('#confirmIntegrationModal').modal('hide');
                    $('#LoadingModal').modal('show');
                },
                url: "{{ route('admin.settings.integration_students.submitForm') }}",
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                data: formData,
                contentType: false,
                processData: false,
                success: function (response) {
                    $('#LoadingModal').modal('hide');
                    toastr.success(`{{__('translate.The integration was completed successfully')}}`); // تم عمل اندماج بنجاح
                },
                error: function (error) {
                    // alert(error.responseText);
                    $('#LoadingModal').modal('hide');
                }
            });
        }

    }

    // Done
    function create_options(headers , id) {
        let selectOptions = document.getElementById(id) , cnt = 0;
        selectOptions.innerHTML = '';
        let option = document.createElement('option');
        option.value = -1;
        option.text = `{{__('translate.Choose Field')}}`; // اختر الحقل
        selectOptions.appendChild(option);
        headers.forEach(function (header) {
            let option = document.createElement('option');
            option.value = cnt++;
            option.text = header;
            selectOptions.appendChild(option);
        });
    }

    // Done
    function upload_excel_file(input) {
        let file = input.files[0];
        let nextButton = document.getElementById('next1');
        if (file) {
            let formData = new FormData();
            formData.append('input-file', file);
            $(`#progress-container`).show();
            // Make an AJAX request to submit the file
            $.ajax({
                url: "{{ route('admin.settings.integration_students.uploadFileExcel') }}",
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
                    if(response.status === 0) {
                        nextButton.disabled = true;
                        document.getElementById('errorPageOne').innerHTML = `
                            <div class="alert alert-danger">
                                {{__('translate.Please choose Exel file only')}}
                            </div>
                        `;
                    }
                    else {
                        nextButton.disabled = false;
                        document.getElementById('errorPageOne').innerHTML = ``;
                    }
                    $(`#progress-container`).hide();
                    document.getElementById('name_file_hidden').value = response.name_file_hidden;
                    let headers = response.headers;
                    create_options(headers , 'student_number');
                    create_options(headers , 'student_name');
                    create_options(headers , 'student_major');
                    create_options(headers , 'student_phone');
                },
                error: function (error) {
                    // alert(error.responseText);
                    $('#progress-container').hide();
                }
            });
        }
    }
</script>
@endsection
