@extends('layouts.app')
@section('title')
    {{ __('translate.Students data integration') }}{{-- تكامل بيانات الطلاب --}}
@endsection
@section('header_title')
    {{ __('translate.Students data integration') }}{{-- تكامل بيانات الطلاب --}}
@endsection
@section('header_title_link')
    <a href="{{ route('home') }}">{{ __('translate.Main') }}{{-- الرئيسية --}}</a>
@endsection
@section('header_link')
    <a href="{{ route('admin.settings') }}">{{ __('translate.Settings') }}{{-- إعدادات  --}}</a>
@endsection
@section('style')
    <style>
    </style>
@endsection
@section('content')
    <div class="card" style="padding-left:0px; padding-right:0px;">
        <div class="card-body">
            <div class="row">
                <form action="{{ route('integration.import_integration_student_excel') }}" method="post"
                    enctype="multipart/form-data">
                    @csrf
                    <div class="col-md-12">
                        {{-- <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="">السنة</label>
                                    <input type="number" required placeholder="مثال : 2024" id="year" name="year" class="form-control">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="">الفصل</label>
                                    <select class="form-control" name="semester" id="">
                                        <option value="1">الفصل الأول</option>
                                        <option value="2">الفصل الثاني</option>
                                        <option value="3">الفصل الصيفي</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="">رقم التدريب العملي</label>
                                    <input  type="number" required placeholder="مثال : 001" id="course_id" name="course_id" class="form-control">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="">اسم التدريب العملي</label>
                                    <input type="text" placeholder="مثال : المرحلة الاولى الجزء الأول" id="course_name" name="course_name" required class="form-control">
                                </div>
                            </div>
                        </div> --}}
                        <div class="row mb-3">
                            <div class="col-md-12">
                                <p class="alert alert-primary text-center text-bold">من خلال النموذج التالي يمكن مزامنة
                                    بيانات الطلاب
                                    سنة <br><br>
                                    <span
                                        class="text-bold bg-dark p-2">{{ \App\Models\SystemSetting::first()->ss_year }}</span>
                                    -
                                    <span class="text-bold bg-dark p-2">
                                        فصل
                                        @if (\App\Models\SystemSetting::first()->ss_semester_type == 1)
                                            أول
                                        @elseif (\App\Models\SystemSetting::first()->ss_semester_type == 2)
                                            ثاني
                                        @elseif (\App\Models\SystemSetting::first()->ss_semester_type == 3)
                                            صيفي
                                        @endif
                                    </span>
                                    <br>
                                    <br>
                                    <span class="text-bold">لتغيير الفصل الحالي اول السنة الحالية من خلال اعدادات
                                        النظام</span>
                                </p>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="">اضافة ملف اكسيل</label>
                                <input type="file" class="form-control" name="file">
                                <a download="student_template_excel"
                                    href="{{ asset('assets/file/student_template_excel.xlsx') }}" class="">تحميل مثال
                                    </p>
                            </div>
                        </div>
                        <button type="submit" class="btn btn-primary">حفظ</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    @include('project.admin.settings.includes.alertToConfirmIntegration')
    </div>
