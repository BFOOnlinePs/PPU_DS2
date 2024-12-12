@extends('layouts.app')
@section('title')
{{__("translate.Payments' Report")}}{{-- تقرير الدفعات المالية --}}
@endsection
@section('header_title')
{{__("translate.Payments' Report")}}{{-- تقرير الدفعات المالية --}}
@endsection
@section('header_title_link')
<a href="{{route('home')}}">{{__('translate.Main')}}{{-- الرئيسية --}}</a>
@endsection
@section('header_link')
<a href="{{route('monitor_evaluation.paymentsReport')}}">{{__("translate.Payments' Report")}}{{-- تقرير الدفعات المالية --}}</a>
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

        <div class="loader-container loader-box" id="loaderContainer" hidden>
            <div class="loader-3"></div>
        </div>

        <form id="paymentsReportSearchForm" action="" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="row">

                <div class="col-md-3">
                    <div class="form-group">
                        <label class="col-form-label pt-0" for="exampleInputEmail1"> {{__('translate.Company')}} {{-- الشركة --}}</label>
                        <div class="col-lg-12">
                            <select id="company" name="company" class="form-control btn-square">
                                <option selected="" value="0">--{{__('translate.Choose')}}--    {{--اختيار--}}</option>
                                @foreach($companies as $key)
                                <option value={{$key->c_id}}> {{$key->c_name}} </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="form-group">
                        <label class="col-form-label pt-0" for="exampleInputEmail1">{{__('translate.Student')}} {{-- الطالب --}}</label>
                        <div class="col-lg-12">

                            <select class="js-example-basic-single col-sm-12" id="student" name="student" onchange="selectChange()">
                                <option selected="" value="0">--{{__('translate.Choose')}}--    {{--اختيار--}}</option>
                                @foreach($students as $key)
                                <option value={{$key->u_id}}> {{$key->name}} </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>




                <div class="col-md-2">
                    <div class="form-group">
                        <label class="col-form-label pt-0" for="exampleInputEmail1">{{__('translate.Semester')}}{{-- الفصل الدراسي --}}</label>
                        <div class="col-lg-12">
                            <select id="semester" name="semester" class="form-control btn-square">
                                {{-- <option value="0">جميع الفصول</option> --}}
                                <option value="1" @if($semester==1) selected @endif>{{__('translate.First')}}</option>
                                <option value="2" @if($semester==2) selected @endif>{{__('translate.Second')}}</option>
                                <option value="3" @if($semester==3) selected @endif>{{__('translate.Summer')}}</option>
                            </select>
                        </div>
                    </div>
                </div>

                <div class="col-md-2">
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

                <div class="col-md-2">
                    <div class="form-group">
                        <label class="col-form-label pt-0" for="exampleInputEmail1"> {{__('translate.Payment Status')}} {{-- حالة الدفعة --}}</label>
                        <div class="col-lg-12">
                            <select id="status" name="status" class="form-control btn-square">
                                <option selected="" value="2">--{{__('translate.Choose')}}--    {{--اختيار--}}</option>
                                <option value='0'> {{__('translate.Not Confirmed')}} {{-- غير مؤكدة --}}</option>
                                <option value='1'>{{__('translate.Confirmed')}} {{-- مؤكدة --}}</option>
                            </select>
                        </div>
                    </div>
                </div>

            </div>
        </form>

        <div id="paymentsReportTable">
            <div class="table-responsive">
                <table class="table table-bordered table-striped">

                    <thead>
                        <tr>
                            <th scope="col">{{__('translate.Company')}} {{-- الشركة --}}</th>
                            <th scope="col">{{__('translate.The student')}} {{-- الطالب --}}</th>
                            <th scope="col">{{__('translate.Payment Date')}} {{-- تاريخ الدفعة --}}</th>
                            <th scope="col">{{__('translate.Payment Amount')}} {{-- قيمة الدفعة --}}</th>
                            <th scope="col">{{__('translate.Payment Status')}} {{-- حالة الدفعة --}} </th>

                        </tr>
                    </thead>
                    <tbody>
                        @if ($payments->isEmpty())
                        <tr>
                            <td colspan="6" class="text-center"><span> {{__('translate.No available data')}} {{-- لا توجد بيانات  --}}</span></td>
                        </tr>
                        @else
                            @foreach ($payments as $key)
                            <tr>
                                <td>{{$key->payments->c_name}}</td>
                                <td>{{$key->userStudent->name}}</td>
                                <td>{{ \Carbon\Carbon::parse($key->created_at)->format('Y-m-d') }}</td>
                                <td>{{ $key->p_payment_value == intval($key->p_payment_value) ? number_format($key->p_payment_value) : number_format(floor($key->p_payment_value * 100) / 100, 2, '.', '') }}  <span>{{$key->currency->c_symbol}}</span></td>
                                <td>@if ($key->p_status == 0) <span class="badge rounded-pill badge-danger">{{__('translate.Not Confirmed')}} {{-- غير مؤكدة --}}</span>  @else <span class="badge rounded-pill badge-primary">{{__('translate.Confirmed')}} {{-- مؤكدة --}}</span> @endif</td>
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

    function selectChange(){
        console.log('hi from test')
        data = $('#paymentsReportSearchForm').serialize();
            console.log(data)
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
                url: "{{ route('monitor_evaluation.paymentsReportSearch') }}",
                data: data,
                dataType: 'json',
                success: function(response) {
                    document.getElementById('loaderContainer').hidden = true;
                    console.log(response.trainings);
                    $('#paymentsReportTable').html(response.view);
                },
                error: function(xhr, status, error) {
                    console.error(xhr.responseText);
                }
            });
    }

    $('#paymentsReportSearchForm').find('select').each(function() {
        element = `${$(this)[0].id}`
        // console.log("hi")
        if(element!='student'){
            document.getElementById(`${element}`).addEventListener("change", function() {
            console.log("hi")
            //console.log($(this).value)
            data = $('#paymentsReportSearchForm').serialize();
            console.log(data)
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
                url: "{{ route('monitor_evaluation.paymentsReportSearch') }}",
                data: data,
                dataType: 'json',
                success: function(response) {
                    document.getElementById('loaderContainer').hidden = true;
                    console.log(response.trainings);
                    $('#paymentsReportTable').html(response.view);
                },
                error: function(xhr, status, error) {
                    console.error(xhr.responseText);
                }
            });
            })
        }

    })
</script>


@endsection
