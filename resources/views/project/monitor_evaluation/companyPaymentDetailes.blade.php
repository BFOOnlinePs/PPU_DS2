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

@endsection

@section('content')

<div class="card" style="padding-left:0px; padding-right:0px;">

    <div class="card-body" >

        <h5>{{__("translate.Company's Payments Details")}} {{-- تفاصيل دفعات  --}} {{-- شركة --}} : {{$payments[0]->payments->c_name}}، {{__('translate.For student')}} {{--  إلى الطالب--}}: {{$payments[0]->userStudent->name}}</h5>
        {{-- <hr> --}}
        {{-- <h6>الشركة</h6> --}}

        <br>

        <table class="table table-bordered table-striped">

            <thead>
                <tr>
                    <th scope="col">{{__('translate.Payment Date')}} {{-- تاريخ الدفعة --}} </th>
                    <th scope="col">{{__('translate.Payment Amount')}} {{-- قيمة الدفعة --}} </th>
                    <th scope="col">{{__('translate.Payment Status')}} {{-- حالة الدفعة --}}  </th>

                </tr>
            </thead>
            <tbody>
                @foreach ($payments as $key)
                <tr>
                    <td>{{ \Carbon\Carbon::parse($key->created_at)->format('Y-m-d') }}</td>
                    <td>{{ $key->p_payment_value == intval($key->p_payment_value) ? number_format($key->p_payment_value) : number_format(floor($key->p_payment_value * 100) / 100, 2, '.', '') }} <span>{{$key->currency->c_symbol}}</span></td>
                    <td>@if ($key->p_status == 0) {{__('translate.Not Confirmed')}} {{-- غير مؤكدة --}}@else {{__('translate.Confirmed')}} {{-- مؤكدة --}} @endif</td>
                </tr>
                @endforeach
            </tbody>
            <tfoot>
                <tr>
                    <td>{{__("translate.Total Payments")}}{{--إجمالي الدفعات--}}</td>
                    <td>
                        @foreach ($trainingPayment->paymentsTotalCollection as $item)
                            @if($item["total"] != 0)
                                <span class="badge rounded-pill badge-danger">{{ $item["total"] == intval($item["total"]) ? number_format($item["total"]) : number_format(floor($item["total"] * 100) / 100, 2, '.', '') }} <span>{{$item["symbol"]}}</span></span>
                            @endif
                        @endforeach
                    <td></td>
                </tr>
            </tfoot>
        </table>

    </div>




</div>

@endsection
@section('script')



@endsection
