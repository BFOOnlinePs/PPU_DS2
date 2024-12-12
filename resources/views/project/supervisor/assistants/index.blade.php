@extends('layouts.app')
@section('title')
{{__("translate.Academic Supervisor's Assistants")}} {{-- المساعدين الإداريين للمشرف الأكاديمي --}}
@endsection
@section('header_title')
{{__("translate.Academic Supervisor's Assistants")}} {{-- المساعدين الإداريين للمشرف الأكاديمي --}}
@endsection
@section('header_title_link')
<a href="{{route('admin.users.index')}}">{{__('translate.Users')}}{{-- المستخدمين --}}</a>
@endsection
@section('header_link')
<a href="{{route('admin.users.details' , ['id'=>$user->u_id])}}">{{$user->name}}</a> / {{__("translate.Academic Supervisor's Assistants")}} {{-- المساعدين الإداريين للمشرف الأكاديمي --}}
@endsection


@section('style')
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/select2.css') }}">
@endsection
@section('content')
<div class="container-fluid">
    @if (auth()->user()->u_role_id == 1) {{-- Admin --}}
        <div class="p-2 pt-0 row">
            @include('project.admin.users.includes.menu_academic_supervisor')
        </div>
    @endif
    <div class="edit-profile">
        <div class="row">
            @if (auth()->user()->u_role_id == 1) {{-- Admin --}}
                <div class="col-xl-3">
                    @include('project.admin.users.includes.information_edit_card_student')
                </div>
                <div class="col-xl-9">
            @elseif(auth()->user()->u_role_id == 3) {{-- Supervisor --}}
                <input type="hidden" value="{{$user->u_id}}" id="u_id">
                <div class="col-xl-12">
            @endif
          <form class="card">
            <div class="card-header pb-0">
              {{-- <h4 class="card-title mb-0">{{__("translate.Academic Supervisor's Assistants")}} المساعدين الإداريين للمشرف الأكاديمي</h4> --}}
              <div class="card-options"><a class="card-options-collapse" href="#" data-bs-toggle="card-collapse"><i class="fe fe-chevron-up"></i></a><a class="card-options-remove" href="#" data-bs-toggle="card-remove"><i class="fe fe-x"></i></a></div>
            </div>
            <div class="card-body">
            @if (auth()->user()->u_role_id == 1) {{-- Admin --}}
                <div class="row">
                    <div class="col-md-4">
                        <div class="form-group">
                            <button class="btn btn-primary btn-sm" onclick="show_modal_add_assistant()" type="button"><span class="fa fa-plus"></span>{{__('translate.Assigne Assistant to the Academic Supervisor')}}{{-- إضافة مساعد إداري للمشرف الأكاديمي --}}</button>
                        </div>
                    </div>
                </div>
            @endif
                <div class="row" id="content">
                    @include('project.supervisor.assistants.ajax.supervisorAssistantList')
                </div>
            </div>
          </form>
        </div>
      </div>
    </div>
    @if (auth()->user()->u_role_id == 1) {{-- Admin --}}
        <div class="add_major_supervisor">
            @include('project.supervisor.assistants.modals.add_supervisor_assistant')
        </div>
        @include('project.admin.users.modals.loading')
        @include('project.supervisor.assistants.includes.alertToConfirmDelete')
    @endif
  </div>
@endsection
@section('script')
    <script src="{{ asset('assets/js/select2/select2.full.min.js') }}"></script>
    <script src="{{ asset('assets/js/select2/select2-custom.js') }}"></script>
    <script>
        function show_modal_add_assistant()
        {
            $('#AddAssistantModal').modal('show');
        }
        let AddAssistantForm = document.getElementById("addAssistantForm");
        AddAssistantForm.addEventListener("submit", (e) => {
            e.preventDefault();
            let supervisor_id = document.getElementById('u_id').value;
            let assistant_id = document.getElementById('select-assistant').value;
            $.ajax({
                beforeSend: function(){
                    $('#AddAssistantModal').modal('hide');
                    $('#LoadingModal').modal('show');
                },
                url: "{{route('supervisors.assistant.create')}}",
                method: 'post',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                data: {
                    'supervisor_id' : supervisor_id,
                    'assistant_id' : assistant_id
                },
                success: function(response) {
                    let selectAssistants = document.getElementById('select-assistant');
                    // Remove existing options
                    while (selectAssistants.firstChild) {
                        selectAssistants.removeChild(selectAssistants.firstChild);
                    }
                    // Populate the select with majors
                    response.assistants.forEach(function(assistant) {
                        var option = document.createElement('option');
                        option.value = assistant.u_id;
                        option.text = assistant.name;
                        selectAssistants.appendChild(option);
                    });
                    $('#AddAssistantModal').modal('hide');
                    $('#content').html(response.html);
                },
                complete: function(){
                    $('#LoadingModal').modal('hide');
                },
                error: function(jqXHR) {
                    alert(jqXHR.responseText);
                    alert('Error fetching user data.');
                }
            });
        });
        function showAlertDelete(sa_id)
        {
            document.getElementById('sa_id').value = sa_id;
            $('#confirmDeleteModal').modal('show');
        }
        function confirmDeleteAssistant()
        {
            let sa_id = document.getElementById('sa_id').value;
            let supervisor_id = document.getElementById('u_id').value;
            $.ajax({
                beforeSend: function(){
                    $('#confirmDeleteModal').modal('hide');
                    $('#LoadingModal').modal('show');
                },
                url: "{{route('supervisors.assistant.delete')}}",
                method: 'post',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                data: {
                    'sa_id' : sa_id,
                    'supervisor_id' : supervisor_id
                },
                success: function(response) {
                    let selectAssistants = document.getElementById('select-assistant');
                    // Remove existing options
                    while (selectAssistants.firstChild) {
                        selectAssistants.removeChild(selectAssistants.firstChild);
                    }
                    // Populate the select with majors
                    response.assistants.forEach(function(assistant) {
                        var option = document.createElement('option');
                        option.value = assistant.u_id;
                        option.text = assistant.name;
                        selectAssistants.appendChild(option);
                    });
                    $('#AddAssistantModal').modal('hide');
                    $('#content').html(response.html);
                },
                complete: function(){
                    $('#LoadingModal').modal('hide');
                },
                error: function(jqXHR) {
                    alert(jqXHR.responseText);
                    alert('Error fetching user data.');
                }
            });
        }
    </script>
@endsection
