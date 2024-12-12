@extends('layouts.app')
@section('title')
    الزيارات
@endsection
@section('header_title')
    الزيارات
@endsection
@section('header_title_link')
    الزيارات
@endsection
@section('header_link')
    <a href="{{ route('supervisors.companies.index') }}">{{__('translate.Training Places')}} {{-- أماكن التدريب --}}</a>
@endsection
@section('style')
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/select2.css') }}">
@endsection
@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <select onchange="list_field_visits()" name="" class="js-example-basic-single form-control" id="company_id">
                                    <option value="">جميع الشركات</option>
                                    @foreach($company as $key)
                                        <option value="{{ $key->c_id }}">{{ $key->c_name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <select onchange="list_field_visits()" name="" class="js-example-basic-single form-control" id="supervisor_id">
                                    <option value="">جميع المشرفين</option>
                                    @foreach($supervisor as $key)
                                        <option value="">{{ $key->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div id="list_field_visits_table" class="col-md-12 table-responsive">

                        </div>
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
            list_field_visits();
        });
        function list_field_visits() {
            $.ajax({
                url: "{{route('admin.field_visits.list_field_visits')}}",
                method: 'post',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                data: {
                    company_id : $('#company_id').val(),
                    supervisor_id : $('#supervisor_id').val()
                },
                success: function(response) {
                    $('#list_field_visits_table').html(response.view);
                },
                error: function() {
                    alert('Error fetching user data.');
                }
            });
        }
    </script>
@endsection
