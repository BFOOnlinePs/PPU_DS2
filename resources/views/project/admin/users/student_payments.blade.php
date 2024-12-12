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
              {{-- <h4 class="card-title mb-0">{{__('translate.Payments')}} الدفعات</h4> --}}
              @include('project.admin.users.includes.payments')
              <div class="card-options"><a class="card-options-collapse" href="#" data-bs-toggle="card-collapse"><i class="fe fe-chevron-up"></i></a><a class="card-options-remove" href="#" data-bs-toggle="card-remove"><i class="fe fe-x"></i></a></div>
            </div>
            <div class="card-body">
              <div class="row">
                    <div class="col-md-4">
                        <div class="form-group">
                            {{-- <button class="btn btn-primary btn-sm custom-btn" onclick="$('#AddPlacesTrainingModal').modal('show')" type="button"><span class="fa fa-plus"></span> تسجيل الطالب في تدريب</button> --}}
                        </div>
                    </div>
              </div>
              <div class="row" id="content">
                {{-- @include('project.admin.users.ajax.studentsAttendanceList') --}}
              </div>
            </div>
          </form>
        </div>
      </div>
    </div>
    {{-- @include('project.admin.users.modals.map_attendance') --}}
  </div>
@endsection
@section('script')

@endsection
