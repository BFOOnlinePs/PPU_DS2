{{-- @extends('layouts.styleForPDF') --}}
{{-- @section('title') --}}
{{-- {{__("translate.Semester's Report")}}تقرير فصل --}}
{{-- @endsection --}}
{{-- @section('style') --}}




<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" dir="{{ app()->isLocale('en') ? 'ltr' : 'rtl' }}">

<head>
    <title>{{ $data['title'] }}</title>
    <style>
        @page {
            header: page-header;
            footer: page-footer;
            background: url("{{ asset('assets/images/report-background.jpg') }}") no-repeat 0 0;
            height: 100vh;
        }
    </style>
    <style>
        table {
            border-collapse: collapse;
            width: 100%;
            font-size: 12px;
        }

        .td {
            border: 1px solid black;
            padding: 8px;
            /* text-align: left; */
        }

        .container {
            display: flex;
        }

        .section {
            flex: 1;
            border: 1px solid #ccc;
            box-sizing: border-box;
            padding: 10px;
        }

        body {
            font-family: 'Arial', 'Helvetica', sans-serif;
            /* background-repeat: no-repeat; */
            /* background-image: url("{{ asset('assets/images/ppu-watermark.png') }}"); */
            /* background-image: url("{{ asset('assets/images/report-background.jpg') }}"); */
            /* background-position: center center; */
            /* background-size: 10px !important; */
            margin: 0;
            padding: 0;
            /* background-image: url("{{ asset('assets/images/report-background.jpg') }}"); Replace 'your-image.jpg' with the path to your image */
            background-image: url("{{ asset('assets/images/background.jpg') }}");
            /* Replace 'your-image.jpg' with the path to your image */
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
            height: 100vh;
            /* Set the height to cover the entire viewport height */
        }
    </style>
</head>

<body>
    {{-- @endsection --}}
    {{-- @section('content') --}}


    <table class="table">

        <tbody>
            <tr style="width:100%">

                <td style="width:1%;">
                    {{-- <img src="{{asset('assets/images/ppu.png')}}" alt="" width="60px" height="65px"> --}}
                </td>
                <td style="width:20%; text-align: right; font-size: 15px;">
                    {{-- {{__('translate.Palestine Polytechnic University')}} --}}
                    <br>
                    {{-- {{__('translate.Dual Studies College')}} --}}
                </td>
                {{-- <td style="width:50%; text-align: center; font-weight: bold;">{{$title}}</td> --}}
                {{-- <td style="width:59%; text-align: center; font-weight: bold;">تقرير الفصل الإجمالي</td> --}}
                <td style="width:59%; text-align: center; font-size: 20px;">{{ $data['title'] }}</td>
                <td style="width:21%; text-align: right; font-size: 14px;">
                    {{ __('translate.report_date') }}{{-- تاريخ التقرير --}} {{ now()->format('Y-m-d') }}
                    <br>
                    {{-- {{$semester}} --}}
                    @if ($semester == 1)
                        <span>{{ __('translate.First Semester') }}{{-- الفصل الدراسي الأول --}}, {{ $year }}</span>
                    @elseif ($semester == 2)
                        <span>{{ __('translate.Second Semester') }}{{-- الفصل الدراسي الثاني --}}, {{ $year }}</span>
                    @elseif ($semester == 3)
                        <span>{{ __('translate.Summer Semester') }}{{-- الفصل الدراسي الصيفي --}}, {{ $year }}</span>
                    @endif
                </td>
                {{-- <td><button class="btn btn-primary"> استعراض</button></td> --}}
            </tr>
        </tbody>
    </table>


    {{-- <hr> --}}
    <br>
    <br>
    <div style="font-size: 15px;">
        <br>

        {{-- <h3>
            {{$title}}
        </h3> --}}

        {{-- <br> --}}
        <div style="width:60%; margin-left: auto; margin-right: auto;">
            <table class="table">
                <tbody>
                    <tr style="background-color: rgba(185, 178, 178, 0.188)">
                        <td class="td"><b>{{ __('translate.Gender') }}{{-- الجنس --}}</b></td>
                        <td class="td">
                            @if ($data['gender'] == 1)
                                <span>{{ __('translate.females') }}{{-- إناث --}}</span>
                            @elseif ($data['gender'] == 0)
                                <span>{{ __('translate.males') }}{{-- ذكور --}}</span>
                            @else
                                <span>{{ __('translate.all') }}{{-- الجميع --}}</span>
                            @endif
                        </td>
                        <td class="td"><b>{{ __('translate.Major') }}{{-- التخصص --}}</b></td>
                        <td class="td">{{ $data['major'] }}</td>
                    </tr>
                    <tr style="background-color: rgba(185, 178, 178, 0.188)">
                        <td class="td"><b>{{ __('translate.company') }}{{-- الشركة --}}</b></td>
                        <td class="td">{{ $data['company'] }}</td>
                        <td class="td"><b>{{ __('translate.Branch') }}{{-- الفرع --}}</b></td>
                        <td class="td">{{ $data['branch'] }}</td>
                    </tr>
                </tbody>
            </table>
        </div>

        <br>
        <div>
            <div>
                <table class="table">

                    <tbody>
                        <tr>
                            <td class="td">{{ __('translate.Total of registered students this semester') }}
                                {{--  إجمالي الطلاب المسجلين في المساقات خلال هذا الفصل --}}</td>
                            <td class="td" id="manager_summary">{{ $data['coursesStudentsTotal'] }}</td>
                        </tr>
                        <tr>
                            <td class="td">{{ __('translate.Total of Semester Courses') }} {{-- إجمالي المساقات لهذا الفصل --}}
                            </td>
                            <td class="td" id="phone_summary">{{ $data['semesterCoursesTotal'] }}</td>
                        </tr>
                        <tr id="phone2_summary_area">
                            <td class="td">
                                {{ __('translate.Total of Traning Hours for all students this semester') }}
                                {{-- إجمالي ساعات التدريب لجميع الطلاب خلال هذا الفصل --}}</td>
                            <td class="td">
                                {{ $data['trainingHoursTotal'] }}{{-- ساعات --}}{{ __('translate.Hours') }}،{{ $data['trainingMinutesTotal'] }}{{-- دقائق --}}
                                {{ __('translate.Minutes') }} </td>
                        </tr>
                        <tr>
                            <td class="td">{{ __("translate.Total of Companies' Trainees this semester") }}
                                {{-- إجمالي الطلاب المسجلين في الشركات خلال هذاالفصل --}}</td>
                            <td class="td">{{ $data['traineesTotal'] }}</td>
                        </tr>
                        <tr>
                            <td class="td">
                                {{ __('translate.Total of Companies have trainees this semester') }}{{-- إجمالي الشركات المسجل بها خلال هذا الفصل --}}
                            </td>
                            <td class="td">{{ $data['semesterCompaniesTotal'] }}</td>
                        </tr>

                    </tbody>
                </table>
            </div>

            {{-- {{$PAGENO}} --}}
        </div>

        <htmlpagefooter name="page-footer">
            {{-- <hr> --}}
            {{-- <div style="display: block;text-align:center; padding: 30px !important;">Page {PAGENO} of {nbpg}</div> --}}
            <div style="display: block;text-align:center; padding: 30px !important;">
                {{ __('translate.page') }}{{-- صفحة --}} {PAGENO}
                {{ __('translate.from') }}{{-- من --}} {nbpg}</div>
        </htmlpagefooter>


        {{-- <footer>
        hi
    </footer> --}}
    </div>
</body>

</html>
{{-- @endsection --}}
