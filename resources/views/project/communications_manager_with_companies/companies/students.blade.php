@extends('layouts.app')
@section('title')
{{__('translate.Students')}}{{-- الطلاب --}}
@endsection
@section('header_title')
{{__('translate.Students')}}{{-- الطلاب --}}
@endsection
@section('header_title_link')
    <a href="{{route('home')}}">{{__('translate.Main')}}{{-- الرئيسية --}}</a>
@endsection
@section('header_link')
<a href="{{ route('communications_manager_with_companies.companies.index') }}">{{__('translate.Training Places')}}{{-- أماكن التدريب --}}</a>
@endsection
@section('content')
<div class="col-sm-12">
    <div class="card">
        <div class="card-body">
            <div>
                @include('project.communications_manager_with_companies.companies.tables.students')
            </div>
        </div>
    </div>
</div>
@endsection
@section('script')
@endsection
