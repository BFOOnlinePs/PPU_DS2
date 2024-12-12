@extends('layouts.app')
@section('title')
{{__("translate.Current Semester's Students")}}{{-- طلاب الفصل الحالي --}}
@endsection
@section('header_title')
{{__("translate.Current Semester's Students")}}{{-- طلاب الفصل الحالي --}}
@endsection
@section('header_title_link')
<a href="{{route('admin.registration.index')}}"> {{__('translate.Registration')}}{{-- التسجيل --}}</a>
@endsection
@section('header_link')
<a href="{{route('admin.registration.semesterStudents')}}"> {{__("translate.Current Semester's Students")}}{{-- طلاب الفصل الحالي --}}</a>
@endsection

@section('style')

@endsection

@section('content')


<div>
    <button class="btn btn-primary  mb-2 btn-s" type="button" onclick='location.href="{{route("admin.registration.index")}}"'><span class="fa fa-book"></span>{{__('translate.Current Semester Courses')}}{{-- التدريبات العملية للفصل الحالي --}}</button>
    {{-- <button class="btn btn-primary  mb-2 btn-s" type="button" onclick='location.href="{{route("admin.companies_categories.index")}}"'><span class="fa fa-users"></span> طلاب الفصل الحالي</button> --}}
</div>

<div class="card" style="padding-left:0px; padding-right:0px;">

    <div class="card-body" >
        <div class="row">
            <div class="col-md-6">
                <input type="search" class="form-control mb-1" placeholder="{{__('translate.Search')}}" aria-label="Search" id="user_name" onkeyup="filter()"/> {{-- بحث --}}
            </div>
            <div class="col-md-2 mb-2">
                <select autofocus class="js-example-basic-single col-sm-2" id="user_major" onchange="filter()">
                    <option value="">جميع التخصصات</option>
                    @foreach ($majors as $major)
                        <option value="{{$major->m_id}}">{{$major->m_name}}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2">
                <select autofocus class="js-example-basic-single col-sm-2" id="user_course" onchange="filter()">
                    <option value="">جميع التدريبات العملية</option>
                    @foreach ($semester_courses as $semester_course)
                        <option value="{{$semester_course->sc_course_id}}">{{$semester_course->courses->c_name}}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2">
                <select autofocus class="js-example-basic-single col-sm-2" id="user_gender" onchange="filter()">
                    <option value="">الكل</option>
                    <option value="0">ذكر</option>
                    <option value="1">أنثى</option>
                </select>
            </div>

        </div>

        <div id="showTable">
            <div class="table-responsive" id="semester_students">

            </div>
        </div>
    </div>
</div>
@endsection
@section('script')
    <script>
        $(document).ready(function () {
            filter();
        });
        function filter() {
            let user_name = document.getElementById('user_name').value;
            let user_major = document.getElementById('user_major').value;
            let user_course = document.getElementById('user_course').value;
            let user_gender = document.getElementById('user_gender').value;
            $.ajax({
                url: "{{ route('admin.registration.filterSemesterStudents') }}",
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                data: {
                    'user_name' : user_name ,
                    'user_major' : user_major ,
                    'user_course' : user_course ,
                    'user_gender' : user_gender
                } ,
                success: function (response) {
                    $('#semester_students').html(response.html);
                },
                error: function (error) {
                }
            });
        }

        function add_training_supervisor(student,supervisor)
        {
            $.ajax({
                url: "{{route('admin.registration.add_training_supervisor')}}",
                method: 'post',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                data: {
                    supervisor : supervisor,
                    student : student,
                },
                success: function(response) {

                },
                error: function(xhr, status, error) {

                }
            });
        }
    </script>
@endsection
