@extends('layouts.app')
@section('title')
    التقارير النهائية
@endsection
@section('header_title')
التقارير النهائية
@endsection
@section('header_title_link')
التقارير النهائية
@endsection
@section('header_link')
    <a href="{{ route('supervisors.companies.index') }}">{{ __('translate.Training Places') }} {{-- أماكن التدريب --}}</a>
@endsection
@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="table-responsive">
                                <div id="final_reports_table">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('script')
<script>
    $(document).ready(function () {
        final_reports_list_ajax();
    });
    function final_reports_list_ajax() {
        $.ajax({
            url: "{{route('training_supervisor.final_reports.final_reports_list_ajax')}}",
            method: 'post',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            data: {
                // 'student_name' : $('#student_name').val(),
                // 'company_name' : $('#company_name').val(),
            },
            success: function(response) {
                $('#final_reports_table').html(response.view);
            },
            error: function (error) {
                alert(error);
            }
        });
    }
</script>
@endsection
