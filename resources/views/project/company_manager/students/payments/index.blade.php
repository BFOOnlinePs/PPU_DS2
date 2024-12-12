@extends('layouts.app')
@section('title')
{{__("translate.Student's Payments Details")}} {{--تفاصيل دفعة الطالب--}}
@endsection
@section('header_title')
{{__("translate.Student's Payments Details")}} {{-- تفاصيل دفعة الطالب--}}
@endsection
@section('header_title_link')
<a href="{{route('home')}}">{{__('translate.Main')}}{{-- الرئيسية --}}</a>
@endsection
@section('header_link')
<a href="{{route('company_manager.students.index')}}">{{__('translate.Students')}}{{-- الطلاب  --}}</a>
@endsection
@section('style')
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/select2.css') }}">
@endsection
@section('header_link')
@endsection
@section('content')
    <div class="container-fluid">
        <div class="alert alert-success" id="success" style="display: none">
            تمت إضافة دفعة بنجاح
        </div>
        <div class="card">
            <div class="card-body">
                <button class="btn btn-primary  mb-2 btn-s" onclick="$('#AddPaymentModal').modal('show')" type="button" id="button_add_payment"><span class="fa fa-plus"></span> {{__('translate.Add Payment')}}{{-- إضافة دفعة --}}</button>
                <div id="content">
                    @include('project.company_manager.students.payments.ajax.paymentsList')
                </div>
            </div>
        </div>
        @include('project.company_manager.students.payments.modals.paymentModal')
        @include('project.admin.users.modals.loading')
    </div>
@endsection
@section('script')
    <script src="{{ asset('assets/js/select2/select2.full.min.js') }}"></script>
    <script src="{{ asset('assets/js/select2/select2-custom.js') }}"></script>
    <script>
        $('#addPaymentsForm').submit(function(e) {
            e.preventDefault();
            add_payment();
        });
        function add_payment() {
            let file = document.getElementById('p_file').files[0];
            let formData = new FormData();
            formData.append('student_company_id' , document.getElementById('student_company_id').value);
            formData.append('p_student_id', document.getElementById('p_student_id').value);
            formData.append('p_payment_value', document.getElementById('p_payment_value').value);
            formData.append('p_currency_id', document.getElementById('p_currency_id').value);
            formData.append('p_reference_id', document.getElementById('p_reference_id').value);
            formData.append('p_file', file);
            formData.append('p_company_notes', document.getElementById('p_company_notes').value);
            // Make an AJAX request to submit the file
            $.ajax({
                beforeSend: function(){
                    $('#AddUserModal').modal('hide');
                    $('#LoadingModal').modal('show');
                },
                url: "{{ route('company_manager.students.payments.create') }}",
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                data: formData,
                contentType: false,
                processData: false,
                success: function (response) {
                    $('#content').html(response.html);
                    document.getElementById('success').style.display = '';
                    setTimeout(() => {
                        document.getElementById('success').style.display = 'none';
                    }, 3000); // 3000 milliseconds = 3 seconds
                    $('#AddPaymentModal').modal('hide');
                },
                complete: function(){
                    $('#LoadingModal').modal('hide');
                },
                error: function (error) {
                    // Handle error, if needed
                    alert(error.responseText);
                    document.getElementById('success').style.display = 'none';
                }
            });
        }
    </script>
@endsection

