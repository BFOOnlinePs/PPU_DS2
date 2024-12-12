@extends('layouts.app')
@section('title')
{{__('translate.Profile')}} {{-- الملف الشخصي --}}
@endsection
@section('header_title')
{{__('translate.Profile')}} {{-- الملف الشخصي --}}
@endsection
@section('header_title_link')
<a href="{{route('home')}}">{{__('translate.Main')}}{{-- الرئيسية --}}</a>
@endsection
@section('header_link')
<a href="{{route('students.personal_profile.index')}}">{{__('translate.Profile')}} {{-- الملف الشخصي --}}</a>
@endsection
@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header d-flex">
                        <img style="height:{{$data['_embedded']['wp:featuredmedia'][0]['media_details']['sizes']['large']['height']}};flex:1" src="{{ $data['_embedded']['wp:featuredmedia'][0]['media_details']['sizes']['large']['source_url'] }}" alt="">
                    </div>
                    <div class="card-body">
                        {!! $data['content']['rendered'] !!}
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

