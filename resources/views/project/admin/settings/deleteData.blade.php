@extends('layouts.app')
@section('title')
{{__('translate.Delete Data')}}{{-- حذف بيانات --}}
@endsection
@section('header_title')
{{__('translate.Delete Data')}}{{-- حذف بيانات --}}
@endsection
@section('header_title_link')
<a href="{{route('home')}}">{{__('translate.Main')}}{{-- الرئيسية --}}</a>
@endsection
@section('header_link')
<a href="{{route('admin.settings')}}">{{__('translate.Settings')}}{{-- إعدادات  --}}</a>
@endsection
@section('style')
@endsection
@section('content')
    <div class="card">
        <div class="card-body">
            <div id="messages">
            </div>
            <br>
            <div class="row">
                <div class="col-md-3">
                    <div class="form-group">
                        <label class="form-label">{{__('translate.From:')}}{{--من تاريخ--}}</label>
                        <input type="date" class="form-control"  id="from">
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label class="form-label">{{__('translate.To:')}}{{--إلى تاريخ--}}</label>
                        <input type="date" class="form-control" id="to">
                    </div>
                </div>
            </div>
            <button class="btn btn-danger" onclick="show_modal_delete()">{{__('translate.Delete')}}{{--حذف--}}</button>
        </div>
        @include('project.admin.settings.includes.alertToDeleteData')
        @include('layouts.loader')
    </div>
@endsection
@section('script')
    <script>
        function show_modal_delete()
        {
            $('#confirmDeleteModal').modal('show');
        }
        function confirmDelete()
        {
            let from = document.getElementById('from').value;
            let to = document.getElementById('to').value;
            $.ajax({
                beforeSend: function() {
                    $('#confirmDeleteModal').modal('hide');
                    $('#LoadingModal').modal('show');
                },
                url: "{{ route('admin.settings.confirmDelete') }}",
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                data:{
                    'from': from,
                    'to': to
                },
                success: function (response) {
                    if(response.status == 1) {
                        document.getElementById('messages').innerHTML = `
                            <div class="alert alert-success">
                                {{__('translate.Deleted Successfully')}}
                            </div>
                        `;
                    }
                    else {
                        document.getElementById('messages').innerHTML = `
                            <div class="alert alert-danger">
                                {{__("translate.No data between these dates, didn't delete any data")}}
                            </div>
                        `;
                    }
                    $('#LoadingModal').modal('hide');
                },
                error: function (error) {
                    $('#LoadingModal').modal('hide');
                }
            });
        }
    </script>
@endsection
