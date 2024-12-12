<div class="table-responsive">
    <table class="table table-bordered table-striped" id="coursesTable">
        <thead>
            <tr>
                <th scope="col" style="display:none;">id</th>
                <th scope="col">{{__('translate.Course Name')}} {{-- اسم التدريب العملي --}}</th>
                <th scope="col">{{__('translate.Course Code')}}{{-- رمز التدريب العملي --}}</th>
                <th scope="col">{{__('translate.Course Hours')}}{{-- ساعات التدريب العملي --}}</th>
                <th scope="col">{{__('translate.Course Type')}}{{-- نوع التدريب العملي --}}</th>
                <th scope="col">{{__('translate.Operations')}} {{--  العمليات --}}</th>

            </tr>
        </thead>
        <tbody>
            @if ($data->isEmpty())
                <tr>
                    <td colspan="5" class="text-center"><span>{{__('translate.No available data')}}{{--لا توجد بيانات--}}</span></td>
                </tr>
            @else
                @foreach($data as $key)
                    <tr data-id="{{ $key->c_id }}">
                        <td style="display:none;">{{ $key->c_id }}</td>
                        <td>{{ $key->c_name }}</td>
                        <td>{{ $key->c_course_code }}</td>
                        <td>{{ $key->c_hours }}</td>
                        @if( $key->c_course_type == 0) <td>{{__('translate.Theoretical')}} {{-- نظري --}}</td>@endif
                        @if( $key->c_course_type == 1) <td>{{__('translate.Practical')}} {{-- عملي --}}</td>@endif
                        @if( $key->c_course_type == 2) <td>{{__('translate.Theoretical - Practical')}} {{-- نظري - عملي --}}</td>@endif
                        <td id="table_buttons_{{$key->c_id}}">
                            <button class="btn btn-info" onclick="showCourseModal({{ $key }})"><i class="fa fa-info"></i></button>
                            <button class="btn btn-primary" onclick="showEditCourseModal({{ $key }})"><i class="fa fa-edit"></i></button>
                        </td>
                    </tr>
                @endforeach
            @endif
        </tbody>
    </table>
</div>
{{-- <script src="{{ asset('assets/js/icons/feather-icon/feather.min.js') }}"></script>
<script src="{{ asset('assets/js/icons/feather-icon/feather-icon.js') }}"></script> --}}
