@extends('layouts.app')
@section('title')
{{__('translate.Students')}}{{-- الطلاب  --}}
@endsection
@section('header_title')
{{__('translate.Students')}}{{-- الطلاب  --}}
@endsection
@section('header_title_link')
<a href="{{route('home')}}">{{__('translate.Main')}}{{-- الرئيسية --}}</a>
@endsection
@section('header_link')
<a href="{{route('company_manager.students.index')}}">{{__('translate.Students')}}{{-- الطلاب  --}}</a>
@endsection
@section('style')
    <link rel="stylesheet" type="text/css" href="{{asset('assets/css/datatables.css')}}">
@endsection
@section('header_link')
@endsection
@section('content')
<div class="container-fluid">
    <div class="card">
        <div class="card-body">
            @include('project.company_manager.students.includes.studentsList')
            </div>
        </div>
    </div>
@endsection
@section('script')
<script src="{{asset('assets/js/datatable/datatables/jquery.dataTables.min.js')}}"></script>
<script src="{{asset('assets/js/datatable/datatables/datatable.custom.js')}}"></script>
<script>
    $(document).ready(function() {
        let table = $('#students').DataTable();
    });
</script>
@endsection

