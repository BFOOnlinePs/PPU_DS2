@extends('layouts.app')
@section('title')
{{__('translate.Payments')}} {{-- الدفعات  --}}
@endsection
@section('header_title')
{{__('translate.Payments')}} {{--  الدفعات --}}
@endsection
@section('header_title_link')
<a href="{{route('home')}}">{{__('translate.Main')}}{{-- الرئيسية --}}</a>
@endsection
@section('header_link')
<a href="{{route('students.payments.index')}}">{{__('translate.Payments')}}{{-- الدفعات  --}}</a>
@endsection
@section('content')
    <div class="container-fluid">
        <div id="content">
            @include('project.student.payments.includes.paymentsList')
        </div>
        @include('project.student.payments.includes.alertToConfirmPayment')
        @include('project.student.payments.modals.notes')
        @include('project.admin.users.modals.loading')
    </div>
@endsection
@section('script')
    <script>
        function showAlert(element , title) {
            document.getElementById('header').innerHTML = title;
            document.getElementById('explain').innerHTML = `{{__('translate.In this section, you can display')}} ${title}`;
            document.getElementById('textinput').textContent= element.getAttribute('title');
            document.getElementById('notes').innerHTML = title;
            $('#NotesModal').modal('show');
        }
        function showModal(value)
        {
            $('#confirmPaymentModal').modal('show');
            document.getElementById('p_id').value = value;
        }
        function confirmPayment(value)
        {
            let p_id = document.getElementById('p_id').value;
            $.ajax({
                url: "{{route('student.payments.confirmByAjax')}}",
                method: 'post',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                beforeSend: function() {
                    $('#confirmPaymentModal').modal('hide');
                    $('#LoadingModal').modal('show');
                },
                data: {
                    'p_id': p_id
                },
                success: function(response) {
                    let btn = document.getElementById(`confirm_payment_btn_${p_id}`);
                    btn.setAttribute('disabled', true);
                    $('#content').html(response.html);
                },
                complete: function(){
                    $('#LoadingModal').modal('hide');
                },
                error: function(xhr, status, error) {
                    // Display an alert with the error message
                    alert('Error: ' + error);
                }
            });
        }
    </script>
@endsection
