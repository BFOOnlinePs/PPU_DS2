@extends('layouts.app')
@section('title')
{{__("translate.User's Information")}}{{-- معلومات المستخدم --}}
@endsection
@section('header_title')
{{__("translate.User's Information")}}{{-- معلومات المستخدم --}}
@endsection
@section('header_title_link')
<a href="{{route('admin.users.index')}}">{{__('translate.Users')}}{{-- المستخدمين --}}</a>
@endsection
@section('header_link')
{{__('translate.Edit User Information')}}{{--تعديل معلومات المستخدم--}} / {{$user->name}}
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
        @if(Auth::user()->u_role_id == 3)
            @include('project.admin.users.includes.menu_academic_supervisor')
        @elseif(Auth::user()->u_role_id == 10)
            <a class=" col m-1 btn btn-primary btn-sm" href="{{route('admin.users.students.attendance' , ['id'=>$user->u_id])}}">
                <h1 style="font-size: 25px; " class="fa fa-check-square" ></h1>
                <br>
            {{__('translate.Attendance Log')}} {{-- سجل المتابعة --}}</a>
            <a class="col m-1  btn btn-primary btn-sm" href="{{route('admin.users.student.payments' , ['id'=>$user->u_id])}}">
                <h1 style="font-size: 25px; " class="fa fa-dollar" ></h1>
                <br>
            {{__('translate.Payments')}} {{-- الدفعات --}}</a>
            @elseif ($user->u_role_id == 2)
            @include('project.admin.users.includes.menu_student')
        @endif
    </div>
    <div class="edit-profile">
      <div class="row">
        @if ($user->u_role_id == 1)
            <div class="col-xl-12">
            @include('project.admin.users.includes.information_edit_card_admin')
        @else
            <div class="col-xl-3">
            @include('project.admin.users.includes.information_edit_card_student')
        @endif
        </div>
        @if ($user->u_role_id != 1) {{-- Admin doesn't have this part of page --}}
            <div class="col-xl-9">
            <form class="card">
                <div class="card-header pb-0">
                    @if($user->u_role_id == 6)
                        <input type="hidden" value="{{$user->u_id}}" id="user_id">
                        <h4 class="card-title mb-0">
                            @if(app()->getLocale() == 'en')
                                {{$company->c_name}} {{__("translate.Interns at")}}
                            @else
                                {{__("translate.Interns at")}} {{$company->c_name}}
                            @endif
                        </h4>
                        @if (isset($students))
                            <br>
                            <div class="row">
                                <div class="col-md-12">
                                    <input class="form-control mb-2 " onkeyup="user_search(this.value)" type="search" placeholder="{{__('translate.Search')}}"> {{-- بحث --}}
                                </div>
                            </div>
                            <div id="user-table">
                                @include('project.admin.users.includes.student')
                            </div>
                        @else
                            <span class="text-center">{{__('translate.No Trainee Students in this Company')}}{{--لا يوجد متدربين في هذه الشركة--}}</span>
                        @endif
                    @elseif($user->u_role_id == 4)
                        <h5>{{__("translate.Assistant's Supervisors")}}{{--مشرفين المساعد الإداري--}}</h5>
                        <br>
                        <button class="btn btn-primary  mb-2 btn-s" type="button" onclick="show_AddSupervisorModal({{$user->u_id}})"><span class="fa fa-plus"></span>{{__('translate.Assigne Supervisor to the Assistant')}} {{--تسجيل مشرف أكاديمي للمساعد الإداري--}}</button>
                        {{-- @include('project.admin.users.modals.add_supervisor') --}}
                    @endif
                    <div class="card-options"><a class="card-options-collapse" href="#" data-bs-toggle="card-collapse"><i class="fe fe-chevron-up"></i></a><a class="card-options-remove" href="#" data-bs-toggle="card-remove"><i class="fe fe-x"></i></a></div>
                </div>
                <div class="card-body pt-0">
                    <div class="row">
                        @if($user->u_role_id == 4)
                            <div id="assistantList">
                                @include('project.admin.users.includes.assistantList')
                            </div>
                        @endif
                        @if($user->u_role_id == 3)
                            <div class="table-responsive">
                                <table class="table table-sm table-striped">
                                    <tbody>
                                        @foreach ($student_for_supervisor as $key)
                                            <tr>
                                                <td><a href="{{ route('admin.users.details' , ['id'=>$key->u_id]) }}">{{$key->name}}</a></td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @endif
                    </div>
                {{-- <hr> --}}
                </div>
            </form>
            @if($user->u_role_id == 4)
                @include('project.admin.users.modals.add_supervisor')
                @include('project.admin.users.modals.alertToConfirmDeleteSupervisor')

            @endif
            @include('layouts.loader')
            </div>
        @endif
    </div>
    </div>
  </div>
@endsection
@section('script')
    <script src="{{ asset('assets/js/select2/select2.full.min.js') }}"></script>
    <script src="{{ asset('assets/js/select2/select2-custom.js') }}"></script>
    <script>
        function deleteSupervisor()
        {
            let sa_id = document.getElementById('sa_id').value;
            $.ajax({
                beforeSend: function() {
                    $('#confirmDeleteSupervisorModal').modal('hide');
                    $('#LoadingModal').modal('show');
                },
                url: "{{ route('admin.assistant.deleteSupervisor') }}",
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                data: {
                    'sa_id' : sa_id
                },
                success: function (response) {
                    let selectSupervisors = document.getElementById('select-supervisors');
                    // Remove existing options
                    while (selectSupervisors.firstChild) {
                        selectSupervisors.removeChild(selectSupervisors.firstChild);
                    }
                    // Populate the select with Supervisors
                    response.supervisors.forEach(function(supervisor) {
                        var option = document.createElement('option');
                        option.value = supervisor.u_id;
                        option.text = supervisor.name;
                        selectSupervisors.appendChild(option);
                    });
                    $('#LoadingModal').modal('hide');
                    $('#assistantList').html(response.html);
                },
                error: function (error) {
                    $('#LoadingModal').modal('hide');
                }
            });
        }
        function confirm_delete_supervisor(value)
        {
            document.getElementById('sa_id').value = value;
            $('#confirmDeleteSupervisorModal').modal('show');
        }
        function user_search(value) {
            user_id = document.getElementById('user_id').value;
            $.ajax({
                url: "{{route('users.company_manager.searchStudentByName')}}",
                method: 'post',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                data: {
                    'user_id' : user_id,
                    'value' : value
                },
                success: function(response) {
                    if(response.html !== '') {
                        $('#user-table').html(response.html);
                    }
                },
                error: function(error) {

                    // alert(error.responseText);
                }
            });
        }
        function show_AddSupervisorModal(id)
        {
            $.ajax({
                url: "{{ route('admin.assistant.showSelectSupervisor') }}",
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                data: {
                    'assistant_id' : id
                },
                success: function (response) {
                    let selectSupervisors = document.getElementById('select-supervisors');
                    // Remove existing options
                    while (selectSupervisors.firstChild) {
                        selectSupervisors.removeChild(selectSupervisors.firstChild);
                    }
                    // Populate the select with Supervisors
                    response.supervisors.forEach(function(supervisor) {
                        var option = document.createElement('option');
                        option.value = supervisor.u_id;
                        option.text = supervisor.name;
                        selectSupervisors.appendChild(option);
                    });
                    $('#AddSupervisorModal').modal('show');
                },
                error: function (error) {
                }
            });
        }
        function add_supervisor(assistant_id)
        {
            let supervisor_id = document.getElementById('select-supervisors').value;
            $.ajax({
                beforeSend: function() {
                    $('#AddSupervisorModal').modal('hide');
                    $('#LoadingModal').modal('show');
                },
                url: "{{ route('admin.assistant.create') }}",
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                data: {
                    'supervisor_id' : supervisor_id,
                    'assistant_id' : assistant_id
                },
                success: function (response) {
                    let selectSupervisors = document.getElementById('select-supervisors');
                    // Remove existing options
                    while (selectSupervisors.firstChild) {
                        selectSupervisors.removeChild(selectSupervisors.firstChild);
                    }
                    // Populate the select with Supervisors
                    response.supervisors.forEach(function(supervisor) {
                        var option = document.createElement('option');
                        option.value = supervisor.u_id;
                        option.text = supervisor.name;
                        selectSupervisors.appendChild(option);
                    });
                    $('#LoadingModal').modal('hide');
                    $('#assistantList').html(response.html);
                },
                error: function (error) {
                    $('#LoadingModal').modal('hide');
                }
            });
        }
    </script>
@endsection
