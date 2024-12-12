@extends('layouts.app')
@section('title')
    تفاصيل المراسلة
@endsection
@section('header_title')
    تفاصيل المراسلة
@endsection
@section('header_title_link')
    تفاصيل المراسلة
@endsection
@section('header_link')
    <a href="{{ route('supervisors.companies.index') }}">{{__('translate.Training Places')}} {{-- أماكن التدريب --}}</a>
@endsection
@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#add_message_modal">اضافة رسالة</button>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
{{--                    <div class="row">--}}
{{--                        <div class="col-md-12">--}}
{{--                            <h5 class="text-center">                            الى المستخدم <span>{{ $receive->receive->name }}</span></h5>--}}
{{--                        </div>--}}
{{--                    </div>--}}
                    <div class="row">
                        @foreach($data as $key)
                            <div class="col-md-12 mb-3">
                                <p class="@if(auth()->user()->u_id == $key->m_sender_id) badge bg-primary float-start @else badge bg-dark float-end @endif">{{ $key->m_message_text }}</p>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
            @include('project.training_supervisor.conversation.modals.add_message')
        </div>
    </div>
@endsection
@section('script')
@endsection
