@extends('layouts.app')
@section('title')
{{__('translate.Users')}}{{-- المستخدمين --}}
@endsection
@section('header_title_link')
{{__('translate.Users')}}{{-- المستخدمين --}}
@endsection
@section('header_link')
{{__('translate.Edit User Information')}}{{--تعديل المستخدم--}} / <a href="{{route('admin.users.details' , ['id'=>$user->u_id])}}">{{$user->name}}</a>
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
              <h4 class="card-title mb-0">{{__("translate.Academic Supervisor's Students")}} {{-- طلاب المشرف --}}</h4>
              <div class="card-options"><a class="card-options-collapse" href="#" data-bs-toggle="card-collapse"><i class="fe fe-chevron-up"></i></a><a class="card-options-remove" href="#" data-bs-toggle="card-remove"><i class="fe fe-x"></i></a></div>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-8">
                        <input class="form-control mb-2 " onkeyup="user_search(this.value)" type="search" placeholder="{{__('translate.Search')}}"> {{-- بحث --}}
                    </div>
                    <div class="col-md-4">
                        <select autofocus class="js-example-basic-single col-sm-12" name="m_id" id="select-major" onchange="select_major(this.value)">
                            <option value="{{null}}">{{__('translate.All Majors')}}{{--جميع التخصصات--}}</option>
                            @foreach ($majors as $major)
                                <option value="{{$major->m_id}}">{{$major->m_name}}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
              <div class="row">
                    <div class="col-md-4">
                        <div class="form-group">
                        </div>
                    </div>
              </div>
              <div class="row" id="content">
                @include('project.admin.users.ajax.supervisorStudentsList')
              </div>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
@endsection
@section('script')
    <script src="{{ asset('assets/js/select2/select2.full.min.js') }}"></script>
    <script src="{{ asset('assets/js/select2/select2-custom.js') }}"></script>
    <script>
        function user_search(word_to_search)
        {
            let u_id = document.getElementById('u_id').value;
            let m_id = document.getElementById('select-major').value;
            $.ajax({
                url: "{{route('admin.users.supervisor.students.search')}}",
                method: 'post',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                data: {
                    'word_to_search' : word_to_search,
                    'user_id' : u_id,
                    'm_id' : m_id
                },
                success: function(response) {
                    $('#content').html(response.html);
                },
                error: function() {
                    alert('Error fetching user data.');
                }
            });
        }
        function select_major(m_id)
        {
            let u_id = document.getElementById('u_id').value;
            $.ajax({
                url: "{{route('admin.users.supervisor.students.search.major')}}",
                method: 'post',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                data: {
                    'user_id' : u_id,
                    'm_id' : m_id
                },
                success: function(response) {
                    $('#content').html(response.html);
                },
                error: function() {
                    alert('Error fetching user data.');
                }
            });
        }
    </script>
@endsection
