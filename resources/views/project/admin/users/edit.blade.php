@extends('layouts.app')
@section('title')
{{__('translate.Users')}}{{-- المستخدمين --}}
@endsection
@section('header_title_link')
{{__('translate.Users')}}{{-- المستخدمين --}}
@endsection
@section('header_link')
{{__('translate.Edit User Information')}}{{--تعديل المستخدم--}} @endsection



@section('content')
<div class="container-fluid">
    <div class="edit-profile">
        @if(session('success'))
            <div class="alert alert-success">
                @if (session('success') == 'تم تعديل بيانات هذا المستخدم بنجاح')
                    {{__('translate.User data changes saved')}}
                @endif
            </div>
        @endif
        @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>
                            {{$error}}
                        </li>
                    @endforeach
                </ul>
            </div>
        @endif
        <div class="row">
            <div class="col-md-12">
                <form action="{{ route('admin.users.update') }}" class="card" method="post" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" name="u_id" value="{{ $user->u_id }}">
                    <div class="card-header pb-0">
                        @if (auth()->user()->u_role_id == 2) {{-- Student --}}
                            <h4 class="card-title mb-0">{{__('translate.Profile')}} {{-- الملف الشخصي --}}</h4>
                        @else
                            <h4 class="card-title mb-0">{{__('translate.Edit User Information')}} {{-- تعديل معلومات المستخدم --}} | {{ $user->u_username }}</h4>
                        @endif
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
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label class="form-label">{{__('translate.Name')}} {{-- الاسم --}} * </label>
                                    <input class="form-control" type="text" name="name" value="{{ $user->name }}" required @if (auth()->user()->u_role_id == 2) readonly @endif>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label class="form-label">{{__('translate.Username')}} {{-- اسم المستخدم --}} * </label>
                                    <input class="form-control" type="text" name="u_username" id="username" value="{{ $user->u_username }}" required @if (auth()->user()->u_role_id == 2) readonly @endif @if ($role_id->r_id != 7 && $role_id->r_id != 6 && $role_id->r_id != 1) onchange="ppu_edu_ps()" @endif>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label class="form-label">{{__('translate.Email')}} {{-- البريد الإلكتروني --}} * </label>
                                    <input class="form-control" type="email" id="email" name="email" value="{{ $user->email }}" required @if (auth()->user()->u_role_id == 2) readonly @endif>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label class="form-label">{{__('translate.Birth Date')}} {{-- تاريخ الميلاد --}} * </label>
                                    <input class="form-control" type="date" name="u_date_of_birth" value="{{ $user->u_date_of_birth }}" @if (auth()->user()->u_role_id == 2) readonly @endif>
                                </div>
                            </div>
                            @if (auth()->user()->u_role_id != 2) {{-- If the user is student don't able to change or display his password --}}
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label class="form-label">{{__('translate.Password')}} {{-- كلمة المرور --}} * </label>
                                        <input class="form-control" type="password" name="password" placeholder="...............">
                                    </div>
                                </div>
                            @endif
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label class="form-label">{{__('translate.Phone Number')}} {{-- رقم الجوال --}} * </label>
                                    <input class="form-control" type="text" name="u_phone1" value="{{ $user->u_phone1 }}" required @if (auth()->user()->u_role_id == 2) readonly @endif pattern="[0-9]{10}" minlength="10" maxlength="10">
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label class="form-label">{{__('translate.Alternative Phone Number')}} {{-- رقم جوال احتياطي --}}</label>
                                    <input class="form-control" type="text" name="u_phone2" value="{{ $user->u_phone2 }}" @if (auth()->user()->u_role_id == 2) readonly @endif pattern="[0-9]{10}" minlength="10" maxlength="10">
                                </div>
                            </div>
                            @if (auth()->user()->u_role_id != 2)
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <div class="media">
                                            <label class="col-form-label m-r-10">{{__('translate.Activate or Deactivate Account')}} {{-- تفعيل أو تعطيل الحساب --}}</label>
                                            <div class="media-body text-end">
                                            <label class="switch">
                                                <input type="checkbox" name="u_status" @if($user->u_status == 1) checked @endif><span class="switch-state"></span>
                                            </label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endif
                            <div class="col-md-3">
                                <label class="form-label">{{__('translate.Gender')}} * </label>
                                @if (auth()->user()->u_role_id == 2)
                                    @if ($user->u_gender == 0)
                                        <input class="form-control" type="text" value="{{__('translate.Male')}}" readonly> {{-- ذكر --}}
                                    @else
                                        <input class="form-control" type="text" value="{{__('translate.Female')}}" readonly> {{-- أنثى --}}
                                    @endif
                                    <div class="form-group m-t-15 custom-radio-ml">
                                @else
                                        <div class="form-group m-t-15 custom-radio-ml">
                                        <div class="radio radio-primary">
                                            <input id="radio1" type="radio" name="u_gender" value="0" {{ $user->u_gender == 0 ? 'checked' : '' }}>
                                            <label for="radio1" style="padding-right: 2px">{{__('translate.Male')}} {{-- ذكر --}}</label>
                                            <input id="radio2" type="radio" name="u_gender" value="1" style="margin: 10px;" {{ $user->u_gender == 1 ? 'checked' : '' }}>
                                            <label for="radio2" style="padding-right: 2px">{{__('translate.Female')}} {{-- أنثى --}}</label>
                                        </div>
                                @endif
                                    </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label class="form-label">{{__('translate.Residential Address')}} {{-- عنوان السكن --}}</label>
                                    <input class="form-control" type="text" name="u_address" value="{{ $user->u_address }}" @if (auth()->user()->u_role_id == 2) readonly @endif>
                                </div>
                            </div>
                            @if(auth()->user()->u_role_id != 2)
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label for="">{{ __('translate.address_details') }}</label>
                                        <input type="text" value="{{ $user->u_address_details }}" placeholder="عنوان السكن التفصيلي" class="form-control" name="u_address_details">
                                    </div>
                                </div>
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label for="">{{ __('translate.city') }}</label>
                                                    <select class="form-control" name="u_city_id" id="">
                                                        @foreach($cities as $key)
                                                            <option @if($key->id == $user->u_city_id) selected @endif value="{{ $key->id }}">
                                                                @if(app()->isLocale('en') || (app()->isLocale('ar') && empty($key->city_name_en)))
                                                                    {{ $key->city_name_en }}
                                                                @else
                                                                    {{ $key->city_name_ar }}
                                                                @endif
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label for="">{{ __('translate.tawjihi_rate') }}</label>
                                                    <input type="text" placeholder="{{ __('translate.tawjihi_rate') }}" class="form-control" value="{{ $user->u_tawjihi_gpa }}" name="u_tawjihi_gpa">
                                                </div>
                                            </div>
                            @endif
                            <div class="col-md-3">
                                <div class="form-group">
                                    @if ($user->u_role_id == 2)
                                        <label class="form-label">{{__('translate.Major')}} {{-- التخصص --}}</label>
                                        @if (auth()->user()->u_role_id == 2)
                                            <input class="form-control" type="text" value="{{$major_id->m_name}}" readonly>
                                        @else
                                            <select name="u_major_id" class="js-example-basic-single col-sm-12 form-control">
                                                <option value="{{$major_id->m_id}}">{{$major_id->m_name}}</option>
                                                @foreach ($majors as $major)
                                                    <option value="{{$major->m_id}}">{{$major->m_name}}</option>
                                                @endforeach
                                            </select>
                                        @endif
                                    @endif
                                </div>
                            </div>
                            @if (auth()->user()->u_role_id != 2)
                                <div class="card-footer text-end">
                                    <input type="hidden" name="u_role_id" value="{{$role_id->r_id}}">
                                    <button class="btn btn-primary" type="submit">{{__('translate.Save Changes')}} {{-- حفظ التعديلات --}}</button>
                                </div>
                            @endif
                        </div>
                    </div>
            </div>
        </div>
    </div>
</div>
@endsection
@section('script')
    <script>
        function ppu_edu_ps()
        {
            let email = document.getElementById('email');
            let username = document.getElementById('username');
            email.value = username.value + "@ppu.edu.ps";
        }
    </script>

@endsection
