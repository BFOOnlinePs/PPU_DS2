@extends('layouts.app')
@section('title')
    تفاصيل الزيارة
@endsection
@section('header_title')
    تفاصيل الزيارة
@endsection
@section('header_title_link')
    تفاصيل الزيارة
@endsection
@section('header_link')
    <a href="{{ route('supervisors.companies.index') }}">{{__('translate.Training Places')}} {{-- أماكن التدريب --}}</a>
@endsection
@section('style')
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/select2.css') }}">
@endsection
@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                @foreach($data->student_names as $key)
                                    <span class="badge bg-primary">{{ $key }}</span>
                                @endforeach
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="">اسم الشركة</label>
                                <input readonly type="text" value="{{ $data->company->c_name }}" class="form-control">
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="">اسم مشرف التدريب العملي</label>
                                <input readonly type="text" value="{{ $data->supervisor->name }}" class="form-control">
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="">مكان الزيارة</label>
                                <input readonly type="text" value="{{ $data->fv_visiting_place }}" class="form-control">
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="">مدة الزيارة</label>
                                <input readonly type="text" value="{{ $data->fv_visit_duration }}" class="form-control">
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="">وقت الزيارة</label>
                                <input readonly type="text" value="{{ $data->fv_vistit_time }}" class="form-control">
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="">ملاحظات</label>
                                <textarea readonly class="form-control" name="" id="" cols="30" rows="3">{{ $data->fv_notes }}</textarea>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('script')
    <script src="{{ asset('assets/js/select2/select2.full.min.js') }}"></script>
    <script src="{{ asset('assets/js/select2/select2-custom.js') }}"></script>

    <script>
        function get_student_from_company(company_id) {
            $.ajax({
                url: "{{route('training_supervisor.field_visits.get_student_from_company')}}",
                method: 'post',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                data: {
                    company_id : company_id
                },
                success: function(response) {
                    if (response.status === 'not_empty') {
                        $('#students_container').empty(); // Clear previous checkboxes
                        $('#data_container').css('visibility', 'visible');
                        console.log(response.data);
                        (response.data).forEach(function(student , index) {
                            var checkboxHtml = `
                        <div class="col-md-3">
                            <input class="" type="checkbox" value="${student.users.u_id}" name="student[]" id="student_${index}">
                            <label class="" for="student_${index}">
                                ${student.users.name}
                            </label>
                        </div>
                    `;
                            $('#students_container').append(checkboxHtml);
                        });
                    } else {
                        $('#data_container').css('visibility', 'hidden');
                    }
                },
                error: function() {
                    alert('Error fetching user data.');
                }
            });
        }
    </script>
@endsection
