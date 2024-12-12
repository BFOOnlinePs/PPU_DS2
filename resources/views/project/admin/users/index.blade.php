@extends('layouts.app')
@section('title')
{{__('translate.Users')}}{{-- المستخدمين --}}
@endsection
@section('header_title')
{{__('translate.Users')}}{{-- المستخدمين --}}
@endsection
@section('header_title_link')
    <a href="{{route('home')}}">{{__('translate.Main')}}{{-- الرئيسية --}}</a>
@endsection
@section('header_link')
    <a href="{{route('admin.users.index')}}">{{__('translate.Users Management')}}{{--إدارة المستخدمين--}}</a>
@endsection
@section('style')
<link rel="stylesheet" type="text/css" href="{{ asset('assets/css/select2.css') }}">
@endsection
@section('navbar')
<div class="row p-2">
    @if (isset($u_role_id))
        <h1 class="text-center" id="r_name">
            @if ($role_name == 'أدمن')
                {{__('translate.Administrator')}} {{-- أدمن --}}
            @elseif($role_name == 'طالب')
                {{__('translate.Student')}} {{-- طالب --}}
            @elseif($role_name == 'رئيس قسم')
                {{__('translate.Academic Supervisor')}} {{-- مشرف أكاديمي --}}
            @elseif($role_name == 'مشرف التدريب العملي')
                مشرف التدريب العملي
            @elseif($role_name == 'مساعد إداري')
                {{__('translate.Academic Supervisor Assistant')}} {{-- مساعد إداري --}}
            @elseif($role_name == 'مسؤول متابعة وتقييم')
                {{__('translate.Monitoring and Evaluation Officer')}} {{-- مسؤول متابعة وتقييم --}}
            @elseif($role_name == 'مدير شركة')
                {{__('translate.Company Manager')}} {{-- مدير شركة --}}
            @elseif($role_name == 'مسؤول تدريب')
                {{__('translate.Training Supervisor')}} {{-- مسؤول تدريب --}}
            @elseif($role_name == 'مسؤول التواصل مع الشركات')
                {{__('translate.Program Coordinator')}} {{-- مسسؤول التواصل مع الشركات --}}
            @endif
        </h1>
    @endif
    <div class="container">
        <div class="container-fluid">
            <div class="col-md-12 row p-2 text-center">
                @foreach ($roles as $role)
                        @if ($role->r_name == 'أدمن')
                            <a class="col-md-1 m-1 p-1 btn btn-dark btn-sm" href="{{route('admin.users.index_id' , ['id'=>$role->r_id])}}" title="{{__('translate.Administrator')}}">
                            {{__('translate.Admin')}} {{-- أدمن --}}
                        @elseif($role->r_name == 'طالب')
                            <a class="col-md-1 m-1 p-1 btn btn-dark btn-sm" href="{{route('admin.users.index_id' , ['id'=>$role->r_id])}}" title="{{__('translate.Student')}}">
                            {{__('translate.Student')}} {{-- طالب --}}
                        @elseif($role->r_name == 'رئيس قسم')
                            <a class="col-md-1 m-1 p-1 btn btn-dark btn-sm" href="{{route('admin.users.index_id' , ['id'=>$role->r_id])}}" title="{{__('translate.Academic Supervisor')}}">
                            {{__('translate.Academic Supervisor')}} {{-- مشرف أكاديمي --}}
                        @elseif($role->r_name == 'مشرف التدريب العملي')
                            <a class="col m-1 p-1 btn btn-dark btn-sm" href="{{route('admin.users.index_id' , ['id'=>$role->r_id])}}" title="مشرف التدريب العملي">
                                مشرف التدريب العملي
                        @elseif($role->r_name == 'مساعد إداري')
                            <a class="col-md-1 m-1 p-1 btn btn-dark btn-sm" href="{{route('admin.users.index_id' , ['id'=>$role->r_id])}}" title="{{__('translate.Academic Supervisor Assistant')}}">
                            {{__('translate.Academic Supervisor Assistant')}} {{-- مساعد إداري --}}
                        @elseif($role->r_name == 'مسؤول متابعة وتقييم')
                            <a class="col m-1 p-1 btn btn-dark btn-sm" href="{{route('admin.users.index_id' , ['id'=>$role->r_id])}}" title="{{__('translate.Monitoring and Evaluation Officer')}}">
                            {{__('translate.M&E')}} {{-- مسؤول متابعة وتقييم --}}
                            @elseif($role->r_name == 'مدير شركة')
                            <a class="col m-1 p-1 btn btn-dark btn-sm" href="{{route('admin.users.index_id' , ['id'=>$role->r_id])}}" title="{{__('translate.Company Manager')}}">
                            {{__('translate.Company Manager')}} {{-- مدير شركة --}}
                        @elseif($role->r_name == 'مسؤول تدريب')
{{--                            <a class="col m-1 p-1 btn btn-primary btn-sm" href="{{route('admin.users.index_id' , ['id'=>$role->r_id])}}" title="{{__('translate.Training Supervisor')}}">--}}
{{--                            {{__('translate.Training Supervisor')}} --}}{{-- مسؤول تدريب --}}
                        @elseif($role->r_name == 'مسؤول التواصل مع الشركات')
                            <a class="col m-1 p-1 btn btn-dark btn-sm" href="{{route('admin.users.index_id' , ['id'=>$role->r_id])}}" title="{{__('translate.Program Coordinator')}}">
                            {{__('translate.Program Coordinator')}} {{-- مسسؤول التواصل مع الشركات --}}
                        @endif
                    </a>
                @endforeach
            </div>
        </div>
    </div>
