@extends('layouts.app')
@section('title')
    التقييمات
@endsection
@section('header_title')
    التقييمات
@endsection
@section('header_title_link')
    التقييمات
@endsection
@section('header_link')
    التقييمات
@endsection
@section('style')
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/select2.css') }}">
@endsection
@section('content')
    <input type="hidden" id="evaluation_id">
    {{-- <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    <a href="{{ route('admin.evaluations.add') }}" class="btn btn-primary btn-sm">اضافة تقييم</a>
                </div>
            </div>
        </div>
    </div> --}}
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-sm table-hover">
                            <thead>
                                <tr>
                                    <th>نوع التقييم</th>
                                    <th>العنوان</th>
                                    <th>الحالة</th>
                                    <th>صلاحية الشخص</th>
                                    {{-- <th>بداية الوقت</th>
                                    <th>نهاية الوقت</th> --}}
                                    <th>العمليات</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if ($data->isEmpty())
                                    <tr>
                                        <td colspan="7" class="text-center">لا توجد بيانات</td>
                                    </tr>
                                @else
                                    @foreach ($data as $key)
                                        <tr>
                                            <td>{{ $key->evaluation_type->et_type_name ?? '' }}</td>
                                            <td>{{ $key->e_title }}</td>
                                            <td>
                                                <div class="media-body switch-sm icon-state">
                                                    <label class="switch">
                                                        <input type="checkbox"
                                                            onchange="update_status({{ $key->e_id }} , this.checked)"
                                                            @if ($key->e_status == 1) checked="" @endif>
                                                        <span class="switch-state"></span>
                                                    </label>
                                                </div>
                                            </td>
                                            <td>{{ $key->role->r_name }}</td>
                                            {{-- <td>{{ $key->e_start_time }}</td>
                                            <td>{{ $key->e_end_time }}</td> --}}
                                            <td>
                                                <a href="{{ route('admin.evaluations.edit', ['id' => $key->e_id]) }}"
                                                    class="btn btn-xs btn-primary"><span class="fa fa-edit"></span></a>
                                                {{--                                                <button class="btn btn-xs btn-dark" onclick="open_modal({{ $key->e_id }})"><span class="fa fa-search"></span></button> --}}
                                                <a href="{{ route('admin.evaluations.details', ['evaluation_id' => $key->e_id]) }}"
                                                    class="btn btn-xs btn-info"><span class="fa fa-search"></span></a>
                                            </td>
                                        </tr>
                                    @endforeach
                                @endif
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            @include('project.admin.evaluations.modals.add_criteria')

        </div>
    </div>
@endsection
@section('script')
    <script src="{{ asset('assets/js/select2/select2.full.min.js') }}"></script>
    <script src="{{ asset('assets/js/select2/select2-custom.js') }}"></script>

    <script>
        function open_modal(evaluation_id) {
            $('#add_criteria_modal').modal('show');
            $('#evaluation_id').val(evaluation_id);
            list_criteria();
            list_evaluation_criteria_table();
        }

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
                    "evaluation_id": $('#evaluation_id').val()
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
                    'evaluation_id': $('#evaluation_id').val(),
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

        function update_status(id, value) {
            $.ajax({
                url: '{{ route('students.evaluation.update_status') }}',
                datatype: "json",
                type: "post",
                data: {
                    "_token": "{{ csrf_token() }}",
                    'id': id,
                    'status': value ? 1 : 0
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
