@extends('layouts.app')
@section('title')
    تفاصيل التقييم
@endsection
@section('header_title')
    تفاصيل التقييم
@endsection
@section('header_title_link')
    تفاصيل التقييم
@endsection
@section('header_link')
    تفاصيل التقييم
@endsection
@section('style')
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/css/select2.css') }}">
    <style>
        /* Add a basic loading spinner */
        .loading {
            display: none;
            text-align: center;
            font-size: 18px;
        }
    </style>
@endsection
@section('content')
    <div class="row mb-2">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <!-- Student Name Filter -->
                        <div class="col-md-3">
                            <div class="form-group">
                                <input type="text" id="student_name" onkeyup="list_evaluation_details_list()"
                                    class="form-control" placeholder="بحث عن الطالب">
                            </div>
                        </div>
                        <!-- Course Filter -->
                        <div class="col-md-3">
                            <div class="form-group">
                                <select class="form-control" onchange="list_evaluation_details_list()" name=""
                                    id="course_id">
                                    <option value="">جميع التدريبات</option>
                                    @foreach ($semesters as $key)
                                        <option value="{{ $key->sc_id }}">{{ $key->courses->c_name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <!-- Supervisor Filter -->
                        <div class="col-md-3">
                            <div class="form-group">
                                <select class="form-control" onchange="list_evaluation_details_list()" name=""
                                    id="supervisor_id">
                                    <option value="">جميع المشرفين</option>
                                    @foreach ($supervisors as $key)
                                        <option value="{{ $key->u_id }}">{{ $key->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <!-- Company Filter -->
                        <div class="col-md-3">
                            <div class="form-group">
                                <select class="form-control" onchange="list_evaluation_details_list()" name=""
                                    id="company_id">
                                    <option value="">جميع الشركات</option>
                                    @foreach ($companies as $key)
                                        <option value="{{ $key->c_id }}">{{ $key->c_name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-12">
                            <!-- Radio Buttons -->
                            <div class="form-group row m-t-15 custom-radio-ml">
                                <div class="radio col-md-3 radio-primary">
                                    <input id="radio0" type="radio" name="radio" value="">
                                    <label for="radio0">الجميع</label>
                                </div>
                                <div class="radio col-md-3 radio-primary">
                                    <input id="radio1" type="radio" name="radio" value="company">
                                    <label for="radio1">لم يتم تقييم من قبل الشركة</label>
                                </div>
                                <div class="radio col-md-3 radio-primary">
                                    <input id="radio2" type="radio" name="radio" value="university">
                                    <label for="radio2">لم يتم تقييم من قبل الجامعة</label>
                                </div>
                                <!-- Excel Export Form -->
                                <div class="col-md-3">
                                    <form action="{{ route('students.evaluation.export_excel') }}" method="post"
                                        id="exportForm">
                                        @csrf
                                        <input type="hidden" name="evaluation_id" value="{{ $data->e_id }}">
                                        <input type="hidden" name="student_name">
                                        <input type="hidden" name="course_id">
                                        <input type="hidden" name="supervisor_id">
                                        <input type="hidden" name="company_id">
                                        <input type="hidden" name="selectedRadio">
                                        <button class="btn btn-sm btn-success" type="submit">استيراد اكسيل</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Loading Spinner -->
                    <div class="row">
                        <div class="col-md-12">
                            <div class="loading">Loading...</div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>

    <!-- Evaluation Details Table -->
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="table-responsive" id="evaluation_deatils_table"></div>
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
        $(document).ready(function() {
            // Call function to load evaluation details on page load
            list_evaluation_details_list();
        });

        // Radio button change event
        $('input[type=radio][name=radio]').change(function() {
            list_evaluation_details_list();
        });

        // Function to list evaluation details with AJAX
        function list_evaluation_details_list() {
            let selectedRadio = $('input[name=radio]:checked').val();
            $('.loading').show(); // Show loading spinner
            $.ajax({
                url: '{{ route('admin.evaluations.list_evaluation_details_list') }}',
                datatype: "json",
                type: "post",
                data: {
                    "_token": "{{ csrf_token() }}",
                    "student_name": $('#student_name').val(),
                    "course_id": $('#course_id').val(),
                    "supervisor_id": $('#supervisor_id').val(),
                    "company_id": $('#company_id').val(),
                    "selectedRadio": selectedRadio,
                },
                success: function(response) {
                    $('#evaluation_deatils_table').html(response.view);
                    $('.loading').hide(); // Hide loading spinner
                },
                error: function(error) {
                    alert('An error occurred while fetching the data.');
                    $('.loading').hide(); // Hide loading spinner
                }
            });
        }

        $('#exportForm').submit(function() {
            $('input[name="student_name"]').val($('#student_name').val());
            $('input[name="course_id"]').val($('#course_id').val());
            $('input[name="supervisor_id"]').val($('#supervisor_id').val());
            $('input[name="company_id"]').val($('#company_id').val());
            $('input[name="selectedRadio"]').val($('input[name=radio]:checked').val());
        });

        function edit_total_score(r_id, total_score) {
            if(total_score > 100 || total_score < 0){
                alert('لا يمكن ادخال اكبر من 100 او اقل من 0');
                return false;
            }
            if(isNaN(total_score)){
    alert('الرجاء إدخال قيمة رقمية فقط');
    return false;
}
            $.ajax({
                url: '{{ route('admin.evaluations.edit_total_score') }}',
                datatype: "json",
                type: "post",
                data: {
                    "_token": "{{ csrf_token() }}",
                    'r_id': r_id,
                    'total_score': total_score
                },
                success: function(response) {
                    $('#evaluation_deatils_table').html(response.view);
                    $('.loading').hide(); // Hide loading spinner
                },
                error: function(error) {
                    alert('An error occurred while fetching the data.');
                    $('.loading').hide(); // Hide loading spinner
                }
            });
        }
    </script>
@endsection