@endsection
@section('script')
    <script src="{{ asset('assets/js/form-wizard/form-wizard-three.js') }}"></script>
    <script src="{{ asset('assets/js/form-wizard/jquery.backstretch.min.js') }}"></script>
    <script src="{{ asset('assets/js/select2/select2.full.min.js') }}"></script>
    <script src="{{ asset('assets/js/select2/select2-custom.js') }}"></script>
    <script>
        function validate_step_one() {
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
                    success: function(response) {
                        if (response.status === 0) {
                            nextButton.disabled = true;
                            document.getElementById('errorPageOne').innerHTML = `
                            <div class="alert alert-danger">
                                {{ __('translate.Please choose Exel file only') }}
                            </div>
                        `;
                        } else {
                            upload_excel_file(file);
                            nextButton.disabled = false;
                            document.getElementById('errorPageOne').innerHTML = ``;
                        }
                    },
                    error: function(error) {}
                });
            } else {
                nextButton.disabled = true;
                document.getElementById('errorPageOne').innerHTML = `
                <div class="alert alert-danger">
                    {{ __('translate.Please choose Exel file only') }}
                </div>
            `;
            }
        }

        function show_confirm_alert() {
            $('#confirmIntegrationModal').modal('show');
        }

        function submit_form() {
            let data = [];
            data.push('year');
            data.push(document.getElementById('year').value);
            data.push('semester');
            data.push(document.getElementById('semester').value);
            data.push('course_id');
            data.push(document.getElementById('course_id').value);
            data.push('course_name');
            data.push(document.getElementById('course_name').value);
            data.push('university_number');
            data.push(document.getElementById('university_number').value);
            data.push('student_name');
            data.push(document.getElementById('student_name').value);
            data.push('gender');
            data.push(document.getElementById('gender').value);
            data.push('address');
            data.push(document.getElementById('address').value);
            data.push('regiment_number');
            data.push(document.getElementById('regiment_number').value);
            data.push('major_name');
            data.push(document.getElementById('major_name').value);
            data.push('major_number');
            data.push(document.getElementById('major_number').value);
            data.push('u_tawjihi_gpa');
            data.push(document.getElementById('u_tawjihi_gpa').value);
            data.push('u_phone1');
            data.push(document.getElementById('u_phone1').value);
            data.push('u_date_of_birth');
            data.push(document.getElementById('u_date_of_birth').value);
            data.push('supervisor');
            data.push(document.getElementById('supervisor').value);
            data.push('company_name');
            data.push(document.getElementById('company_name').value);
            data.push('manager_name');
            data.push(document.getElementById('manager_name').value);
            data.push('manager_phone');
            data.push(document.getElementById('manager_phone').value);
            data.push('email');
            data.push(document.getElementById('email').value);
            // data.push('manager_password');
            // data.push(document.getElementById('manager_password').value);

            // data.push('year');
            // data.push(document.getElementById('year').value);
            // data.push('semester');
            // data.push(document.getElementById('semester').value);
            // data.push('student_id');
            // data.push(document.getElementById('student_id').value);
            // data.push('student_name');
            // data.push(document.getElementById('student_name').value);
            // data.push('student_gender');
            // data.push(document.getElementById('student_gender').value);
            // data.push('course_id');
            // data.push(document.getElementById('course_id').value);
            // data.push('course_name');
            // data.push(document.getElementById('course_name').value);
            // data.push('major_id');
            // data.push(document.getElementById('major_id').value);
            // data.push('major_name');
            // data.push(document.getElementById('major_name').value);
            // data.push('u_tawjihi_gpa');
            // data.push(document.getElementById('u_tawjihi_gpa').value);
            // data.push('u_company_id');
            // data.push(document.getElementById('u_company_id').value);
            // data.push('u_phone1');
            // data.push(document.getElementById('u_phone1').value);
            // data.push('u_date_of_birth');
            // data.push(document.getElementById('u_date_of_birth').value);
            // data.push('email');
            // data.push(document.getElementById('email').value);
            // data.push('manager_name');
            // data.push(document.getElementById('manager_name').value);
            // data.push('manager_phone');
            // data.push(document.getElementById('manager_phone').value);
            // data.push('manager_password');
            // data.push(document.getElementById('manager_password').value);
            let file = document.getElementById('excel_file').files[0];
            let name_file_hidden = document.getElementById('name_file_hidden').value;
            if (file) {
                let formData = new FormData();
                formData.append('input-file', file);
                formData.append('data', data);
                formData.append('name_file_hidden', name_file_hidden);
                $.ajax({
                    beforeSend: function() {
                        $('#confirmIntegrationModal').modal('hide');
                        $('#LoadingModal').modal('show');
                    },
                    url: "{{ route('integration.submitForm') }}",
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    data: formData,
                    contentType: false,
                    processData: false,
                    success: function(response) {
                        $('#LoadingModal').modal('hide');
                        const locale = "{{ app()->getLocale() }}";
                        let progress = '';
                        let courses_array = response.courses_array;
                        let majors_array = response.majors_array;
                        let students_numbers = response.students_numbers_array;
                        let students_names = response.students_names_array;
                        let registration_array = response.registration_array;
                        if (locale === 'ar') {
                            document.getElementById("summary").innerHTML = `
                        <div class="alert alert-success">
                        تم إضافة عدد ${response.user_object} من الطلاب
                        </div>
                        <div class="alert alert-success">
                            تم إضافة عدد ${response.course_object} من التدريبات العملية
                        </div>
                        <div class="alert alert-success">
                            تم إضافة عدد ${response.major_object} من التخصصات
                        </div>
                        <div class="alert alert-success">
                            تم تسجيل ${response.registration_object} من الطلاب
                        </div>
                        `;
                            for (let i = 0; i < courses_array.length; i += 2) {
                                progress +=
                                    `<p>تم تسجيل التدريب العملي ${courses_array[i + 1]} ، رقمه ${courses_array[i]}</p>`;
                            }
                            for (let i = 0; i < majors_array.length; i += 2) {
                                progress +=
                                    `<p>تم تسجيل تخصص ${majors_array[i + 1]} ، رقمه ${majors_array[i]}</p>`;
                            }
                            for (let i = 0; i < students_numbers.length; i++) {
                                progress +=
                                    `<p>تم إضافة طالب اسمه ${students_names[i]} ، و رقمه الجامعي هو ${students_numbers[i]}</p>`;
                            }
                            for (let i = 0; i < registration_array.length; i += 5) {
                                let semester = `الصيفي`;
                                if (registration_array[i + 3] == 1) {
                                    semester = `الأول`;
                                } else if (registration_array[i + 3] == 2) {
                                    semester = `الثاني`;
                                }
                                progress +=
                                    `<p>تم تسجيل الطالب ${registration_array[i]} الّذي يحمل الرقم الجامعي ${registration_array[i + 1]} ، في التدريب العملي ${registration_array[i + 2]} لسنة ${registration_array[i + 4]} في الفصل ${semester}</p>`;
                            }
                            document.getElementById('progress').innerHTML = progress;
                        } else {
                            document.getElementById("summary").innerHTML = `
                        <div class="alert alert-success">
                            Added ${response.user_object} students
                        </div>
                        <div class="alert alert-success">
                            Added ${response.course_object} courses
                        </div>
                        <div class="alert alert-success">
                            Added ${response.major_object} majors
                        </div>
                        <div class="alert alert-success">
                            Registered ${response.registration_object} students
                        </div>
                        `;
                            for (let i = 0; i < courses_array.length; i += 2) {
                                progress +=
                                    `<p>A course with number ${courses_array[i]} titled ${courses_array[i + 1]} has been registered.</p>`;
                            }
                            for (let i = 0; i < majors_array.length; i += 2) {
                                progress +=
                                    `<p>A major with number ${majors_array[i]} titled ${majors_array[i + 1]} has been registered.</p>`;
                            }
                            for (let i = 0; i < students_numbers.length; i++) {
                                progress +=
                                    `<p>A student named ${students_names[i]} with university ID ${students_numbers[i]} has been added.</p>`;
                            }
                            for (let i = 0; i < registration_array.length; i += 5) {
                                let semester = `Summer`;
                                if (registration_array[i + 3] == 1) {
                                    semester = `First`;
                                } else if (registration_array[i + 3] == 2) {
                                    semester = `Second`;
                                }
                                progress +=
                                    `<p>Student ${registration_array[i]} with university ID ${registration_array[i + 1]} has been registered in course ${registration_array[i + 2]} for the year ${registration_array[i + 4]} in the ${semester} semester.</p>`;
                            }
                            document.getElementById('progress').innerHTML = progress;
                        }
                    },
                    error: function(error) {
                        $('#LoadingModal').modal('hide');
                    }
                });
            }

        }

        function create_options(headers, id) {
            let selectOptions = document.getElementById(id),
                cnt = 0;
            selectOptions.innerHTML = '';
            let option = document.createElement('option');
            option.value = -1;
            option.text = `{{ __('translate.Choose Field') }}`; // اختر الحقل
            selectOptions.appendChild(option);
            headers.forEach(function(header) {
                let option = document.createElement('option');
                option.value = cnt++;
                option.text = header;
                selectOptions.appendChild(option);
            });
        }

        function upload_excel_file(input) {
            let file = input.files[0];
            let nextButton = document.getElementById('next1');
            if (file) {
                let formData = new FormData();
                formData.append('input-file', file);
                $(`#progress-container`).show();
                // Make an AJAX request to submit the file
                $.ajax({
                    url: "{{ route('integration.uploadFileExcel') }}",
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    data: formData,
                    contentType: false,
                    processData: false,
                    xhr: function() {
                        var xhr = new XMLHttpRequest();
                        xhr.upload.addEventListener("progress", function(event) {
                            if (event.lengthComputable) {
                                var percentComplete = (event.loaded / event.total) * 100;
                                $(`#progress-bar`).css('width', percentComplete + '%');
                                $(`#progress-bar`).attr('aria-valuenow', percentComplete);
                                $(`#progress-text`).text('Uploading: ' + percentComplete.toFixed(2) +
                                    '%');
                            }
                        }, false);
                        return xhr;
                    },
                    success: function(response) {
                        if (response.status === 0) {
                            nextButton.disabled = true;
                            document.getElementById('errorPageOne').innerHTML = `
                            <div class="alert alert-danger">
                                {{ __('translate.Please choose Exel file only') }}
                            </div>
                        `;
                        } else {
                            nextButton.disabled = false;
                            document.getElementById('errorPageOne').innerHTML = ``;
                        }
                        $(`#progress-container`).hide();
                        document.getElementById('name_file_hidden').value = response.name_file_hidden;
                        let headers = response.headers;

                        create_options(headers, 'year');
                        create_options(headers, 'semester');
                        create_options(headers, 'course_id');
                        create_options(headers, 'course_name');
                        create_options(headers, 'university_number');
                        create_options(headers, 'student_name');
                        create_options(headers, 'gender');
                        create_options(headers, 'address');
                        create_options(headers, 'regiment_number');
                        create_options(headers, 'major_name');
                        create_options(headers, 'major_number');
                        create_options(headers, 'u_tawjihi_gpa');
                        create_options(headers, 'u_phone1');
                        create_options(headers, 'u_date_of_birth');
                        create_options(headers, 'supervisor');
                        create_options(headers, 'company_name');
                        create_options(headers, 'manager_name');
                        create_options(headers, 'manager_phone');
                        create_options(headers, 'email');
                        create_options(headers, 'manager_password');
                    },
                    error: function(error) {
                        $('#progress-container').hide();
                    }
                });
            }
        }
    </script>
@endsection
