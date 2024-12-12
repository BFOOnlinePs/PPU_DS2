@extends('layouts.app')
@section('title')
{{__("translate.Companies Payments' Report")}}{{-- تقرير دفعات الشركات --}}
@endsection
@section('header_title')
{{__("translate.Companies Payments' Report")}}{{-- تقرير دفعات الشركات --}}
@endsection
@section('header_title_link')
<a href="{{route('home')}}">{{__('translate.Main')}}{{-- الرئيسية --}}</a>
@endsection
@section('header_link')
<a href="{{route('monitor_evaluation.companiesPaymentsReport')}}">{{__("translate.Companies Payments' Report")}}{{-- تقرير دفعات الشركات --}}</a>
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

        <h4 class="text-center" id="companiesPaymentsReportTitle"></h4>
        <hr>

        <form id="companiesPaymentsReportSearchForm" action="" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="row">

                <div class="col-md-4">
                    <div class="form-group">
                        <label class="col-form-label pt-0" for="exampleInputEmail1">{{__('translate.Company')}}{{--الشركة--}}</label>
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


                <div class="col-md-4">
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

                <div class="col-md-4">
                    <div class="form-group">
                        <label class="col-form-label pt-0" for="exampleInputEmail1">{{__('translate.Academic Year')}} {{-- العام الدراسي --}}</label>
                        <div class="col-lg-12">
                            <select id="year" name="year" class="form-control btn-square">
                                @foreach($years as $key)
                                <option value={{$key}} @if($key == $year) selected @endif> {{$key}} </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>

            </div>
        </form>

        <div id="companiesPaymentsReportTable">
            <div class="table-responsive">
                <table class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th scope="col" style="display:none;">id</th>
                            <th scope="col">{{__('translate.Company')}} {{-- الشركة --}}</th>
                            <th scope="col">{{__('translate.The Trainee')}} {{-- المتدرب --}}</th>
                            <th scope="col">{{__('translate.Payment Total')}} {{--إجمالي الدفعات --}}</th>
                            <th scope="col">{{__('translate.Total of Confirmed Payments')}} {{--  إجمالي الدفعات المؤكد عليها  --}}</th>
                            <th scope="col">{{__('translate.Payments Details')}} {{-- تفاصيل الدفعات  --}}</th>

                        </tr>
                    </thead>
                    <tbody>
                    @if ($companiesPayments->isEmpty())
                        <tr>
                            <td colspan="6" class="text-center"><span>{{__('translate.No available data')}} {{-- لا توجد بيانات  --}}</span></td>
                        </tr>
                    @else
                        @foreach ($companiesPayments as $key)
                            <tr>
                                <td style="display:none;">{{ $key->p_id }}</td>
                                <td>{{ $key->payments->c_name }}</td>
                                <td>{{ $key->userStudent->name }}</td>
                                <td>
                                    @foreach ($key->paymentsTotalCollection as $item)
                                        @if($item["total"] != 0)
                                            <span class="badge rounded-pill badge-danger">{{ $item["total"] == intval($item["total"]) ? number_format($item["total"]) : number_format(floor($item["total"] * 100) / 100, 2, '.', '') }} <span>{{$item["symbol"]}}</span></span>
                                        @endif
                                    @endforeach
                                </td>
                                <td>
                                    @foreach ($key->approvedPaymentsTotalCollection as $item)
                                    @if($item["total"] != 0)
                                        <span class="badge rounded-pill badge-danger">{{ $item["total"] == intval($item["total"]) ? number_format($item["total"]) : number_format(floor($item["total"] * 100) / 100, 2, '.', '') }} <span>{{$item["symbol"]}}</span></span>
                                    @endif
                                    @endforeach
                                </td>



                                <td>
                                    <form id="testForm" action="{{route('monitor_evaluation.companyPaymentDetailes')}}" method="POST" enctype="multipart/form-data">
                                        @csrf
                                        <input hidden id="test" name="test" value="{{base64_encode(serialize($key))}}">
                                        <button class="btn btn-primary" type="submit"><i class="fa fa-search"></i></button>
                                    </form>
                                  </td>
                            </tr>
                        @endforeach
                    @endif
                    </tbody>
                    @if (!$companiesPayments->isEmpty())
                    <tfoot>
                        <tr>
                            <td colspan="2">{{__('translate.total_companies_payments')}}{{--إجمالي دفعات الشركات--}}</td>
                            <td>
                                @foreach ($totalCollection as $item)
                                    @if($item["total"] != 0)
                                        <span class="badge rounded-pill badge-danger">{{ $item["total"] == intval($item["total"]) ? number_format($item["total"]) : number_format(floor($item["total"] * 100) / 100, 2, '.', '') }} <span>{{$item["symbol"]}}</span></span>
                                    @endif
                                @endforeach
                            </td>
                            <td>
                                @foreach ($totalApprovedCollection as $item)
                                    @if($item["total"] != 0)
                                        <span class="badge rounded-pill badge-danger">{{ $item["total"] == intval($item["total"]) ? number_format($item["total"]) : number_format(floor($item["total"] * 100) / 100, 2, '.', '') }} <span>{{$item["symbol"]}}</span></span>
                                    @endif
                                @endforeach
                            </td>
                        </tr>
                    </tfoot>
                    @endif
                </table>
            </div>
        </div>


    </div>




</div>

@endsection
@section('script')

<script>

window.addEventListener("load", (event) => {



    // reportTitle=""
    // if(semester==1){
    //     reportTitle = `تقرير الشركات للفصل الدراسي الأول`
    // }else if(semester==2){
    //     reportTitle = `تقرير الشركات للفصل الدراسي الثاني`
    // }else if(semester==3){
    //     reportTitle = `تقرير الشركات للفصل الدراسي الصيفي`
    // }
    // $('#companiesReportTitle').html(reportTitle);

    $('#companiesPaymentsReportSearchForm').find('select').each(function() {
        element = `${$(this)[0].id}`
        document.getElementById(`${element}`).addEventListener("change", function() {
            //console.log($(this).value)
            data = $('#companiesPaymentsReportSearchForm').serialize();
            // console.log(data)
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
                url: "{{ route('monitor_evaluation.companiesPaymentsSearch') }}",
                data: data,
                dataType: 'json',
                success: function(response) {
                    //console.log("all has done")
                    document.getElementById('loaderContainer').hidden = true;
                    $('#companiesPaymentsReportTable').html(response.view);
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
