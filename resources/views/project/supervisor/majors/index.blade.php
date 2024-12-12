@extends('layouts.app')
@section('title')
{{__('translate.Majors')}}{{-- التخصصات --}}
@endsection
@section('header_title')
{{__('translate.Majors')}}{{-- التخصصات --}}
@endsection
@section('header_title_link')
<a href="{{route('admin.users.index')}}">{{__('translate.Users')}}{{-- المستخدمين --}}</a>
@endsection
@section('header_link')
<a href="{{route('admin.users.details' , ['id'=>$user->u_id])}}">{{$user->name}}</a> / {{__('translate.Majors')}}{{-- التخصصات --}}
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
              {{-- <h4 class="card-title mb-0">{{__('translate.Majors')}}التخصصات</h4> --}}
              <div class="card-options"><a class="card-options-collapse" href="#" data-bs-toggle="card-collapse"><i class="fe fe-chevron-up"></i></a><a class="card-options-remove" href="#" data-bs-toggle="card-remove"><i class="fe fe-x"></i></a></div>
            </div>
            <div class="card-body">
            @if (auth()->user()->u_role_id == 1) {{-- Admin --}}
                <div class="row">
                    <div class="col-md-4">
                        <div class="form-group">
                            <button class="btn btn-primary btn-sm" onclick="$('#AddMajorModal').modal('show')" type="button"><span class="fa fa-plus"></span>  {{__('translate.Add major for academic supervisor')}} {{-- إضافة تخصص للمشرف الأكاديمي --}}</button>
                        </div>
                    </div>
                </div>
            @endif
              <div class="row" id="content">
                @include('project.admin.users.ajax.supervisorMajorList')
              </div>
            </div>
          </form>
        </div>
      </div>
    </div>
    @if (auth()->user()->u_role_id == 1) {{-- Admin --}}
        <div class="add_major_supervisor">
            @include('project.admin.users.modals.add_major_supervisor')
        </div>
        @include('project.admin.users.modals.loading')
    @endif
  </div>
@endsection
@section('script')
    <script src="{{ asset('assets/js/select2/select2.full.min.js') }}"></script>
    <script src="{{ asset('assets/js/select2/select2-custom.js') }}"></script>
    <script>
        let AddMajorForm = document.getElementById("addMajorForm");
        AddMajorForm.addEventListener("submit", (e) => {
            e.preventDefault();
            let user_id = document.getElementById('u_id').value;
            let major_id = document.getElementById('select-majors').value;
            $.ajax({
                beforeSend: function(){
                    $('#AddMajorModal').modal('hide');
                    // $('#LoadingModal').modal('show');
                },
                url: "{{route('admin.users.supervisor.major.add')}}",
                method: 'post',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                data: {
                    'user_id' : user_id,
                    'major_id' : major_id
                },
                success: function(response) {
                    var selectMajors = document.getElementById('select-majors');
                    // Remove existing options
                    while (selectMajors.firstChild) {
                        selectMajors.removeChild(selectMajors.firstChild);
                    }
                    // Populate the select with majors
                    response.majors.forEach(function(major) {
                        var option = document.createElement('option');
                        option.value = major.m_id;
                        option.text = major.m_name;
                        selectMajors.appendChild(option);
                    });
                    $('#AddMajorModal').modal('hide');
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
        function delete_major_for_supervisor(ms_id)
        {
            let user_id = document.getElementById('u_id').value;
            $.ajax({
                beforeSend: function(){
                    $('#AddMajorModal').modal('hide');
                    // $('#LoadingModal').modal('show');
                },
                url: "{{route('admin.users.supervisor.major.delete')}}",
                method: 'post',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                data: {
                    'ms_id' : ms_id,
                    'user_id' : user_id
                },
                success: function(response) {
                    var selectMajors = document.getElementById('select-majors');
                    // Remove existing options
                    while (selectMajors.firstChild) {
                        selectMajors.removeChild(selectMajors.firstChild);
                    }
                    // Populate the select with majors
                    response.majors.forEach(function(major) {
                        var option = document.createElement('option');
                        option.value = major.m_id;
                        option.text = major.m_name;
                        selectMajors.appendChild(option);
                    });
                    $('#AddMajorModal').modal('hide');
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
