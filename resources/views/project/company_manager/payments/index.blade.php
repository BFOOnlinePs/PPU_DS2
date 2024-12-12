@extends('layouts.app')
@section('title')
{{__('translate.Payments')}} {{-- الدفعات   --}}
@endsection
@section('header_title')
{{__('translate.Payments')}} {{--  الدفعات --}}
@endsection
@section('header_title_link')
<a href="{{route('home')}}">{{__('translate.Main')}}{{-- الرئيسية --}}</a>
@endsection
@section('header_link')
<a href="{{route('company_manager.payments.index')}}">{{__('translate.Payments')}} {{--  الدفعات --}}</a>
@endsection
@section('header_link')
@endsection
@section('content')
    <div class="container-fluid">
        <div class="card">
            <div class="card-body">
                @include('project.company_manager.payments.includes.paymentsList')
            </div>
        </div>
    </div>
@endsection
@section('script')
@endsection

