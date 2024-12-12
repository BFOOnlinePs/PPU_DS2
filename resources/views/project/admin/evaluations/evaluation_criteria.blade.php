@extends('layouts.app')
@section('title')
    معايير التقييم
@endsection
@section('header_title')
    معايير التقييم
@endsection
@section('header_title_link')
    معايير التقييم
@endsection
@section('header_link')
    معايير التقييم
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
                        <div class="col-md-12">
                            <div class="row">
                                <div class="col-md-6">
                                    <div>
                                        <h5 class="text-center">المعايير</h5>
                                    </div>
                                    <div class="table-responsive" id="list_criteria_table">

                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div>
                                        <h5 class="text-center">المعايير الخاصة بالتقييم</h5>
                                    </div>
                                    <div class="table-responsive" id="list_evaluation_criteria_table">

                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row mt-3">
                        <div class="col-md-12">
                            <a href="{{ route('admin.evaluations.edit',['id'=>$id]) }}" class="btn btn-primary btn-sm">السابق</a>
                            <a href="{{ route('admin.evaluations.index') }}" class="btn btn-primary btn-sm">صفحة التقييم</a>
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
            list_criteria();
            list_evaluation_criteria_table();
        });
        function list_criteria() {
            $.ajax({
                url: '{{ route('admin.evaluations.list_criteria_ajax') }}',
                datatype: "json",
                type: "post",
                data: {
                    "_token": "{{ csrf_token() }}",
                },
                success: function(response) {
                    $('#list_criteria_table').html(response.view);
                },
                error:function (error) {
                    alert(error);
                }
            })
        }

        function list_evaluation_criteria_table() {
            $.ajax({
                url: '{{ route('admin.evaluations.list_evaluation_criteria_ajax') }}',
                datatype: "json",
                type: "post",
                data: {
                    "_token": "{{ csrf_token() }}",
                    "evaluation_id": {{ $id }}
                },
                success: function(response) {
                    $('#list_evaluation_criteria_table').html(response.view);
                },
                error:function (error) {
                    alert(error);
                }
            })
        }

        function add_evaluation_criteria_ajax(criteria_id) {
            $.ajax({
                url: '{{ route('admin.evaluations.add_evaluation_criteria_ajax') }}',
                datatype: "json",
                type: "post",
                data: {
                    "_token": "{{ csrf_token() }}",
                    'evaluation_id': {{ $id }},
                    'criteria_id': criteria_id
                },
                success: function(response) {
                    list_evaluation_criteria_table();
                },
                error:function (error) {
                    alert(error);
                }
            })
        }

        function delete_evaluation_criteria_ajax(id) {
            $.ajax({
                url: '{{ route('admin.evaluations.delete_evaluation_criteria_ajax') }}',
                datatype: "json",
                type: "post",
                data: {
                    "_token": "{{ csrf_token() }}",
                    'ec_id': id
                },
                success: function(response) {
                    list_evaluation_criteria_table();
                },
                error:function (error) {
                    alert(error);
                }
            })
        }
    </script>
@endsection
