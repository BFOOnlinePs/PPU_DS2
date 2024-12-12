

@extends('layouts.app')
@section('title')
    {{__("translate.Academic Supervisor's Students")}}{{-- طلاب المشرف --}}
@endsection
@section('header_title')
    {{__("translate.Academic Supervisor's Students")}}{{-- طلاب المشرف --}}
@endsection
@section('header_title_link')
    <a href="{{route('admin.users.index')}}">{{__('translate.Users')}}{{-- المستخدمين --}}</a>
@endsection
@section('header_link')
@endsection


@section('style')
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/select2.css') }}">
@endsection
@section('content')
    <div class="card">
        <div class="card-body">
            <div class="row">
                <div class="col-md-12">
                    <div class="row">
                        <div class="col-md-6">
                            <input onkeyup="students_waiting_to_approve_cv()" type="text" class="form-control" id="input_search" placeholder="{{ __('translate.search') }}">
                        </div>
                        <div class="col-md-6">
                            <select onchange="students_waiting_to_approve_cv()" class="js-example-basic-single" name="" id="company_id">
                                <option value="">جميع الشركات</option>
                                @foreach($company as $key)
                                    <option value="{{ $key->c_id }}">{{ $key->c_name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="mt-2" id="students_waiting_to_approve_cv_table">

                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('script')
            <script src="{{ asset('assets/js/select2/select2.full.min.js') }}"></script>
            <script src="{{ asset('assets/js/select2/select2-custom.js') }}"></script>
            <script>
                $(document).ready(function () {
                    students_waiting_to_approve_cv();
                });

                function students_waiting_to_approve_cv()
                {
                    $.ajax({
                        url: "{{route('users.students_waiting_to_approve_cv')}}",
                        method: 'post',
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        data: {
                            'input_search' : $('#input_search').val(),
                            'company_id' : $('#company_id').val()
                        },
                        success: function(response) {
                            $('#students_waiting_to_approve_cv_table').html(response.view);
                        },
                        error: function() {
                            alert('Error fetching user data.');
                        }
                    });
                }

                function change_status_from_cv(id,status)
                {
                    $.ajax({
                        url: "{{route('users.change_status_from_cv')}}",
                        method: 'post',
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        data: {
                            'id' : id,
                            'status' : status
                        },
                        success: function(response) {
                            // alert('success');
                            students_waiting_to_approve_cv();
                            // $('#students_waiting_to_approve_cv_table').html(response.view);
                        },
                        error: function() {
                            alert('Error fetching user data.');
                        }
                    });
                }
            </script>
@endsection
