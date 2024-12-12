@extends('layouts.app')
@section('title')
{{__('translate.Main')}}{{-- الرئيسية --}}
@endsection
@section('header_title')
{{__('translate.Students')}}{{-- الطلاب --}}
@endsection
@section('header_title_link')
<a href="{{route('home')}}">{{__('translate.Main')}}{{-- الرئيسية --}}</a>
@endsection
@section('header_link')
<a href="{{route('communications_manager_with_companies.students.index')}}">{{__('translate.Students')}}{{-- الطلاب --}}
@endsection
@section('style')
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/select2.css') }}">
@endsection
@section('content')
<div class="container-fluid">
    <div class="edit-profile">
        <div class="row">
        <div class="col-xl-12">
          <form class="card">
            <div class="card-header pb-0">
              <h4 class="card-title mb-0">{{--جميع طلاب الكلية --}} {{__("translate.All College's Students")}} </h4>
              <div class="card-options"><a class="card-options-collapse" href="#" data-bs-toggle="card-collapse"><i class="fe fe-chevron-up"></i></a><a class="card-options-remove" href="#" data-bs-toggle="card-remove"><i class="fe fe-x"></i></a></div>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-8">
                        <input class="form-control mb-2 " onkeyup="user_search(this.value)" type="search" placeholder="{{__('translate.Search')}}" id="search_by_name"> {{-- بحث --}}
                    </div>
                    <div class="col-md-4">
                        <select autofocus class="js-example-basic-single col-sm-12" name="m_id" id="select-major" onchange="user_search(document.getElementById('search_by_name').value)">
                            <option value="{{null}}">{{__('translate.All Majors')}}{{--جميع التخصصات--}}</option>
                            @foreach ($majors as $key)
                                <option value="{{$key->m_id}}">{{$key->m_name}}</option>
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
            let m_id = document.getElementById('select-major').value;
            $.ajax({
                url: "{{route('communications_manager_with_companies.students.search')}}",
                method: 'post',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                data: {
                    'word_to_search' : word_to_search,
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