</div>
@endsection
@section('content')
<div class="col-sm-12" id="main">
    <div class="card">
        <div class="card-body">
            {{-- @if (isset($u_role_id) && $u_role_id != 6) --}}
            @if (isset($u_role_id))
                @if ($role_name == 'أدمن')
                    <input type="hidden" id="u_role_id" value="1">
                    <button class="btn btn-primary  mb-2 btn-s" onclick="$('#AddUserModal').modal('show')" type="button" id="button_add_user" title="{{__('translate.Administrator')}}"><span class="fa fa-plus"></span>
                        {{__('translate.Add')}} {{-- إضافة --}}
                        {{__('translate.admin')}}{{-- أدمن --}}
                @elseif($role_name == 'طالب')
                            <input type="hidden" id="u_role_id" value="2">
                    <button class="btn btn-primary  mb-2 btn-s" onclick="$('#AddUserModal').modal('show')" type="button" id="button_add_user" title="{{__('translate.Student')}}"><span class="fa fa-plus"></span>
                    {{__('translate.Add')}} {{-- إضافة --}}
                    {{__('translate.student')}} {{-- طالب --}}
                @elseif($role_name == 'رئيس قسم')
                            <input type="hidden" id="u_role_id" value="3">
                    <button class="btn btn-primary  mb-2 btn-s" onclick="$('#AddUserModal').modal('show')" type="button" id="button_add_user" title="{{__('translate.Academic Supervisor')}}"><span class="fa fa-plus"></span>
                    {{__('translate.Add')}} {{-- إضافة --}}
                    {{__('translate.academic supervisor')}} {{-- مشرف أكاديمي --}}
                @elseif($role_name == 'مشرف التدريب العملي')
                            <input type="hidden" id="u_role_id" value="4">
                    <button class="btn btn-primary  mb-2 btn-s" onclick="$('#AddUserModal').modal('show')" type="button" id="button_add_user" title="{{__('translate.Academic Supervisor')}}"><span class="fa fa-plus"></span>
                    {{__('translate.Add')}} {{-- إضافة --}}
                    مشرف التدريب العملي
                @elseif($role_name == 'مساعد إداري')
                            <input type="hidden" id="u_role_id" value="5">
                    <button class="btn btn-primary  mb-2 btn-s" onclick="$('#AddUserModal').modal('show')" type="button" id="button_add_user" title="{{__('translate.Academic Supervisor Assistant')}}"><span class="fa fa-plus"></span>
                    {{__('translate.Add')}} {{-- إضافة --}}
                    {{__('translate.academic supervisor assistant')}} {{-- مساعد إداري --}}
                @elseif($role_name == 'مسؤول متابعة وتقييم')
                    <button class="btn btn-primary  mb-2 btn-s" onclick="$('#AddUserModal').modal('show')" type="button" id="button_add_user" title="{{__('translate.Monitoring and Evaluation Officer')}}"><span class="fa fa-plus"></span>
                    {{__('translate.Add')}} {{-- إضافة --}}
                    {{__('translate.M&E')}} {{-- مسؤول متابعة وتقييم --}}
                    @elseif($role_name == 'مدير شركة')
                    <button class="btn btn-primary  mb-2 btn-s" onclick="$('#AddUserModal').modal('show')" type="button" id="button_add_user" title="{{__('translate.Monitoring and Evaluation Officer')}}"><span class="fa fa-plus"></span>
                    {{__('translate.Add')}} {{-- إضافة --}}
                    {{__('translate.Company Manager')}} {{-- مسؤول متابعة وتقييم --}}
                @elseif($role_name == 'مسؤول تدريب')
                    <button class="btn btn-primary  mb-2 btn-s" onclick="$('#AddUserModal').modal('show')" type="button" id="button_add_user" title="{{__('translate.Training Supervisor')}}"><span class="fa fa-plus"></span>
                    {{__('translate.Add')}} {{-- إضافة --}}
                    {{__('translate.Training Supervisor')}} {{-- مسؤول تدريب --}}
                @elseif($role_name == 'مسؤول التواصل مع الشركات')
                    <button class="btn btn-primary  mb-2 btn-s" onclick="$('#AddUserModal').modal('show')" type="button" id="button_add_user" title="{{__('translate.Program Coordinator')}}"><span class="fa fa-plus"></span>
                    {{__('translate.Add')}} {{-- إضافة --}}
                    {{__('translate.Program Coordinator')}} {{-- مسسؤول التواصل مع الشركات --}}
                @endif
                </button>
            @else
                <button class="btn btn-primary  mb-2 btn-s" onclick="$('#AddUserModal').modal('show')" type="button" id="button_add_user" style="display: none"><span class="fa fa-plus"></span></button>
            @endif
            <input class="form-control mb-2 " id="search_input" onkeyup="user_search(this.value)" type="search" placeholder="{{__('translate.Search')}}"> {{-- البحث --}}
            <div id="user-table" class="table-responsive">
                @include('project.admin.users.ajax.usersList')
            </div>
        </div>
    </div>
    @include('project.admin.users.modals.add')
    @include('project.admin.users.modals.loading')
