@extends('layouts.app')
@section('title')
    {{__('translate.Payments')}} {{-- الدفعات --}}
@endsection
@section('header_title')
    {{__('translate.Payments')}} {{-- الدفعات --}}
@endsection
@section('header_title_link')
    <a href="{{route('admin.users.index')}}">{{__('translate.Users')}}{{-- المستخدمين --}}</a>
@endsection
@section('header_link')
    <a href="{{route('admin.users.details' , ['id'=>$user->u_id])}}">{{$user->name}}</a> / {{__('translate.Payments')}} {{-- الدفعات --}}
@endsection


@section('style')
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/select2.css') }}">
@endsection
@section('content')
    <div class="container-fluid">
        <div class="page-header pb-1">
            <div class="row">
                <div class="col-sm-6">
                </div>
                <div class="col-sm-6">
                </div>
            </div>
        </div>
    </div>
    <div class="container-fluid">
        <div class="p-2 pt-0 row">
            @include('project.admin.users.includes.menu_student')
        </div>
        <div class="edit-profile">
            <div class="row">
                <div class="col-xl-3">
                    @include('project.admin.users.includes.information_edit_card_student')
                </div>
                <div class="col-xl-9">
                    <form class="card">
                        <div class="card-header pb-0">
                            <div class="card-options"><a class="card-options-collapse" href="#" data-bs-toggle="card-collapse"><i class="fe fe-chevron-up"></i></a><a class="card-options-remove" href="#" data-bs-toggle="card-remove"><i class="fe fe-x"></i></a></div>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-12 mb-3">
                                    <button type="button" onclick="AddStudentAttachmentModal()" class="btn btn-primary">{{ __('translate.add_attachment') }}</button>
                                </div>
                                <div class="col-md-12">
                                    <div class="table-responsive">
                                        <table class="table table-bordered table-hover table-sm">
                                            <thead>
                                                <tr>
                                                    <th>{{ __('translate.file') }}</th>
                                                    <th>{{ __('translate.Notes') }}</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @if($data->isEmpty())
                                                    <tr>
                                                        <td colspan="1" class="text-center">لا توجد بيانات</td>
                                                    </tr>
                                                @else
                                                    @foreach($data as $key)
                                                        <tr>
                                                            <td>
                                                                <a target="_blank" href="{{ asset('public/storage/students/'.$key->file) }}">{{ $key->file }}</a>
                                                            </td>
                                                            <td>{{ $key->note }}</td>
                                                        </tr>
                                                    @endforeach
                                                @endif
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
         @include('project.admin.users.modals.add_student_attachment_modal')
    </div>
@endsection
@section('script')
    <script>
        function AddStudentAttachmentModal() {
            $('#AddStudentAttachmentModal').modal('show');
        }
    </script>
@endsection
