@extends('layouts.app')
@section('title')
    {{__('translate.System Settings')}}{{-- إعدادات النظام --}}
@endsection
@section('header_title')
    {{__('translate.System Settings')}}{{-- إعدادات النظام --}}
@endsection
@section('header_title_link')
<a href="{{route('home')}}">{{__('translate.Main')}}{{-- الرئيسية --}}</a>
@endsection
@section('header_link')
 <a href="{{route('admin.settings.systemSettings')}}">{{__('translate.System Settings')}}{{-- إعدادات النظام --}}</a>
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

    .input-error {
    border-color: #d22d3d;
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

        <h1>{{__('translate.System Settings')}}{{-- إعدادات النظام --}}</h1>
        <hr>
        <br>

        <div class="row">
            <div class="col-md-6">
                <form class="form-horizontal" id="updateSettingsForm" action="" method="POST" enctype="multipart/form-data">
                    @csrf
                    {{-- <div class="row"> --}}
                        {{-- <div class="col-md-4"> --}}
                            {{-- <div class="mb-3 row"> --}}
                                <label class="col-lg-12 form-label " for="textinput">{{__('translate.Current Academic Year')}}{{--العام الدراسي الحالي--}}</label>
                                <div class="col-lg-12">
                                    <input id="year" name="year" type="text" class="form-control" value="{{$year}}" oninput="validateInput(this)">

                                </div>
                            {{-- </div> --}}
                        {{-- </div> --}}
                    {{-- </div> --}}

                    {{-- <div class="row"> --}}
                        {{-- <div class="col-md-4"> --}}
                            {{-- <div class="mb-3 row"> --}}
                                <label class="col-lg-12 form-label " for="selectbasic">{{__('translate.Current Semester')}}{{--الفصل الدراسي الحالي--}}</label>
                                <div class="col-lg-12">
                                    <select id="semester" name="semester" class="form-control btn-square">
                                        <option value="1" @if($semester == '1') selected @endif>{{__('translate.First Semester')}}{{--فصل أول--}}</option>
                                        <option value="2" @if($semester == '2') selected @endif>{{__('translate.Second Semester')}}{{--فصل ثاني--}}</option>
                                        <option value="3" @if($semester == '3') selected @endif>{{__('translate.Summer Semester')}}{{--فصل صيفي--}}</option>
                                    </select>
                                </div>
                                <div class="col-md-12 mt-3">
                                    <div class="form-group">
                                        <label for="">حالة تسليم التقرير لدى الطالب</label>
                                        <select name="report_status" class="form-control" id="">
                                            <option @if ($report == 1)
                                                selected
                                            @endif value="1">تفعيل</option>
                                            <option @if ($report == 0)
                                                selected

                                            @endif value="0">الغاء التفعيل</option>
                                        </select>
                                        <p class="text-dark mt-1">من هذه القسم يمكنك تعيين حالة التسليم لدى الطالب</p>
                                    </div>
                                </div>
                            {{-- </div> --}}
                        {{-- </div> --}}
                    {{-- </div> --}}

                    <br>

                    <button class="btn btn-primary">{{__('translate.Save Changes')}}{{--حفظ--}}</button>

                    <br>
                </form>
            </div>
            <div class="col-md-6">
                {{-- <div class="col-sm-12 col-xl-6"> --}}
                    <div class="card">
                      <div class="card-header b-l-primary border-3">
                        {{-- <h5>عام {{$year}}، فصل {{$semester}}</h5> --}}
                        <h5 id="systemSettingsTitle"> @if($semester==1) {{__('translate.First Semester')}} {{--الأول--}} @elseif($semester==2) {{__('translate.Second Semester')}} {{--الثاني--}} @elseif($semester==3) {{__('translate.Summer Semester')}}{{--الصيفي--}} @endif {{__('translate.For Academic Year')}} {{--لعام--}} {{$year}}</h5>
                      </div>
                      <div class="card-body">
                        <h6 id="totalSemesterCourses">
                            {{-- إجمالي التدريبات العملية للفصل: {{$coursesNum}} --}}
                            {{__('translate.Total Semester Courses')}}: {{$coursesNum}}
                        </h6>
                        <h6 id="totalSemesterStudents">
                            {{-- إجمالي طلاب الفصل: {{$studentsNum}} --}}
                            {{__('translate.Total Semester Students')}}: {{$studentsNum}}
                        </h6>
                      </div>
                    </div>
                {{-- </div> --}}
            </div>
        </div>


    </div>





</div>


@endsection
@section('script')

<script>

    let submit = false;


    function validateInput(input) {
        // Remove non-numeric characters
        var cleanedValue = input.value.replace(/\D/g, '');

        // Restrict the input to 4 digits
        if (cleanedValue.length > 4) {
            cleanedValue = cleanedValue.slice(0, 4);
        }

        // Update the input value
        input.value = cleanedValue;
    }


    document.getElementById('updateSettingsForm').addEventListener("submit", (e) => {
        e.preventDefault();
        data = $('#updateSettingsForm').serialize();
        year = document.getElementById('year').value;
        semester = document.getElementById('semester').value;
        var csrfToken = $('meta[name="csrf-token"]').attr('content');

        if(document.getElementById('year').value != ""){
            submit=true;
        }else{
            $('#year').addClass('input-error');
        }

        if(submit){
            // Send an AJAX request with the CSRF token
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': csrfToken
                }
            });

            //Send an AJAX request
            $.ajax({
                beforeSend: function(){
                    document.getElementById('loaderContainer').hidden = false;
                },
                type: 'POST',
                url: "{{ route('admin.settings.systemSettingsUpdate') }}",
                data: data,
                dataType: 'json',
                success: function(response) {
                    document.getElementById('loaderContainer').hidden = true;
                    document.getElementById("totalSemesterStudents").innerHTML = "{{__('translate.Total Semester Students')}}: "+ `${response.studentsNum}`
                    document.getElementById("totalSemesterCourses").innerHTML = "{{__('translate.Total Semester Courses')}}: " + `${response.coursesNum}`
                    document.getElementById("systemSettingsTitle").innerHTML = `${semester == 1 ? "{{__('translate.First Semester')}}" : (semester == 2 ? "{{__('translate.Second Semester')}}" : (semester == 3 ? "{{__('translate.Summer Semester')}}" : ''))} {{__('translate.For Academic Year')}} ${year}`;


                },
                error: function(xhr, status, error) {
                    console.error(xhr.responseText);
                }
            });
        }

    });

    $('#year').on('focus', function() {
    	$('#year').removeClass('input-error');
    });


</script>

@endsection
