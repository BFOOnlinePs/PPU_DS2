@extends('layouts.app')
@section('title')
{{__('translate.Students')}}{{-- الطلاب  --}}
@endsection
@section('header_title')
{{__('translate.Students')}}{{-- الطلاب  --}}
@endsection
@section('header_title_link')
<a href="{{ route('supervisor_assistants.majors.index' , ['id' => auth()->user()->u_id])}}">{{__('translate.Majors')}}{{-- التخصصات  --}}</a>
@endsection
@section('header_link')
<a href="{{route('supervisor_assistants.students.index' , ['ms_major_id' => null])}}">{{__('translate.Students')}}{{-- الطلاب  --}}</a>
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
                <h4 class="card-title mb-0">{{__('translate.Students')}}{{-- الطلاب --}}</h4>
                <div class="card-options"><a class="card-options-collapse" href="#" data-bs-toggle="card-collapse"><i class="fe fe-chevron-up"></i></a><a class="card-options-remove" href="#" data-bs-toggle="card-remove"><i class="fe fe-x"></i></a></div>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-8">
                        <input class="form-control mb-2 " onkeyup="user_search()" type="search" placeholder="{{__('translate.Search')}}" id="search_id"> {{-- بحث --}}
                    </div>
                    <div class="col-md-4">
                        <select autofocus class="js-example-basic-single col-sm-12" name="m_id" onchange="user_search()" id="select-major">
                            @if (isset($major))
                                <option value="{{$major->m_id}}">{{$major->m_name}}</option>
                            @endif
                            <option value="{{null}}">{{__('translate.All Majors')}}{{--جميع التخصصات--}}</option>
                            @foreach ($majors as $key)
                                <option value="{{$key->m_id}}">{{$key->m_name}}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="row" id="content">
                    @include('project.assistant.students.ajax.studentsList')
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
        function user_search()
        {
            let word_to_search = document.getElementById('search_id').value;
            let m_id = document.getElementById('select-major').value;
            $.ajax({
                url: "{{route('supervisor_assistants.students.search')}}",
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

                }
            });
        }
    </script>
@endsection
