@extends('layouts.app')
@section('title')
    {{ __('translate.Main') }}{{-- الرئيسية  --}}
@endsection
@section('header_title')
    {{ __('translate.Majors Management') }}{{-- إدارة التخصصات --}}
@endsection
@section('header_title_link')
    <a href="{{ route('home') }}">{{ __('translate.Main') }}{{-- الرئيسية --}}</a>
@endsection
@section('header_link')
    <a href="{{ route('admin.majors.index') }}"> {{ __('translate.Majors') }}{{-- التخصصات --}}</a>
@endsection
@section('style')
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/select2.css') }}">
    <style>
        table td,
        table th {
            text-overflow: ellipsis;
            white-space: nowrap;
            overflow: hidden;
        }

        tbody td {
            font-weight: 500;
            color: #999999;
        }

        .input-error {
            border-color: #d22d3d;
        }

        .icon {
            position: absolute;
            left: 20px;
            /* Adjust the left position to control the icon's placement */
            top: 50%;
            transform: translateY(-50%);
        }

        .icon_spinner {
            position: absolute;
            left: 20px;
            /* Adjust the left position to control the icon's placement */
            top: 30%;
            transform: translateY(-50%);
        }

        /* Style the input to provide some spacing for the icon */
        input {
            padding-left: 30px;
            /* Add padding to the left of the input to make room for the icon */
            width: 100%;
            /* Make the input take up the full width of the container */
        }
    </style>
