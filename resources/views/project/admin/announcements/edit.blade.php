@extends('layouts.app')
@section('title')
{{__('translate.Main')}}{{--الرئيسية --}}
@endsection
@section('header_title')
{{__('translate.the_announcement')}}{{-- الاعلان--}}
@endsection
@section('header_title_link')
<a href="{{route('home')}}">{{__('translate.Main')}}{{-- الرئيسية --}}</a>
@endsection
@section('header_link')
{{__('translate.the_announcement')}}{{-- الاعلان--}}</a>
@endsection

@section('style')
<link rel="stylesheet" type="text/css" href="{{ asset('assets/css/select2.css') }}">
@endsection

@section('content')

<div class="card">
    <div class="card-body">
<div class="a_title" style=" font-size:25px ;font-weight: bold;">
    {{$data->a_title}}
</div>
<hr>
<div class="a_content" style=" font-size:15px;">
    {{$data->a_content}}
</div>
<br>

<br>
<img src="{{ asset('../storage/app/public/uploads/announcements/'.$data->a_image ) }}" style="width:40% ; border-radius:5px">

<div class="a_date" style="justify-content: flex-end;display:flex ; font-weight:900">
    {{$data->created_at->format('Y/F/d')}}
</div>
</div>
</div>
@endsection


@section('script')

@endsection