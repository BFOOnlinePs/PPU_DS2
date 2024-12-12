@extends('layouts.app')
@section('title')
    التقييمات
@endsection
@section('header_title')
    التقييمات
@endsection
@section('header_title_link')
    التقييمات
@endsection
@section('header_link')
    التقييمات
@endsection
@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <table class="table table-sm table-hover">
                            <thead>
                                <tr>
                                    <th>التقييم</th>
                                    <th>العنوان</th>
                                    {{--                                    <th>الحالة</th> --}}
                                    <th>بداية الوقت</th>
                                    <th>نهاية الوقت</th>
                                    <th>العمليات</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if ($data->isEmpty())
                                    <tr>
                                        <td colspan="5" class="text-center">لا توجد بيانات</td>
                                    </tr>
                                @else
                                    @foreach ($data as $key)
                                        <tr>
                                            <td>{{ $key->evaluation_type->et_type_name ?? '' }}</td>
                                            <td>{{ $key->e_title }}</td>
                                            {{--                                            <td>{{ $key->e_status }}</td> --}}
                                            <td>{{ $key->e_start_time }}</td>
                                            <td>{{ $key->e_end_time }}</td>
                                            <td>
                                                <a href="{{ route('students.evaluation.details', ['evaluation_id' => $key->e_id]) }}"
                                                    class="btn btn-sm btn-primary"><span class="fa fa-search"></span></a>
                                            </td>
                                        </tr>
                                    @endforeach
                                @endif
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('script')
    <script>
        function showAlert(element, title) {
            document.getElementById('header').innerHTML = title;
            document.getElementById('explain').innerHTML =
                `{{ __('translate.In this section, you can display') }} ${title}`;
            document.getElementById('textinput').textContent = element.getAttribute('title');
            document.getElementById('notes').innerHTML = title;
            $('#NotesModal').modal('show');
        }

        function showModal(value) {
            $('#confirmPaymentModal').modal('show');
            document.getElementById('p_id').value = value;
        }

        function confirmPayment(value) {
            let p_id = document.getElementById('p_id').value;
            $.ajax({
                url: "{{ route('student.payments.confirmByAjax') }}",
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
                complete: function() {
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