@endsection
@section('content')

    <div>
        <button class="btn btn-primary  mb-2 btn-s" onclick="$('#AddMajorModal').modal('show')" type="button"
            id="openAddModalButton"><span class="fa fa-plus"></span>{{ __('translate.Add Major') }}
            {{-- إضافة تخصص --}}</button>
    </div>

    <div class="card" style="padding-left:0px; padding-right:0px;">

        <div class="card-body">
            {{-- <div class="form-outline">
            <input type="search" onkeyup="majorSearch(this.value)" class="form-control mb-2" placeholder="البحث"
                aria-label="Search" />
        </div> --}}
            <div class="form-outline" id="showSearch" hidden>
                <input type="search" onkeyup="majorSearch(this.value)" class="form-control mb-2"
                    placeholder="{{ __('translate.Search') }}" aria-label="Search" /> {{-- بحث --}}
            </div>
            @if (!$data->isEmpty())
                <div class="form-outline">
                    <input type="search" onkeyup="majorSearch(this.value)" class="form-control mb-2"
                        placeholder="{{ __('translate.Search') }}" aria-label="Search" /> {{-- بحث --}}
                </div>
            @endif

            <div id="majorsTable">
                <div class="table-responsive">
                    <table class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th scope="col" style="display:none;">id</th>
                                <th scope="col">{{ __('translate.Major Name') }} {{-- اسم التخصص --}}</th>
                                <th scope="col">{{ __('translate.Major Reference Code') }} {{-- الرمز المرجعي للتخصص --}}</th>
                                <th scope="col">{{ __('translate.Academic Supervisor') }} {{-- المشرف --}}</th>
                                <th scope="col">{{ __('translate.Operations') }} {{--  العمليات --}}</th>

                            </tr>
                        </thead>
                        <tbody>

                            @if ($data->isEmpty())
                                <tr>
                                    <td colspan="5" class="text-center">
                                        <span>{{ __('translate.No data to display') }}{{-- لا توجد بيانات --}}</span>
                                    </td>
                                </tr>
                            @else
                                @foreach ($data as $major)
                                    <tr>
                                        <td style="display:none;">{{ $major->m_id }}</td>
                                        <td>{{ $major->m_name }}</td>
                                        {{-- <td>{{ $major->m_description }}</td> --}}
                                        <td>{{ $major->m_reference_code }}</td>
                                        <td>
                                            <select class="js-example-basic-single col-sm-12"
                                                id="supervisor_{{ $major->m_id }}" multiple="multiple"
                                                onchange="showSuperVisorModal({{ $major }})">
                                                @foreach ($superVisors as $super)
                                                    @php
                                                        // التحقق إذا كان المشرف مرتبطًا بالتخصص
                                                        $isSelected = $major->majorSupervisors->contains(function ($majorSupervisor) use ($super) {
                                                            return $majorSupervisor->ms_super_id == $super->u_id;
                                                        });
                                                    @endphp
                                                    <option value="{{ $super->u_id }}" {{ $isSelected ? 'selected' : '' }}>
                                                        {{ $super->name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </td>
                                        <td>
                                            {{-- <button class="btn btn-info" onclick="showMajorModal({{ $major }})"><i class="fa fa-info"></i></button> --}}
                                            <button class="btn btn-primary" onclick="showEditModal({{ $major }})"><i
                                                    class="fa fa-edit"></i></button>
                                        </td>
                                    </tr>
                                @endforeach
                            @endif

                        </tbody>
                    </table>
                </div>
            </div>

            @include('project.admin.majors.modals.addMajorModal')

            @include('project.admin.majors.modals.editMajorModal')

            @include('project.admin.majors.modals.selectSuperVisorModal')

            @include('project.admin.majors.modals.showMajorModal')

            @include('layouts.loader')

        </div>

    </div>
@endsection
@section('script')
    <script>
        let AddMajorForm = document.getElementById("addMajorForm");
        let EditMajorForm = document.getElementById("editMajorForm");
        let AddSuperVisorForm = document.getElementById("AddSuperVisorForm");

        let language = document.documentElement.lang

        $(document).ready(function() {
            var iconSpinners = document.querySelectorAll('.icon_spinner');
            var icons = document.querySelectorAll('.icon');

            iconSpinners.forEach((iconSpinner) => {

                iconSpinner.style.left = 'auto';
                iconSpinner.style.right = 'auto';
                iconSpinner.style.position = 'absolute';
                iconSpinner.style.top = '30%';
                iconSpinner.style.transform = 'translateY(-50%)';

                if (language == 'ar') {
                    iconSpinner.style.left = '20px';
                } else {
                    iconSpinner.style.right = '20px';
                }

            });

            icons.forEach((icon) => {

                icon.style.left = 'auto';
                icon.style.right = 'auto';
                icon.style.position = "absolute";
                icon.style.top = "50%";
                icon.style.transform = "translateY(-50%)";

                if (language == 'ar') {
                    icon.style.left = '20px';
                } else {
                    icon.style.right = '20px';
                }

            });
        });



        AddMajorForm.addEventListener("submit", (e) => {

            majors = {!! json_encode($data, JSON_HEX_APOS) !!};
            majorsLength = majors.length;

            var if_submit = true;
            e.preventDefault();
            data = $('#addMajorForm').serialize();
            console.log(data)
            var csrfToken = $('meta[name="csrf-token"]').attr('content');

            var formDataArray = data.split('&').map(function(item) {
                var pair = item.split('=');
                return {
                    name: pair[0],
                    value: decodeURIComponent(pair[1] || '')
                };
            });

            //start from 1 cause 0 is for token when selialize a form
            for (var i = 1; i < formDataArray.length; i++) {
                if (formDataArray[i].value == "") {
                    var x = `#${formDataArray[i].name}`;
                    $(`${x}`).addClass('input-error');
                    if_submit = false;
                }
            }


            if (if_submit) {
                // Send an AJAX request with the CSRF token
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': csrfToken
                    }
                });

                // Send an AJAX request
                $.ajax({
                    beforeSend: function() {
                        $('#AddMajorModal').modal('hide');
                        $('#LoadingModal').modal('show');
                    },
                    type: 'POST',
                    url: "{{ route('admin.majors.create') }}",
                    data: data,
                    success: function(response) {

                        $('#AddMajorModal').modal('hide');
                        $('#majorsTable').html(response.view);

                        document.getElementById('m_name').value = "";
                        document.getElementById('m_reference_code').value = "";
                        document.getElementById('m_description').value = "";
                    },
                    complete: function() {
                        $('#LoadingModal').modal('hide');
                        if (majorsLength == 0) {
                            document.getElementById('showSearch').hidden = false;
                        }
                    },
                    error: function(xhr, status, error) {
                        console.error("error" + error);
                    }
                });
            }



        });

        $('#m_name').on('focus', function() {
            $('#m_name').removeClass('input-error');
        });
        $('#m_reference_code').on('focus', function() {
            $('#m_reference_code').removeClass('input-error');
        });
        $('#m_description').on('focus', function() {
            $('#m_description').removeClass('input-error');
        });
        $('#edit_m_name').on('focus', function() {
            $('#edit_m_name').removeClass('input-error');
        });
        $('#edit_m_reference_code').on('focus', function() {
            $('#edit_m_reference_code').removeClass('input-error');
        });
        $('#edit_m_description').on('focus', function() {
            $('#edit_m_description').removeClass('input-error');
        });


        function showEditModal(data) {
            console.log("data");
            console.log(data.major_supervisors.users);
            console.log(document.getElementById('edited_supervisor'));
            document.getElementById('edit_m_id').value = data.m_id
            document.getElementById('edit_m_name').value = data.m_name
            document.getElementById('edit_m_description').value = data.m_description
            document.getElementById('edit_m_reference_code').value = data.m_reference_code
            document.getElementById('oldRefCode').value = data.m_reference_code;

            // document.getElementById('edited_supervisor').value = data

            $('#editMajorModal').modal('show')
        }

        function showSuperVisorModal(data) {
            superVisor = [];
            const selectElement = document.getElementById("supervisor_" + data.m_id);
            for (const option of selectElement.options) {
                if (option.selected) {
                    superVisor.push(option.value);
                }
            }

            var url;
            if (data.major_supervisors.length == 0) {
                url = "{{ route('admin.majors.addSuperVisor') }}";
            } else {
                url = "{{ route('admin.majors.updateSuperVisor') }}";
            }
            console.log("eeeeeee")


            var csrfToken = $('meta[name="csrf-token"]').attr('content');

            // Send an AJAX request with the CSRF token
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': csrfToken
                }
            });

            // Send an AJAX request
            $.ajax({
                beforeSend: function() {

                    $('#LoadingModal').modal('show');
                },
                type: 'POST',
                url: url,
                data: {
                    superVisor: superVisor,
                    selected_m_id: data.m_id

                },
                dataType: 'json',
                success: function(response) {
                    // $('#majorsTable').html(response.view);
                },
                complete: function() {

                    $('#LoadingModal').modal('hide');
                },
                error: function(xhr, status, error) {
                    console.error("error" + error);
                }
            });
        }

        EditMajorForm.addEventListener("submit", (e) => {
            var if_submit = true;
            e.preventDefault();

            // استخدم serialize لجمع البيانات
            var data = $('#editMajorForm').serialize();

            console.log("data");
            console.log(data);

            var csrfToken = $('meta[name="csrf-token"]').attr('content');

            // تقسيم البيانات وتحويلها إلى مصفوفة
            var formDataArray = data.split('&').map(function(item) {
                var pair = item.split('=');
                return {
                    name: pair[0],
                    value: decodeURIComponent(pair[1] || '')
                };
            });

            // ابدأ من 1 لأن العنصر الأول هو CSRF token
            // for (var i = 1; i < formDataArray.length; i++) {
            //     var fieldValue = formDataArray[i].value.trim(); // استخدم trim لإزالة المسافات
            //     if (fieldValue === "") {
            //         var fieldName = formDataArray[i].name;
            //         var x = `#edit_${fieldName}`; // اسم الحقل
            //         $(`${x}`).addClass('input-error'); // أضف كلاس خطأ
            //         if_submit = false;
            //     } else {
            //         // إزالة كلاس الخطأ إن لم يكن الحقل فارغًا
            //         var fieldName = formDataArray[i].name;
            //         var x = `#edit_${fieldName}`;
            //         $(`${x}`).removeClass('input-error');
            //     }
            // }

            // if (if_submit) {
            // تجهيز الطلب AJAX مع CSRF
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': csrfToken
                }
            });

            // إرسال الطلب AJAX
            $.ajax({
                beforeSend: function() {
                    $('#editMajorModal').modal('hide');
                    $('#LoadingModal').modal('show');
                },
                type: 'POST',
                url: "{{ route('admin.majors.update') }}", // تحقق من صحة الرابط
                data: data,
                success: function(response) {
                    $('#majorsTable').html(response.view); // تحديث الجدول بعد التعديل
                    $('#editMajorModal').modal('hide');
                },
                complete: function() {
                    $('#LoadingModal').modal('hide');
                },
                error: function(xhr, status, error) {
                    console.error("Error: " + error);
                    console.error("Response Text: " + xhr.responseText);
                }
            });
            // }
        });

        function showMajorModal(data) {
            document.getElementById('show_m_name').value = data.m_name;
            document.getElementById('show_m_description').value = data.m_description;
            document.getElementById('show_m_reference_code').value = data.m_reference_code;

            $('#showMajorModal').modal('show');
        }

        function majorSearch(data) {
            var csrfToken = $('meta[name="csrf-token"]').attr('content');

            // Send an AJAX request with the CSRF token
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': csrfToken
                }
            });
            $('#majorsTable').html(
                '<div class="modal-body text-center"><div class="loader-box"><div class="loader-3" ></div></div></div>');

            $.ajax({
                url: "{{ route('admin.majors.search') }}", // Replace with your own URL
                method: "post",
                data: {
                    'search': data,
                    _token: '{!! csrf_token() !!}',
                }, // Specify the expected data type
                success: function(data) {

                    $('#majorsTable').html(data.view);
                },
                error: function(xhr, status, error) {
                    // This function is called when there is an error with the request
                    alert('error');
                }
            });
        }
        $('.modal').on('shown.bs.modal', function() {
            $(this).find('[autofocus]').focus();
        });


        $("#AddMajorModal").on("hidden.bs.modal", function() {
            document.getElementById('m_name').value = "";
            document.getElementById('m_reference_code').value = "";
            document.getElementById('m_description').value = "";

            $('#m_name').removeClass('input-error');
            $('#m_reference_code').removeClass('input-error');
            $('#m_description').removeClass('input-error');

            document.getElementById('similarMajorCodeMessage').hidden = true;
            document.getElementById('search_icon').hidden = true;
            document.getElementById('ok_icon').hidden = true;

            document.getElementById('add_major').disabled = false;

        });

        $("#editMajorModal").on("hidden.bs.modal", function() {

            $('#edit_m_name').removeClass('input-error');
            $('#edit_m_reference_code').removeClass('input-error');
            $('#edit_m_description').removeClass('input-error');

            document.getElementById('edit_similarMajorCodeMessage').hidden = true;
            document.getElementById('edit_search_icon').hidden = true;
            document.getElementById('edit_ok_icon').hidden = true;

            document.getElementById('edit_major').disabled = false;

        });


        function validateInput(input) {

            let pattern = /^[^\d][\u0600-\u06FF\u0750-\u077F\u08A0-\u08FFa-zA-Z0-9\s-]*$/;

            if (!pattern.test(input.value)) {
                input.value = '';
            }
        }

        function validateEngNumInput(inputElement) {

            //console.log("hi")
            var cleanedValue = inputElement.value.replace(/[^a-zA-Z0-9]/g, '');
            if (!/^[a-zA-Z0-9]+$/.test(cleanedValue)) {
                inputElement.value = cleanedValue;
            } else {
                inputElement.value = cleanedValue;
            }

        }

        function checkMajorCode(data, page) {
            console.log("hi reeem")
            old = document.getElementById('oldRefCode').value;
            console.log(old)
            console.log(data)
            if (page == "edit") {

                document.getElementById('edit_ok_icon').hidden = true;

                if (data != "" && data != old) {

                    var csrfToken = $('meta[name="csrf-token"]').attr('content');

                    $.ajaxSetup({
                        headers: {
                            'X-CSRF-TOKEN': csrfToken
                        }
                    })

                    $.ajax({
                        beforeSend: function() {
                            document.getElementById('edit_search_icon').hidden = false;
                        },
                        url: "{{ route('admin.majors.checkMajorCode') }}",
                        method: "post",
                        data: {
                            'search': data,
                            _token: '{!! csrf_token() !!}',
                        },
                        success: function(response) {

                            if (response.data != null) {

                                document.getElementById('edit_search_icon').hidden = true;
                                document.getElementById('edit_ok_icon').hidden = true;
                                document.getElementById('edit_similarMajorCodeMessage').hidden = false;
                                document.getElementById('edit_major').disabled = true;

                            } else {

                                document.getElementById('edit_similarMajorCodeMessage').hidden = true;
                                document.getElementById('edit_search_icon').hidden = true;
                                document.getElementById('edit_ok_icon').hidden = false;
                                document.getElementById('edit_major').disabled = false;

                            }

                        },
                        error: function(xhr, status, error) {
                            alert('error');
                        }
                    });
                } else {
                    document.getElementById('edit_similarMajorCodeMessage').hidden = true;
                    document.getElementById('edit_search_icon').hidden = true;
                    document.getElementById('edit_ok_icon').hidden = true;
                }

            } else {

                document.getElementById('ok_icon').hidden = true;

                if (data != "") {

                    var csrfToken = $('meta[name="csrf-token"]').attr('content');

                    $.ajaxSetup({
                        headers: {
                            'X-CSRF-TOKEN': csrfToken
                        }
                    })

                    $.ajax({
                        beforeSend: function() {
                            document.getElementById('search_icon').hidden = false;
                        },
                        url: "{{ route('admin.majors.checkMajorCode') }}",
                        method: "post",
                        data: {
                            'search': data,
                            _token: '{!! csrf_token() !!}',
                        },
                        success: function(response) {

                            if (response.data != null) {
                                document.getElementById('search_icon').hidden = true;
                                document.getElementById('ok_icon').hidden = true;
                                document.getElementById('similarMajorCodeMessage').hidden = false;
                                document.getElementById('add_major').disabled = true;
                            } else {
                                document.getElementById('similarMajorCodeMessage').hidden = true;
                                document.getElementById('search_icon').hidden = true;
                                document.getElementById('ok_icon').hidden = false;
                                document.getElementById('add_major').disabled = false;
                            }

                        },
                        error: function(xhr, status, error) {
                            alert('error');
                        }
                    });
                } else {
                    document.getElementById('similarMajorCodeMessage').hidden = true;
                    document.getElementById('search_icon').hidden = true;
                    document.getElementById('ok_icon').hidden = true;
                }

            }
        }
    </script>




    <!-- end -->
@endsection
