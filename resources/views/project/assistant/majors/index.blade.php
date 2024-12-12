@extends('layouts.app')
@section('title')
{{__('translate.Majors')}}{{-- التخصصات  --}}
@endsection
@section('header_title')
{{__('translate.Majors')}}{{-- التخصصات  --}}
@endsection
@section('header_title_link')
<a href="{{route('home')}}">{{__('translate.Main')}}{{-- الرئيسية --}}</a>
@endsection
@section('header_link')
<a href="{{ route('supervisor_assistants.majors.index' , ['id' => auth()->user()->u_id])}}">{{__('translate.Majors')}}{{-- التخصصات  --}}</a>
@endsection

@section('style')
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/select2.css') }}">
@endsection
@section('content')
<div class="container-fluid">
    <div class="edit-profile">
        <div class="row">
            <input type="hidden" value="{{$user->u_id}}" id="u_id">
            <div class="col-xl-12">
            <form class="card">
            <div class="card-header pb-0">
                <h4 class="card-title mb-0">{{__('translate.Majors')}}{{-- التخصصات --}}</h4>
                <div class="card-options"><a class="card-options-collapse" href="#" data-bs-toggle="card-collapse"><i class="fe fe-chevron-up"></i></a><a class="card-options-remove" href="#" data-bs-toggle="card-remove"><i class="fe fe-x"></i></a></div>
            </div>
            <div class="card-body">
                <div class="row" id="content">
                    @include('project.assistant.majors.tables.supervisorAssistantMajorList')
                </div>
            </div>
            </form>
        </div>
    </div>
    </div>
    </div>
@endsection
@section('script')
    {{-- <script src="{{ asset('assets/js/select2/select2.full.min.js') }}"></script>
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
    </script> --}}
@endsection
