@extends('layouts.app')
@section('title')
    {{ __("translate.Semester's Report") }}{{-- تقرير فصل --}}
@endsection
@section('header_title')
    {{ __("translate.Semester's Report") }}{{-- تقرير فصل --}}
@endsection
@section('header_title_link')
    <a href="{{ route('home') }}">{{ __('translate.Main') }}{{-- الرئيسية --}}</a>
@endsection
@section('header_link')
    <a
        href="{{ route('monitor_evaluation.semesterReport') }}">{{ __("translate.Semester's Report") }}{{-- تقرير فصل --}}</a>
@endsection

@section('style')
    <style>
        .loader-container {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.35);
            /* خلفية شفافة لشاشة التحميل */
            display: flex;
            justify-content: center;
            align-items: center;
            z-index: 9999;
            /* يجعل شاشة التحميل فوق جميع العناصر الأخرى */
        }
    </style>
@endsection

@section('content')
    <div class="card" style="padding-left:0px; padding-right:0px;">

        <div class="card-body">

            {{-- <div class="mb-3"> --}}
            {{-- <button class="btn btn-primary mb-2 btn-s" id="semsterPDFButton" ><i class="fa fa-file-pdf-o"></i> {{__('translate.Report File')}} ملف التقرير</button> --}}
            {{-- <button class="btn btn-primary mb-2 btn-s" onclick="showSemesterPDF()"><i class="fa fa-print"></i> </button> --}}
            {{-- </div> --}}

            <form id="semesterReportAjax" method="POST" action="{{ route('monitor_evaluation.semesterReportPDF') }}"
                enctype="multipart/form-data" target="_blank">
                @csrf
                <div>
                    <input hidden id="test" name="test" value="{{ base64_encode(serialize($pdf)) }}">
                    <input hidden id="semesterText" name="semesterText" value="{{ $semester }}">
                    {{-- <input hidden id="companyTypeText" name="companyTypeText" value="{{$companyType}}">
            <input hidden id="companyCateg" name="companyCateg" value="{{$companyCateg}}"> --}}
                    {{-- <input hidden id="title" name="title" value="{{$title}}"> --}}
                    <button class="btn btn-primary mb-2 btn-s" id="semsterPDFButton" type="submit"><i
                            class="fa fa-print"></i> </button>
                </div>
            </form>

            <!--loading whole page-->
            <div class="loader-container loader-box" id="loaderContainer" hidden>
                <div class="loader-3"></div>
            </div>
            <!--//////////////////-->

            <form id="searchForm" action="" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="row">


                    <div class="col-md-1">
                        <div class="form-group">
                            <label class="col-form-label pt-0"
                                for="exampleInputEmail1">{{ __('translate.Semester') }}{{-- الفصل الدراسي --}}</label>
                            <div class="col-lg-12">
                                <select id="semester" name="semester" class="form-control btn-square">
                                    <option value="1" @if ($semester == 1) selected @endif>
                                        {{ __('translate.First') }}{{-- أول --}}</option>
                                    <option value="2" @if ($semester == 2) selected @endif>
                                        {{ __('translate.Second') }}{{-- ثاني --}}</option>
                                    <option value="3" @if ($semester == 3) selected @endif>
                                        {{ __('translate.Summer') }}{{-- صيفي --}}</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-1">
                        <div class="form-group">
                            <label class="col-form-label pt-0"
                                for="exampleInputEmail1">{{ __('translate.Academic Year') }}{{-- العام الدراسي --}}</label>
                            <div class="col-lg-12">
                                <select id="year" name="year" class="form-control btn-square">
                                    @foreach ($years as $key)
                                        <option value={{ $key }}
                                            @if ($key == $year) selected @endif> {{ $key }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-1">
                        <div class="form-group">
                            <label class="col-form-label pt-0"
                                for="exampleInputEmail1">{{ __('translate.Gender') }}{{-- الجنس --}}</label>
                            <div class="col-lg-12">
                                <select id="gender" name="gender" class="form-control btn-square">
                                    <option value="-1" selected>--{{ __('translate.Choose') }}--</option>
                                    <option value="0">{{ __('translate.Male') }}{{-- ذكر --}}</option>
                                    <option value="1">{{ __('translate.Female') }}{{-- أنثى --}}</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-3">
                        <div class="form-group">
                            <label class="col-form-label pt-0"
                                for="exampleInputEmail1">{{ __('translate.Major') }}{{-- التخصص --}}</label>
                            <div class="col-lg-12">
                                <select id="major" name="major" class="form-control btn-square">
                                    <option value="-1" selected>--{{ __('translate.Choose') }}--</option>
                                    @foreach ($majors as $key)
                                        <option value={{ $key->m_id }}> {{ $key->m_name }} </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-3">
                        <div class="form-group">
                            <label class="col-form-label pt-0"
                                for="exampleInputEmail1">{{ __('translate.Company') }}{{-- الشركة --}}</label>
                            <div class="col-lg-12">
                                <select id="company" name="company" class="form-control btn-square">
                                    <option value="0" selected>--{{ __('translate.Choose') }}--</option>
                                    @foreach ($companies as $key)
                                        <option value={{ $key->c_id }}> {{ $key->c_name }} </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-3">
                        <div class="form-group">
                            <label class="col-form-label pt-0"
                                for="exampleInputEmail1">{{ __('translate.company_branch') }}{{-- فرع الشركة --}}</label>
                            <div class="col-lg-12">
                                <select id="branch" name="branch" class="form-control btn-square">
                                    <option value="0" selected>--{{ __('translate.Choose') }}--</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    {{-- <div class="col-md-2 d-flex justify-content-center">
                    <div class="form-group">
                        <div style="margin-top:27px;" style="width: 100%">
                        <button class="btn btn-info  mb-2 btn-s" style="width: 120px" type="submit"> {{__('translate.View')}} عرض  </button>
                        </div>
                    </div>
                </div> --}}
                </div>
            </form>

            <div id="semsterReportTable">
                <div class="table-responsive">
                    <table class="table table-bordered">
                        <tr>
                            <th id="semsterReportTableTitle" style="background-color: #ecf0ef82;" colspan="3"></th>
                            </th>
                        </tr>
                        <tbody>
                            <tr>
                                <td class="col-md-4">{{ __('translate.Total of registered students this semester') }}
                                    {{--  إجمالي الطلاب المسجلين في المساقات خلال هذا الفصل --}}</td>
                                <td id="manager_summary">{{ $coursesStudentsTotal }}</td>
                                <td><button class="btn btn-primary"
                                        onclick='location.href="{{ route('monitor_evaluation.students_courses_report') }}"'><i
                                            class="fa fa-search"></i></button></td>
                            </tr>
                            <tr>
                                <td class="col-md-4">{{ __('translate.Total of Semester Courses') }} {{-- إجمالي المساقات لهذا الفصل --}}
                                </td>
                                <td id="phone_summary">{{ $semesterCoursesTotal }}</td>
                                <td><button class="btn btn-primary"
                                        onclick='location.href="{{ route('monitor_evaluation.courses_registered_report') }}"'><i
                                            class="fa fa-search"></i></button></td>
                            </tr>
                            <tr id="phone2_summary_area">
                                <td class="col-md-4">
                                    {{ __('translate.Total of Traning Hours for all students this semester') }}
                                    {{-- إجمالي ساعات التدريب لجميع الطلاب خلال هذا الفصل --}}</td>
                                <td id="phone2_summary">
                                    {{ $trainingHoursTotal }}{{-- ساعات --}}{{ __('translate.Hours') }}،{{ $trainingMinutesTotal }}{{-- دقائق --}}
                                    {{ __('translate.Minutes') }} </td>
                                <td><button class="btn btn-primary"
                                        onclick='location.href="{{ route('monitor_evaluation.training_hours_report') }}"'><i
                                            class="fa fa-search"></i></button></td>
                            </tr>
                            <tr>
                                <td class="col-md-4">{{ __("translate.Total of Companies' Trainees this semester") }}
                                    {{-- إجمالي الطلاب المسجلين في الشركات خلال هذاالفصل --}}</td>
                                <td id="address_summary">{{ $traineesTotal }}</td>
                                <td><button class="btn btn-primary"
                                        onclick='location.href="{{ route('monitor_evaluation.students_companies_report') }}"'><i
                                            class="fa fa-search"></i></button></td>
                            </tr>
                            <tr>
                                <td class="col-md-4">
                                    {{ __('translate.Total of Companies have trainees this semester') }}{{-- إجمالي الشركات المسجل بها خلال هذا الفصل --}}
                                </td>
                                <td id="address_summary">{{ $semesterCompaniesTotal }}</td>
                                <td><button class="btn btn-primary"
                                        onclick='location.href="{{ route('monitor_evaluation.companiesReport') }}"'><i
                                            class="fa fa-search"></i></button></td>
                            </tr>
                            <tr>
                                <td class="col-md-4"> الطلاب الغير مسجلين في شركات</td>
                                <td id="address_summary">{{ $company_no_student }}</td>
                                <td><button class="btn btn-primary"
                                        onclick='location.href="{{ route('monitor_evaluation.companiesReport') }}"'><i
                                            class="fa fa-search"></i></button></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>


        </div>




    </div>
@endsection
@section('script')
    <script>
        let companyChanged = false;
        var dataPDF = "<?php echo base64_encode(serialize($pdf)); ?>";

        $('#searchForm').find('select').each(function() {
            element = `${$(this)[0].id}`
            document.getElementById(`${element}`).addEventListener("change", function() {

                if ($(this)[0].id == 'company') {
                    companyChanged = true;
                } else {
                    companyChanged = false;
                }

                data = $('#searchForm').serialize();
                // console.log($(this)[0].id)
                // console.log(data)

                var csrfToken = $('meta[name="csrf-token"]').attr('content');

                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': csrfToken
                    }
                });

                $.ajax({
                    beforeSend: function() {
                        document.getElementById('loaderContainer').hidden = false;
                    },
                    type: 'POST',
                    url: "{{ route('monitor_evaluation.semesterReportAjax') }}",
                    data: data,
                    dataType: 'json',
                    success: function(response) {
                        dataPDF = response.pdf;
                        console.log(response.pdf);
                        document.getElementById('loaderContainer').hidden = true;
                        document.getElementById('semesterText').value = response.semester;
                        document.getElementById('test').value = response.pdf;

                        var selectElement = document.getElementById("branch");
                        // document.getElementById('branch').hidden = true;

                        console.log(response.branches)

                        // while (selectElement.options.length > 0) {
                        //     selectElement.remove(0);
                        // }

                        if (companyChanged) {
                            console.log("hi reem from company change")
                            for (var i = selectElement.options.length - 1; i > 0; i--) {
                                selectElement.remove(i);
                            }

                            if (response.branches != null) {
                                for (var i = 0; i < response.branches.length; i++) {
                                    var option = document.createElement("option");
                                    option.value = response.branches[i].b_id;
                                    option.text = response.branches[i].b_address;
                                    selectElement.appendChild(option);
                                }
                            }

                        }


                        $('#semsterReportTable').html(response.view);

                        var semester = response.semester
                        var year = response.year
                        if (semester == 1) {
                            semester = "{{ __('translate.First Semester Report') }} "
                        } else if (semester == 2) {
                            semester = "{{ __('translate.Second Semester Report') }} "
                        } else {
                            semester = "{{ __('translate.Summer Semester Report') }} "
                        }

                        x = semester + "{{ __('translate.for Academic Year') }} " + year
                        $('#semsterReportTableTitle').html(x);

                    },
                    error: function(xhr, status, error) {
                        console.error(xhr.responseText);
                    }
                });
            })
        })





        function pdfLink(data) {

            //href="{{ route('monitor_evaluation.semesterReportPDF', ['data' => base64_encode(serialize($pdf))]) }}"
            var encodedPdfData = "<?php echo base64_encode(serialize($pdf)); ?>";

            var editLink = "{{ route('monitor_evaluation.semesterReportPDF', ['data' => 'dataArr']) }}";

            //var encodedData = encodeURIComponent(JSON.stringify(pdfData));

            editLink = editLink.replace('dataArr', data);
            //document.getElementById("pdfButton").setAttribute("href",editLink);
            return editLink
        }

        function showSemesterPDF() {
            editLink = pdfLink(dataPDF);
            window.open(editLink, '_blank');
        }

        window.addEventListener("load", (event) => {

            var semester = {!! json_encode($semester, JSON_HEX_APOS) !!}

            if (semester == 1) {
                semester = "{{ __('translate.First Semester Report') }} "
            } else if (semester == 2) {
                semester = "{{ __('translate.Second Semester Report') }} "
            } else {
                semester = "{{ __('translate.Summer Semester Report') }} "
            }
            var year = {!! json_encode($year, JSON_HEX_APOS) !!}

            x = semester + "{{ __('translate.for Academic Year') }} " + year
            $('#semsterReportTableTitle').html(x);
        });

        document.getElementById('searchForm').addEventListener("submit", (e) => {

            e.preventDefault();
            data = $('#searchForm').serialize();
            var csrfToken = $('meta[name="csrf-token"]').attr('content');

            // Send an AJAX request with the CSRF token
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': csrfToken
                }
            });

            //Send an AJAX request
            $.ajax({
                beforeSend: function() {
                    document.getElementById('loaderContainer').hidden = false;
                },
                type: 'POST',
                url: "{{ route('monitor_evaluation.semesterReportAjax') }}",
                data: data,
                dataType: 'json',
                success: function(response) {
                    // console.log(response.pdf);
                    dataPDF = response.pdf;
                    document.getElementById('loaderContainer').hidden = true;
                    $('#semsterReportTable').html(response.view);

                    var semester = response.semester
                    var year = response.year
                    if (semester == 1) {
                        semester = "{{ __('translate.First Semester Report') }} "
                    } else if (semester == 2) {
                        semester = "{{ __('translate.Second Semester Report') }} "
                    } else {
                        semester = "{{ __('translate.Summer Semester Report') }} "
                    }

                    x = semester + "{{ __('translate.for Academic Year') }} " + year
                    $('#semsterReportTableTitle').html(x);

                },
                error: function(xhr, status, error) {
                    console.error(xhr.responseText);
                }
            });

        });
    </script>
@endsection
