@extends('layouts.app')
@section('title')
{{__('translate.Training Places')}}{{-- أماكن التدريب --}}
@endsection
@section('header_title')
{{__('translate.Training Places')}}{{-- أماكن التدريب --}}
@endsection
@section('header_title_link')
    <a href="{{route('home')}}">{{__('translate.Main')}}{{--الرئيسية--}}</a>
@endsection
@section('header_link')
<a href="{{route('supervisor_assistants.companies.index')}}">{{__('translate.Training Places')}}{{-- أماكن التدريب --}}</a>
@endsection
@section('content')
<div class="col-sm-12">
    <div class="card">
        <div class="card-body">
            <div id="user-table">
                @include('project.assistant.companies.tables.companiesList')
            </div>
        </div>
    </div>
</div>
@endsection
@section('script')
@endsection
