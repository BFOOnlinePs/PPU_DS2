@extends('layouts.app')
@section('title')
{{__('translate.company_trainees')}}{{--طلاب الشركة--}}
@endsection
@section('header_title')
{{__('translate.company_trainees')}}{{--طلاب الشركة--}}
@endsection
@section('header_title_link')
<a href="{{route('home')}}">{{__('translate.Main')}}{{-- الرئيسية --}}</a>
@endsection
@section('header_link')
<a href="{{route('monitor_evaluation.companiesReport')}}">{{__("translate.Companies' Report")}}{{-- تقرير الشركات --}}</a> / {{__('translate.company_trainees')}}{{--طلاب الشركة--}}
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

        <h4 class="text-center">{{$company_name}}</h4>
        <hr>
        {{-- <br> --}}

        {{-- <a href="{{ route('monitor_evaluation.companiesReportPDF', ['data' => base64_encode(serialize($data))]) }}">Go to View 2</a> --}}

        {{-- <div>
            <button class="btn btn-primary mb-2 btn-s" id="semsterPDFButton" onclick="showCompaniesPDF()"><i class="fa fa-file-pdf-o"></i> ملف التقرير </button>
        </div> --}}

        {{-- <br> --}}

        <form id="companyStudentsSearchForm" action="" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="row">

                <div class="col-md-4">
                    <div class="form-group">
                        <label class="col-form-label pt-0" for="exampleInputEmail1">{{__('translate.Semester')}}{{-- الفصل الدراسي --}}</label>
                        {{-- <input class="form-control" id="semester" name="semester"> --}}
                        <div class="col-lg-12">
                            <select id="semester" name="semester" class="form-control btn-square">
                                <option value="0">{{__('translate.All Semesters')}}{{-- جميع الفصول  --}}</option>
                                <option value="1" @if($semester==1) selected @endif>{{__('translate.First')}}{{-- أول --}}</option>
                                <option value="2" @if($semester==2) selected @endif>{{__('translate.Second')}}{{-- ثاني --}}</option>
                                <option value="3" @if($semester==3) selected @endif>{{__('translate.Summer')}}{{-- صيفي --}}</option>
                            </select>
                        </div>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="form-group">
                        <label class="col-form-label pt-0" for="exampleInputEmail1">{{__('translate.Gender')}}{{--الجنس--}}</label>
                        <div class="col-lg-12">
                            <select id="gender" name="gender" class="form-control btn-square">
                                <option value="-1" selected>--{{__('translate.Choose')}}--</option>
                                <option value="0">{{__('translate.Male')}}{{-- ذكر --}}</option>
                                <option value="1">{{__('translate.Female')}}{{-- أنثى --}}</option>
                            </select>
                        </div>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="form-group">
                        <label class="col-form-label pt-0" for="exampleInputEmail1">{{__('translate.Major')}}{{--التخصص--}}</label>
                        <div class="col-lg-12">
                            <select id="major" name="major" class="form-control btn-square">
                                <option value="-1" selected>--{{__('translate.Choose')}}--</option>
                                @foreach($majors as $key)
                                <option value={{$key->m_id}}> {{$key->m_name}} </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>

            </div>
        </form>

        <div id="companyStudentsTable">
            <div class="table-responsive">
                <table class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th scope="col">{{__('translate.student_id')}}{{--رقم الطالب--}}</th>
                            <th scope="col">{{__('translate.Student Name')}}{{--اسم الطالب--}}</th>
                            <th scope="col">{{__('translate.student_major')}}{{--تخصص الطالب--}}</th>
                        </tr>
                    </thead>
                    <tbody>
                    @if ($data->isEmpty())
                        <tr>
                            <td colspan="3" class="text-center"><span>{{__('translate.No available data')}} {{-- لا توجد بيانات  --}}</span></td>
                        </tr>
                    @else
                        @foreach ($data as $key)
                            <tr>
                                <td>{{ $key->users->u_username }}</td>
                                <td>{{ $key->users->name }}</td>
                                <td>{{ $key->major }}</td>
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




window.addEventListener("load", (event) => {

    $('#companyStudentsSearchForm').find('select').each(function() {
        element = `${$(this)[0].id}`
        document.getElementById(`${element}`).addEventListener("change", function() {

            data = $('#companyStudentsSearchForm').serialize();
            var csrfToken = $('meta[name="csrf-token"]').attr('content');

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
                url: "{{ route('monitor_evaluation.companyStudentsReportSearch') }}",
                data: data,
                dataType: 'json',
                success: function(response) {
                    document.getElementById('loaderContainer').hidden = true;
                    $('#companyStudentsTable').html(response.view);
                },
                error: function(xhr, status, error) {
                    console.error(xhr.responseText);
                }
            });
        })
    })
})
</script>

@endsection
