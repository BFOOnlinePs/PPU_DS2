@extends('layouts.app')
@section('title')
    المراسلات
@endsection
@section('header_title')
    المراسلات
@endsection
@section('header_title_link')
    المراسلات
@endsection
@section('header_link')
    <a href="{{ route('supervisors.companies.index') }}">{{ __('translate.Training Places') }} {{-- أماكن التدريب --}}</a>
@endsection
@section('content')
    {{-- <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    <a href="{{ route('training_supervisor.conversation.add') }}" class="btn btn-primary btn-sm">اضافة مراسلة جديدة</a>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    <table class="table table-sm table-hover">
                        <thead>
                            <tr>
                                <th>عنوان الرسالة</th>
                                <th>العمليات</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if ($data->isEmpty())
                                <tr>
                                    <td colspan="3" class="text-center">لا توجد مراسلات</td>
                                </tr>
                            @else
                                @foreach ($data as $key)
                                    <tr>
                                        <td>{{ $key->c_name }}</td>
                                        <td>
                                            <a href="{{ route('training_supervisor.conversation.details',['id'=>$key->c_id]) }}" class="btn btn-primary btn-sm"><span class="fa fa-search"></span></a>
                                        </td>
                                    </tr>
                                @endforeach
                            @endif
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div> --}}
    <input type="hidden" id="conversation_id" value="">

    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-12">
                                <button class="btn btn-primary" type="button" data-bs-toggle="modal" data-bs-target="#add_conversation_modal">انشاء رسالة جديدة</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="call-chat-sidebar col">
                <div class="card">
                    <div class="chat-body card-body">
                        <div class="chat-box">
                            <div class="chat-left-aside">
                                <div class="media"><img src="{{ asset('public/assets/images/avtar/profile.png') }}" alt=""
                                        class="rounded-circle user-image media">
                                    <div class="about"><a href="/viho/app/users/userprofile">
                                            <div class="name f-w-600 f-12">{{ auth()->user()->name }}</div>
                                        </a>
                                        <div class="status">{{ auth()->user()->email }}</div>
                                    </div>
                                </div>
                                <div class="people-list" id="people-list">
                                    <div class="search">
                                        <form class="theme-form">
                                            <div class="form-group mb-3"><input
                                                id="c_name"
                                                onkeyup="list_conversations()"
                                                placeholder="بحث" type="text"
                                                    class="form-control form-control" value=""><i
                                                    class="fa fa-search"></i></div>
                                                    <div id="list_conversation_ajax">

                                                    </div>
                                        </form>
                                    </div>

                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="call-chat-body col" id="list_message_ajax">

            </div>
        </div>
        @include('project.training_supervisor.conversation.modals.add_conversation')
    </div>
@endsection
@section('script')

<script>
    $(document).ready(function () {
        list_conversations();
        scrollToBottom();
        // list_message_ajax();
    });
    function list_conversations() {
        $('#list_conversation_ajax').html('<div class="loader-box"><div class="loader-3"></div></div>');
        $.ajax({
            url: "{{route('training_supervisor.conversation.list_conversations')}}",
            method: 'post',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            data: {
                'c_name' : $('#c_name').val()
            },
            success: function(response) {
                $('#list_conversation_ajax').html(response.view);

            },
            error: function (error) {
                alert(error);
            }
        });
    }

    function list_message_ajax(c_id){
        $('#conversation_id').val(c_id); // تعيين القيمة في الحقل المخفي
        // $('#list_message_ajax').html('<div class="loader-box"><div class="loader-3"></div></div>');
        $.ajax({
            url: "{{route('training_supervisor.conversation.list_message_ajax')}}",
            method: 'post',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            data: {
                'c_id' : c_id
            },
            success: function(response) {
                console.log($('.custom-scrollbar')[0].scrollHeight);
                $('#list_message_ajax').html(response.view);
                $('#mohamadmaraqa').focus();
                // $('.custom-scrollbar').scrollTop($('.custom-scrollbar')[0].scrollHeight);
                scrollToBottom();
            },
            error: function (error) {
                alert(error);
            }
        });
    }

function scrollToBottom() {
    let chatBox = $('.chat-msg-box');
    chatBox.scrollTop(chatBox[0].scrollHeight);
}
</script>
@endsection
