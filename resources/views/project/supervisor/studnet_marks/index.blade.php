@extends('layouts.app')
@section('title')
    علامات الطلاب
@endsection
@section('header_title')
علامات الطلاب
@endsection
@section('header_title_link')
علامات الطلاب
@endsection
@section('header_link')
    <a href="{{ route('supervisors.companies.index') }}">الرئيسية</a>
@endsection
@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="table-responsive">
                                <table class="table table-sm table-hover">
                                    <thead>
                                        <tr>
                                            <th>الاسم</th>
                                            <th>علامة الشركة</th>
                                            <th>علامة المشرف الاكاديمي</th>
                                            <th>العلامة النهائية</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @if ($data->isEmpty())
                                            <tr>
                                                <td colspan="4" class="text-center">لا توجد نتائج</td>
                                            </tr>
                                        @else
                                            @foreach($data as $key)
                                                <tr>
                                                    <td>{{ $key->name }}</td>
                                                    <td>{{ $key->company_marks }}</td>
                                                    <td>{{ $key->training_supervisor_marks }}</td>
                                                    <td></td>
                                                </tr>
                                            @endforeach
                                        @endif
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('script')
    <script>
        $(document).ready(function () {
            get_student_from_company();
        });
        function get_student_from_company() {
            $.ajax({
                url: "{{route('training_supervisor.my_students.list_my_student_ajax')}}",
                method: 'post',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                data: {
                    'student_name' : $('#student_name').val(),
                    'company_name' : $('#company_name').val(),
                },
                success: function(response) {
                    $('#my_students_table').html(response.view);
                },
                error: function (error) {
                    alert(error);
                }
            });
        }
    </script>
@endsection
