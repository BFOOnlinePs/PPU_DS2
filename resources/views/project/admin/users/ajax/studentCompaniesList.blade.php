@extends('layouts.app')
@section('title')
{{__('translate.Users')}}{{-- المستخدمين --}}
@endsection
@section('header_title_link')
{{__('translate.Users')}}{{-- المستخدمين --}}
@endsection
@section('header_link')
@endsection
@section('style')
<link rel="stylesheet" type="text/css" href="{{ asset('assets/css/select2.css') }}">
@endsection
@section('content')
    <div class="container-fluid">
        @if ($student_companies->isEmpty())
            <h6 class="alert alert-danger">{{__('translate.No Companies Enrolled In')}}{{--لا يوجد شركات مسجل فيها--}} </h6>
        @else
            <table class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th>{{__('translate.Company Name')}} {{-- اسم الشركة --}}</th>
                        <th>{{__('translate.Branch')}} {{-- الفرع --}}</th>
                        <th>{{__('translate.Section')}} {{-- القسم --}}</th>
                        <th>{{__('translate.Trainer (from the company)')}}{{-- (المدرب (من الشركة --}}</th>
                        <th>{{__('translate.Academic Supervisor Assistant (from the university)')}}{{-- المساعد الإداري من الجامعة --}}</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($student_companies as $student_company)
                        <tr>
                            <td>{{$student_company->company->c_name}}</td>
                            <td>
                                @if (isset($student_company->companyBranch->b_address))
                                    {{$student_company->companyBranch->b_address}}
                                @endif
                            </td>

                            <td>
                                @if (isset($student_company->companyDepartment->d_name))
                                    {{$student_company->companyDepartment->d_name}}
                                @endif
                            </td>
                            <td>
                                @if (isset($student_company->userMentorTrainer->name))
                                    {{$student_company->userMentorTrainer->name}}
                                @endif
                            </td>
                            <td>
                                @if (isset($student_company->userAssistant->name))
                                    {{$student_company->userAssistant->name}}
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @endif
    </div>
@endsection
