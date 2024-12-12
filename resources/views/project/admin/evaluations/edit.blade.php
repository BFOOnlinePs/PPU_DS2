@extends('layouts.app')
@section('title')
    تعديل التقييم
@endsection
@section('header_title')
    تعديل التقييم
@endsection
@section('header_title_link')
    تعديل التقييم
@endsection
@section('header_link')
    تعديل التقييم
@endsection
@section('style')
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/select2.css') }}">
@endsection
@section('content')
    <div class="row">
        <div class="col-md-12">
            <form action="{{ route('admin.evaluations.update') }}" method="post">
                @csrf
                <input type="hidden" name="id" value="{{ $data->e_id }}">
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="">عنوان التقييم</label>
                                    <input type="text" value="{{ $data->e_title }}" name="e_title" class="form-control"
                                        placeholder="عنوان التقييم">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="">نوع التقييم</label>
                                    <select class="form-control" required id="" disabled>
                                        <option value="">اختر نوع التقييم ...</option>
                                        @foreach ($evaluation_type as $key)
                                            <option @if ($key->et_id == $data->e_type_id) selected @endif
                                                value="{{ $key->et_id }}">{{ $key->et_type_name }}</option>
                                        @endforeach
                                    </select>
                                    <input type="hidden" name="e_type_id" value="{{ $data->e_type_id }}">

                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="">المستخدم الموجه له التقييم</label>
                                    <select class="form-control" id="" disabled>
                                        @foreach ($roles as $key)
                                            @if ($key->r_name != 'أدمن')
                                                <option @if ($key->r_id == $data->e_evaluator_role_id) selected @endif
                                                    value="{{ $key->r_id }}">{{ $key->r_name }}</option>
                                            @endif
                                        @endforeach
                                    </select>
                                    <input type="hidden" name="e_evaluator_role_id"
                                        value="{{ $data->e_evaluator_role_id }}">

                                </div>
                            </div>
                            {{-- <div class="col-md-6">
                                <div class="form-group">
                                    <label for="">بداية الوقت</label>
                                    <input type="datetime-local" value="{{ $data->e_start_time }}" name="e_start_time" class="form-control" placeholder="عنوان التقييم">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="">نهاية الوقت</label>
                                    <input type="datetime-local" value="{{ $data->e_end_time }}" name="e_end_time" class="form-control" placeholder="عنوان التقييم">
                                </div>
                            </div> --}}
                            {{--                            <div class="col-md-12"> --}}
                            {{--                                <button class="btn btn-primary">تعديل</button> --}}
                            {{--                                @if (empty($evaluation_criteria)) --}}
                            {{--                                    <a href="{{ route('admin.evaluations.evaluation_criteria',['id'=>$data->e_id]) }}" class="btn btn-primary">التالي</a> --}}
                            {{--                                @endif --}}
                            {{--                            </div> --}}
                            <div class="col-md-12 mt-4">
                                <div class="row mb-4">
                                    <div class="col-md-12">
                                        <a class="btn btn-primary" href="{{ route('admin.criteria.index') }}"><span>ادارة المعايير</span></a>
                                        <a class="btn btn-primary" href="{{ route('admin.criteria.add') }}"><span>اضافة معيار</span></a>
                                    </div>
                                </div>
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
                        <div class="col-md-12 mt-3">
                            <button class="btn btn-primary">تعديل</button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection
@section('script')
    <script src="{{ asset('assets/js/select2/select2.full.min.js') }}"></script>
    <script src="{{ asset('assets/js/select2/select2-custom.js') }}"></script>

    <script>
        $(document).ready(function() {
            list_criteria();
            list_evaluation_criteria_table();
        })

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
                error: function(error) {
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
                    "evaluation_id": {{ $data->e_id }}
                },
                success: function(response) {
                    $('#list_evaluation_criteria_table').html(response.view);
                },
                error: function(error) {
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
                    'evaluation_id': {{ $data->e_id }},
                    'criteria_id': criteria_id
                },
                success: function(response) {
                    list_evaluation_criteria_table();
                },
                error: function(error) {
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
                error: function(error) {
                    alert(error);
                }
            })
        }
    </script>
@endsection
