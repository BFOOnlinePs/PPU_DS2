@extends('layouts.app')
@section('title')
    {{__('translate.student_nominations')}} {{-- سِجل الحضور و المغادرة --}}
@endsection
@section('header_title')
    {{__('translate.student_nominations')}} {{-- سِجل الحضور و المغادرة --}}
@endsection
@section('header_title_link')
    <a href="{{route('home')}}">{{__('translate.Main')}}{{-- الرئيسية --}}</a>
@endsection
@section('header_link')
    <a href="{{route('company_manager.records.index')}}">{{__('translate.student_nominations')}} {{-- سِجل الحضور و المغادرة --}}</a>
@endsection
@section('content')
    <div class="container-fluid">
        <div class="card">
            <div class="card-header pb-0">

            </div>
            <div class="row card-body">
                <div id="student_nomination_table">

                </div>
            </div>
        </div>
    </div>
@endsection
@section('script')
    <script>

        $(document).ready(function() {
            student_nomination_table();
            // student_nomination_table();
        });
        function student_nomination_table()
        {
            $.ajax({
                url: "{{ route('company_manager.student_nominations.student_nomination_table') }}",
                method: 'post',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                data: {

                },
                success: function (data) {
                    console.log(data);
                    $('#student_nomination_table').html(data.view);
                },
                error: function (xhr, status, error) {
                    document.getElementById('loading').style.display = 'none';
                }
            });
        }
    </script>
@endsection

