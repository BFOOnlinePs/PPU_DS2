@extends('layouts.app')
@section('title')
    التسليم النهائي
@endsection
@section('header_title')
    التسليم النهائي
@endsection
@section('header_title_link')
    <a href="{{ route('home') }}">{{ __('translate.Main') }}{{-- الرئيسية --}}</a>
@endsection
@section('header_link')
    التسليم النهائي
@endsection
@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-body">
                        @if ($setting->ss_report_status == 0)
                            <h3>التسليم النهائي غير متاح</h3>
                        @elseif ($setting->ss_report_status == 1)
                        <form action="{{ route('students.final_reports.create') }}" class="row" method="post"
                        enctype="multipart/form-data">
                        @csrf
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="">اضافة التقرير النهائي
                                    {{-- @if (!empty($registration->final_file))
                                        <a href="{{ asset('public/storage/uploads/'.$registration->final_file) }}" download="{{ $registration->final_file }}"><span class="fa fa-download"></span></a>
                                    @endif --}}
                                </label>
                                <input type="file" class="form-control" name="final_file" multiple>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="">ملاحظات</label>
                                <textarea class="form-control" name="frs_notes" id="" cols="30" rows="4"></textarea>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <button class="btn btn-sm btn-primary">تسليم التقرير</button>
                        </div>
                    </form>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-12">
                                <table class="table table-sm table-hover">
                                    <thead>
                                        <tr>
                                            <th>الاسم الاصلي</th>
                                            <th>الاسم المستعار</th>
                                            <th>ملاحظات</th>
                                            <th>العمليات</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @if ($data->isEmpty())
                                            <tr>
                                                <td colspan="4" class="text-center">لا يوجد تقارير</td>
                                            </tr>
                                        @endif
                                        @foreach ($data as $final_report)
                                            <tr>
                                                <td>
                                                    <a href="{{ asset('public/storage/final_reports/'.$final_report->frs_name) }}" target="_blank">{{ $final_report->frs_name }}</a>
                                                </td>
                                                <td>{{ $final_report->frs_real_name }}</td>
                                                <td>{{ $final_report->frs_notes }}</td>
                                                <td>
                                                    {{-- <a href="{{ asset('public/storage/uploads/'.$final_report->frs_file) }}" target="_blank"><span class="fa fa-download"></span></a> --}}
                                                    <a class="btn btn-xs btn-danger" href="{{ route('students.final_reports.delete',['id'=>$final_report->id]) }}"><span class="fa fa-trash"></span></a>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
