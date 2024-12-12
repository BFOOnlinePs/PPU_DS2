@extends('layouts.app')
@section('title')
{{__('translate.Supervisor Students in this Company')}}{{-- طلاب المشرف في هذه الشركة --}}
@endsection
@section('header_title')
{{__('translate.Supervisor Students in this Company')}}{{-- طلاب المشرف في هذه الشركة --}}
@endsection
@section('header_title_link')
<a href="{{route('home')}}">{{__('translate.Main')}}{{-- الرئيسية --}}</a>
@endsection
@section('header_link')
<a href="{{ route('supervisors.companies.index') }}">{{__('translate.Training Places')}} {{-- أماكن التدريب --}}</a>
@endsection
@section('content')
<div class="col-sm-12">
    <div class="card">
        <div class="card-body">
            <div>
                @include('project.supervisor.companies.tables.students')
            </div>
        </div>
    </div>
</div>
@endsection
@section('script')
@endsection