</div>
@endsection
@section('script')
<script src="{{ asset('assets/js/select2/select2.full.min.js') }}"></script>
<script src="{{ asset('assets/js/select2/select2-custom.js') }}"></script>
    <script>
        function close_add_modal()
        {
            document.getElementById('addUserForm').reset();
            document.getElementById('email_duplicate_message').style.display = 'none';
            const errorContainer = document.getElementById('error-container');
            errorContainer.innerHTML = '';
            $('#u_major_id').val(null).trigger('change');
        }
        $(document).ready(function() {
            const table = document.getElementById('users_table');
            if(table === null) {
                document.getElementById('search_input').style.display = 'none';
            }
            else {
                document.getElementById('search_input').style.display = '';
            }
        });
        let username = document.getElementById('u_username');
        let email = document.getElementById('email');
        // username.addEventListener("change" , function() {
        //     email.value = username.value + "@ppu.edu.ps";
        // });
        let AddUserForm = document.getElementById("addUserForm");
        AddUserForm.addEventListener("submit", (e) => {
            e.preventDefault();
            data = $('#addUserForm').serialize();
            $.ajax({
                beforeSend: function(){
                    // $('#AddUserModal').modal('hide');
                    $('#LoadingModal').modal('show');
                },
                url: "{{route('admin.users.create')}}",
                method: 'post',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                data: data,
                success: function(response) {
                    $('#AddUserModal').modal('hide');
                    document.getElementById('addUserForm').reset();
                    $('#user-table').html(response.html);
                    document.getElementById('search_input').style.display = '';
                    const errorContainer = document.getElementById('error-container');
                    errorContainer.innerHTML = ''; // Clear previous errors
                    $('#u_major_id').val(null).trigger('change');
                },
                complete: function(){
                    $('#LoadingModal').modal('hide');
                },
                error: function(xhr, status, error) {
                    const errorContainer = document.getElementById('error-container');
                    errorContainer.innerHTML = ''; // Clear previous errors

                    if (xhr.responseJSON && xhr.responseJSON.errors) {
                        let errors = xhr.responseJSON.errors;

                        Object.values(errors).forEach(errorMessage => {
                            const errorDiv = document.createElement('div');
                            errorDiv.style = 'color: red';
                            errorDiv.textContent = '• ' + errorMessage;
                            errorContainer.appendChild(errorDiv);
                        });
                    } else {
                        const errorDiv = document.createElement('div');
                        errorDiv.textContent = 'Error: ' + error;
                        errorContainer.appendChild(errorDiv);
                    }
                }
            });
        });
        function user_search(data)
        {
            u_role_id = document.getElementById('u_role_id').value;
            $.ajax({
                url: "{{route('admin.users.search')}}",
                method: 'post',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                data: {
                    'data':{
                        'data': data,
                        'u_role_id':u_role_id,
                        'user_role' : '{{ $user_role }}'
                    }
                },
                success: function(response) {
                    $('#user-table').html(response.html);
                },
                error: function() {

                }
            });
        }

        function check_email_not_duplicate()
        {
            let email = document.getElementById('email').value;
            $.ajax({
                url: "{{route('users.add.check_email_not_duplicate')}}",
                method: 'post',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                data: {
                    'email': email
                },
                success: function(response) {
                    let btn = document.getElementById('button_add_user_in_modal');
                    if(response.status === 'true') {
                        btn.setAttribute('disabled', true);
                        document.getElementById('email_duplicate_message').style.display = '';
                    }
                    else {
                        btn.removeAttribute('disabled');
                        document.getElementById('email_duplicate_message').style.display = 'none';
                    }
                },
                error: function(xhr, status, error) {

                }
            });
        }

        function add_training_supervisor(student,supervisor)
        {
            $.ajax({
                url: "{{route('admin.registration.add_training_supervisor')}}",
                method: 'post',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                data: {
                    supervisor : supervisor,
                    student : student,
                },
                success: function(response) {
                    alert('success');
                },
                error: function(xhr, status, error) {

                }
            });
        }

        function add_training_supervisor(student,supervisor)
        {
            $.ajax({
                url: "{{route('admin.registration.add_training_supervisor')}}",
                method: 'post',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                data: {
                    supervisor : supervisor,
                    student : student,
                },
                success: function(response) {

                },
                error: function(xhr, status, error) {

                }
            });
        }

        function change_user_role(u_id,u_role_id)
        {
            $.ajax({
                url: "{{route('users.change_user_role')}}",
                method: 'post',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                data: {
                    u_role_id : u_role_id,
                    u_id : u_id,
                },
                success: function(response) {
                    alert('success');
                },
                error: function(xhr, status, error) {

                }
            });
        }
    </script>
@endsection
