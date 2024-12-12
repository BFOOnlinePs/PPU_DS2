@extends('layouts.app')
@section('title')
    احصائيات الحضور والمغادرة
@endsection
@section('header_title')
    احصائيات الحضور والمغادرة
@endsection
@section('header_title_link')
    احصائيات الحضور والمغادرة
@endsection
@section('header_link')

@endsection

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="">اسم الطالب</label>
                                <input class="form-control" type="text" placeholder="اسم الطالب">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="">حالة الدوام</label>
                                <select class="form-control" name="" id="">
                                    <option value="">جميع الحالات</option>
                                    <option value="yes">الطلاب المداومين</option>
                                    <option value="no">الطلاب غير المداومين</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="">من تاريخ</label>
                                <input type="date" class="form-control">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="">الى تاريخ</label>
                                <input type="date" class="form-control">
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
                    <div class="table-responsive" id="list_statistic_attendance_table">

                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('script')
    <script>

        $(document).ready(function(){
            list_statistic_attendance();
        });

        function list_statistic_attendance(){
            var csrfToken = $('meta[name="csrf-token"]').attr('content');
            // Send an AJAX request with the CSRF token
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': csrfToken
                }
            });
            $.ajax({
                type: 'POST',
                url: "{{ route('monitor_evaluation.statistic_attendance.list_statistic_attendance_ajax') }}",
                data: {

                },
                dataType: 'json',
                success: function(response) {
                    $('#list_statistic_attendance_table').html(response.view);
                },
                error: function(xhr, status, error) {
                    console.error(xhr.responseText);
                }
            });
        }

    </script>
@endsection
