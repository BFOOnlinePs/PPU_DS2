@extends('layouts.app')
@section('title')
    اضافة مراسلة
@endsection
@section('header_title')
    اضافة مراسلة
@endsection
@section('header_title_link')
    اضافة مراسلة
@endsection
@section('header_link')
    <a href="{{ route('supervisors.companies.index') }}">{{__('translate.Training Places')}} {{-- أماكن التدريب --}}</a>
@endsection
@section('content')
        <form action="{{ route('training_supervisor.conversation.create') }}" class="row card" method="post">
            @csrf
            <div class="card-body">
                <div class="row">
                    <div class="col-md-12">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label for="">الى المستخدم</label>
                                        <select class="form-control" name="uc_user_id" id="">
                                            <option value="">اختر شخص ...</option>
                                            @foreach($users as $key)
                                                <option value="{{ $key->u_id }}">{{ $key->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label for="">عنوان المراسلة</label>
                                        <input type="text" name="c_name" class="form-control" placeholder="عنوان المراسلة">
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label for="">نص المراسلة</label>
                                        <textarea name="m_message_text" id="" class="form-control" cols="30" rows="3"></textarea>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
{{--                    <div class="col-md-4 text-center">--}}
{{--                        <div class="row">--}}
{{--                            <div class="col-md-12">--}}
{{--                                <span style="font-size: 200px" class="fa fa-comment"></span>--}}
{{--                            </div>--}}
{{--                        </div>--}}
{{--                    </div>--}}
                    <div class="col-md-12">
                        <button class="btn btn-primary">حفظ</button>
                    </div>
                </div>
            </div>
        </form>
@endsection
@section('script')
@endsection
