@extends('layouts.app')
@section('title')
    {{ __("translate.Companies' Report") }}{{-- تقرير الشركات --}}
@endsection
@section('header_title')
    {{ __("translate.Companies' Report") }}{{-- تقرير الشركات --}}
@endsection
@section('header_title_link')
    <a href="{{ route('home') }}">{{ __('translate.Main') }}{{-- الرئيسية --}}</a>
@endsection
@section('header_link')
    <a
        href="{{ route('monitor_evaluation.companiesReport') }}">{{ __("translate.Companies' Report") }}{{-- تقرير الشركات --}}</a>
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

            <!--loading whole page-->
            <div class="loader-container loader-box" id="loaderContainer" hidden>
                <div class="loader-3"></div>
            </div>
            <!--//////////////////-->

            <h4 class="text-center" id="companiesReportTitle"></h4>
            <hr>
            {{-- <br> --}}

            {{-- <a href="{{ route('monitor_evaluation.companiesReportPDF', ['data' => base64_encode(serialize($data))]) }}">Go to View 2</a> --}}

            {{-- <div>
            <button class="btn btn-primary mb-2 btn-s" id="semsterPDFButton" onclick="showCompaniesPDF()"><i class="fa fa-file-pdf-o"></i> ملف التقرير </button>
        </div> --}}

            <form id="companiesReportAjax" action="{{ route('monitor_evaluation.companiesReportPDF') }}" method="POST"
                enctype="multipart/form-data" target="_blank">
                @csrf
                <div>
                    <input hidden id="test" name="test" value="{{ base64_encode(serialize($data)) }}">
                    <input hidden id="semesterText" name="semesterText" value="{{ $semester }}">
                    <input hidden id="companyTypeText" name="companyTypeText" value="{{ $companyType }}">
                    <input hidden id="companyCateg" name="companyCateg" value="{{ $companyCateg }}">
                    <input hidden id="title" name="title" value="{{ $title }}">
                    <input hidden id="yearText" name="yearText" value="{{ $year }}">
                    <button class="btn btn-primary mb-2 btn-s" id="semsterPDFButton" type="submit"><i
                            class="fa fa-print"></i> </button>
                </div>
            </form>

            <br>

            <form id="companiesReportSearchForm" action="" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="row">
                    <div class="col-md-4">
                        <div class="form-group">
                            <label class="col-form-label pt-0"
                                for="exampleInputEmail1">{{ __('translate.Semester') }}{{-- الفصل الدراسي --}}</label>
                            {{-- <input class="form-control" id="semester" name="semester"> --}}
                            <div class="col-lg-12">
                                <select id="semester" name="semester" class="form-control btn-square">
                                    <option value="0">{{ __('translate.All Semesters') }}{{-- جميع الفصول  --}}
                                    </option>
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
                    <div class="col-md-4">
                        <div class="form-group">
                            <label class="col-form-label pt-0" for="exampleInputEmail1">
                                {{ __('translate.Company Type') }}{{-- نوع الشركة --}}</label>
                            <div class="col-lg-12">
                                <select id="companyType" name="companyType" class="form-control btn-square">
                                    {{-- <option selected="" disabled="" value="">--اختيار--</option> --}}

                                    <option selected="" value="0">
                                        --{{ __('translate.Choose') }}{{-- اختيار --}}--</option>

                                    <option value="1">{{ __('translate.Public Sector') }}{{-- قطاع عام --}}
                                    </option>
                                    <option value="2">{{ __('translate.Private Sector') }}{{-- قطاع خاص --}}
                                    </option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="form-group">
                            <label class="col-form-label pt-0"
                                for="exampleInputEmail1">{{ __('translate.Company Category') }}{{-- تصنيف الشركة --}}
                            </label>
                            <div class="col-lg-12">
                                <select id="companyCategory" name="companyCategory" class="form-control btn-square">
                                    {{-- <option selected="" disabled="" value="">--اختيار--</option> --}}
                                    <option selected="" value="0">--{{ __('translate.Choose') }}--
                                        {{-- اختيار --}}</option>
                                    @foreach ($categories as $key)
                                        <option value="{{ $key->cc_id }}">{{ $key->cc_name ?? '' }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>

                    {{-- <div class="col-md-2 d-flex justify-content-left">
                    <div class="form-group">
                        <div style="margin-top:50%;" style="width: 100%">
                        <button class="btn btn-danger  mb-2 btn-s" style="width: 120px" type="submit">حذف الفلتر بحث</button>
                        <a href="" style="display: block; text-align: left;"><i class="fa fa-times-circle-o"></i> حذف الفلتر</a>
                        </div>
                    </div>
                </div> --}}


                </div>
            </form>

            <div id="companiesReportTable">
                <div class="table-responsive">
                    <table class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th scope="col" style="display:none;">id</th>
                                <th scope="col">{{ __('translate.Company Name') }} {{-- اسم الشركة --}}</th>
                                <th scope="col">{{ __('translate.Company Manager') }}{{-- مدير الشركة --}}</th>
                                <th scope="col">{{ __('translate.Company Category') }}{{-- تصنيف الشركة --}}</th>
                                <th scope="col">{{ __('translate.Company Type') }}{{-- نوع الشركة --}}</th>

                                <th scope="col">{{ __('translate.Total Students') }}{{-- إجمالي الطلاب --}} </th>
                                <th scope="col">{{ __('translate.display_students') }}{{-- استعراض الطلاب --}}</th>


                            </tr>
                        </thead>
                        <tbody>
                            @if ($data->isEmpty())
                                <tr>
                                    <td colspan="6" class="text-center"><span>{{ __('translate.No available data') }}
                                            {{-- لا توجد بيانات  --}}</span></td>
                                </tr>
                            @else
                                @foreach ($data as $key)
                                    <tr>
                                        <td style="display:none;">{{ $key->c_id }}</td>
                                        <td>{{ $key->c_name }}</td>
                                        <td>{{ $key->manager->name }}</td>
                                        <td>{{ $key->companyCategories->cc_name ?? '' }}</td>
                                        @if ($key->c_type == 1)
                                            <td>{{ __('translate.Public Sector') }}{{-- قطاع عام --}}</td>
                                        @elseif ($key->c_type == 2)
                                            <td>{{ __('translate.Private Sector') }}{{-- قطاع خاص --}}</td>
                                            @else
                                            <td></td>
                                        @endif
                                        <td>
                                            {{ $key->studentsTotal }}
                                        </td>

                                        <td>
                                            {{-- <button class="btn btn-primary" onclick='location.href="{{route("monitor_evaluation.companyStudentsReport")}}"'><i class="fa fa-search"></i></button> --}}
                                            <button class="btn btn-primary"
                                                onclick='location.href="{{ route('monitor_evaluation.companyStudentsReport', ['id' => $key->c_id]) }}"'><i
                                                    class="fa fa-search"></i></button>
                                        </td>
                                    </tr>
                                @endforeach
                            @endif
                        </tbody>
                    </table>
                </div>
            </div>


        </div>




    </div>

@endsection
@section('script')
    <script>
        // var dataPDF = {!! json_encode($data, JSON_HEX_APOS) !!};

        // var dataPDF = "<?php echo base64_encode(serialize($data)); ?>";
        // console.log(dataPDF)

        space = " ";

        function pdfLink(data) {
            var editLink = "{{ route('monitor_evaluation.companiesReportPDF', ['data' => 'dataArr']) }}";

            editLink = editLink.replace('dataArr', data);

            return editLink
        }

        function showCompaniesPDF() {

            editLink = pdfLink(dataPDF);
            window.open(editLink, '_blank');

        }


        window.addEventListener("load", (event) => {

            //console.log({!! json_encode($data, JSON_HEX_APOS) !!})

            semester = {!! json_encode($semester, JSON_HEX_APOS) !!}
            // reportTitle="{{ __('translate.Company Report For') }}" + "{{ __('translate.Semester') }};";
            reportTitle = "";
            if (semester == 1) {
                reportTitle = "{{ __('translate.Company Report for ') }}" +
                    "{{ __('translate.The First Semester') }}";
            } else if (semester == 2) {
                reportTitle = "{{ __('translate.Company Report for ') }}" +
                    "{{ __('translate.The Second Semester') }}";
            } else if (semester == 3) {
                reportTitle = "{{ __('translate.Company Report for ') }}" +
                    "{{ __('translate.The Summer Semester') }}";
            }
            $('#companiesReportTitle').html(reportTitle);

            $('#companiesReportSearchForm').find('select').each(function() {
                element = `${$(this)[0].id}`
                document.getElementById(`${element}`).addEventListener("change", function() {
                    //console.log($(this).value)
                    data = $('#companiesReportSearchForm').serialize();
                    // console.log(data)
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
                        url: "{{ route('monitor_evaluation.companiesReportSearch') }}",
                        data: data,
                        dataType: 'json',
                        success: function(response) {
                            //console.log("all has done")
                            document.getElementById('loaderContainer').hidden = true;
                            semester = document.getElementById('semester').value;
                            document.getElementById('test').value = response.data;
                            document.getElementById('semesterText').value = response
                                .semester;
                            document.getElementById('companyTypeText').value = response
                                .companyType;
                            document.getElementById('companyCateg').value = response
                                .companyCateg;
                            document.getElementById('yearText').value = response.year;
                            // reportTitle="{{ __('translate.Company Report For') }}" + "{{ __('translate.Semester') }}";
                            reportTitle = "";
                            if (semester == 0) {
                                reportTitle =
                                    "{{ __('translate.Company Report for ') }}" +
                                    "{{ __('translate.All Semesters') }}"
                            } else if (semester == 1) {
                                reportTitle =
                                    "{{ __('translate.Company Report for ') }}" +
                                    "{{ __('translate.The First Semester') }}"
                            } else if (semester == 2) {
                                reportTitle =
                                    "{{ __('translate.Company Report for ') }}" +
                                    "{{ __('translate.The Second Semester') }}"
                            } else if (semester == 3) {
                                reportTitle =
                                    "{{ __('translate.Company Report for ') }}" +
                                    "{{ __('translate.The Summer Semester') }}"
                            }

                            $('#companiesReportTitle').html(reportTitle);
                            $('#companiesReportTable').html(response.view);
                        },
                        error: function(xhr, status, error) {
                            console.error(xhr.responseText);
                        }
                    });
                })
            })
        })
    </script>
@endsection
