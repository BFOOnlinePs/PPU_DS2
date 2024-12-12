@extends('layouts.app')
@section('title')
الملف الشخصي
@endsection
@section('header_title')
الملف الشخصي
@endsection
@section('header_title_link')
<a href="{{route('home')}}">{{__('translate.Main')}}{{-- الرئيسية --}}</a>
@endsection
@section('header_link')
الملف الشخصي
@endsection
@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="">الاسم</label>
                                <input type="text" readonly class="form-control" value="{{$data->name}}">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="">اسم المستخدم</label>
                                <input type="text" readonly class="form-control" value="{{$data->u_username}}">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="">رقم الجوال</label>
                                <input type="text" readonly class="form-control" value="{{$data->u_phone1}}">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="">رقم جوال احتياطي</label>
                                <input type="text" readonly class="form-control" value="{{$data->u_phone2}}">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="">البريد الالكتروني</label>
                                <input type="text" readonly class="form-control" value="{{$data->email}}">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="">العنوان</label>
                                <input type="text" readonly class="form-control" value="{{$data->u_address}}">
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="">تاريخ الميلاد</label>
                                <input type="text" readonly class="form-control" value="{{$data->u_date_of_birth}}">
                            </div>
                        </div>
                        <form action="{{ route('monitor_evaluation.update_password') }}" method="POST">
                            @csrf
                            <div class="form-group">
                                <label for="">كلمة المرور</label>
                                <input name="password" required type="text" class="form-control" value="{{$data->u_city}}">
                            </div>
                            <div class="form-group">
                                <button type="submit" class="btn btn-primary">تعديل كلمة المرور</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
