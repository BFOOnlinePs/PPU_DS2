@extends('layouts.app')
@section('title')
    طلابي
@endsection
@section('header_title')
    طلابي
@endsection
@section('header_title_link')
    طلابي
@endsection
@section('header_link')
    <a href="{{ route('supervisors.companies.index') }}">{{__('translate.Training Places')}} {{-- أماكن التدريب --}}</a>
@endsection
@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <input type="text" onkeyup="get_student_from_company()" class="form-control" id="student_name" placeholder="اسم الطالب">
                            </div>
                        </div>
                        {{-- <div class="col-md-6">
                            <div class="form-group">
                                <input type="text" onkeyup="get_student_from_company()" class="form-control" id="company_name" placeholder="اسم الشركة">
                            </div>
                        </div> --}}
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    <div class="table-responsive" id="my_students_table">

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
