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
        <div class="edit-profile">
            <div class="row">
                <div class="col-md-12 card">
{{--                    <form action="{{ route('admin.users.update') }}" class="card" method="post" enctype="multipart/form-data">--}}
                        @csrf
                        <input type="hidden" name="u_id" value="{{ $user->u_id }}">
                        <div class="card-header pb-0">
                            <div class="card-options">
                                <a class="card-options-collapse" href="#" data-bs-toggle="card-collapse">
                                    <i class="fe fe-chevron-up"></i>
                                </a>
                                <a class="card-options-remove" href="#" data-bs-toggle="card-remove">
                                    <i class="fe fe-x"></i>
                                </a>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-8">
                                    <div class="row">

                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label class="form-label">{{__('translate.Name')}} {{-- الاسم --}}</label>
                                                    <input class="form-control" type="text" name="name" value="{{ $user->name }}" readonly>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label class="form-label">{{__('translate.Username')}} {{-- اسم المستخدم --}}</label>
                                                    <input class="form-control" type="text" name="u_username" value="{{ $user->u_username }}" readonly>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label class="form-label">{{__('translate.Email')}} {{-- البريد الإلكتروني --}}</label>
                                                    <input class="form-control" type="email" name="email" value="{{ $user->email }}" readonly>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label class="form-label">{{__('translate.Birth Date')}} {{-- تاريخ الميلاد --}}</label>
                                                    <input class="form-control" type="date" name="u_date_of_birth" value="{{ $user->u_date_of_birth }}" readonly>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label class="form-label">{{__('translate.Phone Number')}} {{-- رقم الجوال --}}</label>
                                                    <input class="form-control" type="text" name="u_phone1" value="{{ $user->u_phone1 }}" readonly>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label class="form-label">{{__('translate.Alternative Phone Number')}} {{-- رقم جوال احتياطي --}}</label>
                                                    <input class="form-control" type="text" name="u_phone2" value="{{ $user->u_phone2 }}" readonly>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <label class="form-label">{{__('translate.Gender')}}{{-- الجنس --}}</label>
                                                @if ($user->u_gender == 0)
                                                    <input class="form-control" type="text" value="ذكر" readonly>
                                                @else
                                                    <input class="form-control" type="text" value="أنثى" readonly>
                                                @endif
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label class="form-label">{{__('translate.Residential Address')}} {{-- عنوان السكن --}}</label>
                                                    <input class="form-control" type="text" name="u_address" value="{{ $user->u_address }}" readonly>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label class="form-label">{{__('translate.Major')}} {{-- التخصص --}}</label>
                                                    <input class="form-control" type="text" value="{{$major_id->m_name}}" readonly>
                                                </div>
                                            </div>
                                            <form action="{{ route('students.personal_profile.update_password') }}" method="post">
                                                @csrf
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label class="form-label">كلمة المرور</label>
                                                    <input class="form-control" min="8" name="password" type="text" placeholder="كلمة المرور">
                                                </div>
                                            </div>
                                            <div class="col-md-12">
                                                <button type="submit" class="btn btn-success">تعديل</button>
                                            </div>
                                        </form>

                                        </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <h6 class="text-center">السيرة الذاتية</h6>
                                            <div class="text-center">
                                                @if(!empty($user->u_cv))
                                                    <a class="btn btn-info btn-sm w-100 mb-3" download="cv_{{ $user->u_cv }}" href="{{ asset('public/storage/uploads/'.$user->u_cv) }}"><span class="fa fa-download"></span> <span>تحميل السيرة الذاتية</span></a>
                                                @endif
                                                <p class="text-bold">لتحديث ملف السيرة الذاتية يرجى اختيار ملف السيرة الذاتية الجديد والضغط على زر تحديث السيرة الذاتية</p>
                                                    <form action="{{ route('students.personal_profile.add_sv_to_student') }}" class="mt-2" method="post" enctype="multipart/form-data">
                                                        @csrf
                                                        <input type="hidden" name="id" value="{{ $user->u_id }}">
                                                        <div class="form-group">
                                                            <input type="file" name="cv_file" id="cv_file" class="form-control">
                                                        </div>
                                                        <button type="submit" class="btn btn-success btn-sm">تحديث السيرة الذاتية</button>
                                                    </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
{{--                    </form>--}}
                </div>
            </div>
        </div>
    </div>
@endsection

