@extends('layouts.app')
@section('title')
{{__('translate.student_enrolled_in_courses_report')}}{{--تقرير الطلاب المسجلين في المساقات--}}
@endsection
@section('header_title')
{{__('translate.student_enrolled_in_courses_report')}}{{--تقرير الطلاب المسجلين في المساقات--}}
@endsection
@section('header_title_link')
<a href="{{route('home')}}">{{__('translate.Main')}}{{-- الرئيسية --}}</a>
@endsection
@section('header_link')
<a href="{{route('monitor_evaluation.semesterReport')}}">{{__("translate.Semester's Report")}}</a> / <a href="{{route('monitor_evaluation.students_courses_report')}}">{{__('translate.student_enrolled_in_courses_report')}}{{--تقرير الطلاب المسجلين في المساقات--}}</a>
@endsection

@section('style')

<style>
.loader-container {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background-color: rgba(0, 0, 0, 0.35); /* خلفية شفافة لشاشة التحميل */
    display: flex;
    justify-content: center;
    align-items: center;
    z-index: 9999; /* يجعل شاشة التحميل فوق جميع العناصر الأخرى */
    }
</style>

@endsection

@section('content')

<div class="card" style="padding-left:0px; padding-right:0px;">

    <div class="card-body" >

        <!--loading whole page-->
        <div class="loader-container loader-box" id="loaderContainer" hidden>
            <div class="loader-3"></div>
        </div>
        <!--//////////////////-->



        <form action="{{route('monitor_evaluation.studentsCoursesPDF')}}" method="POST" enctype="multipart/form-data" target="_blank">
            @csrf
            <div>
                <input hidden id="test" name="test" value="{{base64_encode(serialize($data))}}">
                <input hidden id="genderText" name="genderText" value="{{$gender}}">
                <input hidden id="majorText" name="majorText" value="{{$majorText}}">
                <input hidden id="semesterText" name="semesterText" value="{{$semester}}">
                <input hidden id="title" name="title" value="{{$title}}">
                <input hidden id="yearText" name="yearText" value="{{$year}}">
                <button class="btn btn-primary mb-2 btn-s" type="submit"><i class="fa fa-print"></i> </button>
            </div>
        </form>

        <br>

        <form id="searchForm" action="" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="row">
                <div class="col-md-3">
                    <div class="form-group">
                        <label class="col-form-label pt-0" for="exampleInputEmail1">{{__('translate.Semester')}}{{-- الفصل الدراسي --}}</label>
                        <div class="col-lg-12">
                            <select id="semester" name="semester" class="form-control btn-square">
                                {{-- <option value="0">{{__('translate.All Semesters')}}جميع الفصول </option> --}}
                                <option value="1" @if($semester==1) selected @endif>{{__('translate.First')}}</option>
                                <option value="2" @if($semester==2) selected @endif>{{__('translate.Second')}}</option>
                                <option value="3" @if($semester==3) selected @endif>{{__('translate.Summer')}}</option>
                            </select>
                        </div>
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="form-group">
                        <label class="col-form-label pt-0" for="exampleInputEmail1">{{__('translate.Academic Year')}} {{-- العام الدراسي --}} </label>
                        <div class="col-lg-12">
                            <select id="year" name="year" class="form-control btn-square">
                                @foreach($years as $key)
                                <option value={{$key}} @if($key == $year) selected @endif> {{$key}} </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>


                <div class="col-md-3">
                    <div class="form-group">
                        <label class="col-form-label pt-0" for="exampleInputEmail1"> {{__('translate.Gender')}}{{--  الجنس --}}</label>
                        <div class="col-lg-12">
                            <select id="gender" name="gender" class="form-control btn-square">
                                <option selected="" value="-1">--{{__('translate.Choose')}}{{--اختيار--}}--</option>
                                <option value="0">{{__('translate.Male')}}</option>
                                <option value="1">{{__('translate.Female')}}</option>
                            </select>
                        </div>
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="form-group">
                        <label class="col-form-label pt-0" for="exampleInputEmail1">{{__('translate.Major')}} {{-- التخصص --}} </label>
                        <div class="col-lg-12">
                            <select id="major" name="major" class="form-control btn-square">
                                <option selected="" value="0">--{{__('translate.Choose')}}{{--اختيار--}}--</option>
                                @foreach($majors as $key)
                                <option value={{$key->m_id}}> {{$key->m_name}} </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>

            </div>
        </form>



        <div id="studentsCoursesReportTable">
            <div class="table-responsive">
                <table class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th scope="col" style="display:none;">id</th>
                            <th scope="col">{{__('translate.student_id')}}{{--رقم الطالب--}}</th>
                            <th scope="col">{{__('translate.Student Name')}}{{--اسم الطالب--}}</th>
                            <th scope="col">{{__('translate.total_enrolled_courses')}}{{--إجمالي المساقات المسجلة--}}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @if ($data->isEmpty())
                        <tr>
                            <td colspan="6" class="text-center"><span>{{__('translate.No available data')}} {{-- لا توجد بيانات  --}}</span></td>
                        </tr>
                        @else
                            @foreach ($data as $key)
                                <tr>
                                    <td style="display:none;">{{ $key->r_id }}</td>
                                    <td>{{ $key->users->u_username }}</td>
                                    <td>{{ $key->users->name }}</td>
                                    <td>{{ $key->coursesNum }}</td>

                                </tr>
                            @endforeach
                        @endif
                    </tbody>
                </table>
            </div>
        </div>


    </div>




</div>

@endsection
@section('script')

<script>
$('#searchForm').find('select').each(function() {
        element = `${$(this)[0].id}`
        console.log("hi")
        console.log(element)
        document.getElementById(`${element}`).addEventListener("change", function() {
            console.log("hi")
            data = $('#searchForm').serialize();

            var csrfToken = $('meta[name="csrf-token"]').attr('content');
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': csrfToken
                }
            });

            $.ajax({
                beforeSend: function(){
                    document.getElementById('loaderContainer').hidden = false;
                },
                type: 'POST',
                url: "{{ route('monitor_evaluation.studentsCoursesAjax') }}",
                data: data,
                dataType: 'json',
                success: function(response) {
                    // dataPDF = response.pdf;
                    document.getElementById('loaderContainer').hidden = true;
                    document.getElementById('test').value = response.data;
                    document.getElementById('genderText').value = response.gender;
                    document.getElementById('majorText').value = response.majorText;
                    document.getElementById('semesterText').value = response.semester;
                    document.getElementById('yearText').value = response.year;
                    $('#studentsCoursesReportTable').html(response.view);

                },
                error: function(xhr, status, error) {
                    console.error(xhr.responseText);
                }
            });
        })
})

</script>

@endsection
