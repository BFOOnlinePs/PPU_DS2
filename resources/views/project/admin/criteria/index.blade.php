@extends('layouts.app')
@section('title')
    المعايير
@endsection
@section('header_title')
    المعايير
@endsection
@section('header_title_link')
    المعايير
@endsection
@section('header_link')
    المعايير
@endsection
@section('style')
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/select2.css') }}">
@endsection
@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    <a href="{{ route('admin.criteria.add') }}" class="btn btn-primary btn-sm">اضافة معيار</a>
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
                            <div class="table-responsive">
                                <table class="table table-sm table-hover">
                                    <thead>
                                        <tr>
                                            <th>المعيار</th>
                                            <th>العلامة</th>
                                            <th>العمليات</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @if($data->isEmpty())
                                            <tr>
                                                <td colspan="3" class="text-center">لا توجد بيانات</td>
                                            </tr>
                                        @else
                                            @foreach($data as $key)
                                                <tr>
                                                    <td>{{ $key->c_criteria_name }}</td>
                                                    <td>{{ $key->c_max_score }}</td>
                                                    <td>
                                                        <a href="{{ route('admin.criteria.edit',['id'=>$key->c_id]) }}" class="btn btn-success btn-xs"><span class="fa fa-edit"></span></a>
                                                        <button class="btn btn-dark btn-xs"><span class="fa fa-search"></span></button>
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
            </div>
        </div>
    </div>
@endsection
@section('script')
    <script src="{{ asset('assets/js/select2/select2.full.min.js') }}"></script>
    <script src="{{ asset('assets/js/select2/select2-custom.js') }}"></script>
@endsection
