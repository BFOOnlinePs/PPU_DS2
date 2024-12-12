@extends('layouts.app')
@section('title')
    اضافة زيارة
@endsection
@section('header_title')
    الزيارات
@endsection
@section('header_title_link')
    الزيارات
@endsection
@section('header_link')
    <a href="{{ route('supervisors.companies.index') }}">{{__('translate.Training Places')}} {{-- أماكن التدريب --}}</a>
@endsection
@section('style')
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/select2.css') }}">
@endsection
@section('content')
    <div class="row">
        <form action="{{ route('training_supervisor.field_visits.create') }}" method="post">
            @csrf
            <div class="col-md-12">
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="">الشركة</label>
                                    <select onchange="get_student_from_company(this.value)" class="form-control js-example-basic-single" required name="fv_company_id" id="">
                                        <option value="">اختر شركة ...</option>
                                        @foreach($company as $key)
                                            <option value="{{ $key->c_id }}">{{ $key->c_name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div id="data_container" style="visibility: hidden">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label for="">الطلاب المسجلين</label>
                                        <div id="students_container" class="row"></div>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label for="">مكان الزيارة</label>
                                        <textarea name="fv_visiting_place" id="" cols="30" rows="2" class="form-control" placeholder="مكان الزيارة"></textarea>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label for="">مدة الزيارة</label>
                                        <select class="form-control" required name="fv_visit_duration" id="">
                                            <option value="">اختر مدة الدوام ...</option>
                                            <option value="1-15">1 - 15</option>
                                            <option value="15-30">15 - 30</option>
                                            <option value="30-45">30 - 45</option>
                                            <option value="45-60">45 - 60</option>
                                            <option value="60-120">60 - 120</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label for="">ملاحظات</label>
                                        <textarea class="form-control" name="fv_notes" id="" cols="30" rows="3"></textarea>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <button class="btn btn-primary">حفظ</button>
                    </div>
                </div>
            </div>
        </form>
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
