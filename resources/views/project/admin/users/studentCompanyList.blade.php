@extends('layouts.app')
@section('title')
{{__('translate.Users')}}{{-- المستخدمين --}}
@endsection
@section('header_title_link')
{{__('translate.Users')}}{{-- المستخدمين --}}
@endsection
@section('header_link')
{{-- تعديل المستخدم / <a href="{{route('admin.users.details' , ['id'=>$user->u_id])}}">{{$user->name}}</a> --}}
@endsection
@section('content')
<div class="container-fluid">
    <div>
      <div class="row">
        <div class="col-xl-12">
          <form class="card">
            <div class="card-body">
                <div class="row">
                    @foreach ($student_companies as $student_company)
                        <a href="{{route('student.training.company' , ['id' => $student_company->sc_id])}}" class="btn btn-success  btn-lg m-1">{{$student_company->company->c_name}}</a>
                    @endforeach
              </div>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
@endsection
@section('script')

@endsection
