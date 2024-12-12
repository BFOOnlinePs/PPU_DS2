@extends('layouts.app')
@section('title')
    الزيارات
@endsection
@section('header_title')
    الزيارات
@endsection
@section('header_title_link')
    الزيارات
@endsection
@section('header_link')
    <a href="{{ route('supervisors.companies.index') }}">{{ __('translate.Training Places') }} {{-- أماكن التدريب --}}</a>
@endsection
@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    <a href="{{ route('training_supervisor.field_visits.add') }}" class="btn btn-primary btn-sm">اضافة
                        زيارة</a>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    <table class="table table-sm table-hover">
                        <thead>
                            <tr>
                                <th>الطالب</th>
                                <th>المشرف</th>
                                <th>الشركة</th>
                                <th>عنوان الزيارة</th>
                                <th>وقت الزيارة</th>
                                <th>ملاحظات</th>
                                <th>العمليات</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if ($data->isEmpty())
                                <tr>
                                    <td colspan="7" class="text-center">لا توجد مراسلات</td>
                                </tr>
                            @else
                                @foreach ($data as $key)
                                    <tr>
                                        <td>{{ implode(', ', $key->student_names) }}</td>
                                        <td>{{ $key->fv_supervisor_id }}</td>
                                        <td>{{ $key->fv_company_id }}</td>
                                        <td>{{ $key->fv_visiting_place }}</td>
                                        <td>{{ $key->fv_date_by_user }}</td>
                                        <td>{{ $key->fv_notes }}</td>
                                        {{--                                        <td>{{ $key->user->name }}</td> --}}
                                        <td>
                                            <a href="{{ route('training_supervisor.field_visits.details', ['id' => $key->fv_id]) }}"
                                                class="btn btn-primary btn-sm"><span class="fa fa-search"></span></a>
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
@endsection
