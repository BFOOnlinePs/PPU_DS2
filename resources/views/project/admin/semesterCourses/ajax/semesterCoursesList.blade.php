<div id="showTable">
    <div class="table-responsive">
        <table class="table table-bordered table-striped">
            <thead>
                <tr>
                    <th scope="col" style="display:none;">id</th>
                    <th scope="col"> {{__('translate.Academic Year')}}{{-- العام الدراسي --}}</th>
                    <th scope="col">{{__('translate.Semester')}}{{-- الفصل الدراسي--}}</th>
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
                    <td colspan="7" class="text-center"><span>{{__('translate.No available data')}}{{--لا توجد بيانات--}}</span></td>
                </tr>
            @else
                @foreach ($data as $key)
                    <tr>
                        <td style="display:none;">{{ $key->sc_id }}</td>
                        <td>{{ $key->sc_year }}</td>
                        @if( $key->sc_semester == 1) <td>{{__('translate.First')}}{{-- أول --}}</td>@endif
                        @if( $key->sc_semester == 2) <td>ثاني</td>@endif
                        @if( $key->sc_semester == 3) <td>{{__('translate.Summer')}}{{-- صيفي --}}</td>@endif
                        <td>{{ $key->courses->c_name }}</td>

                        <td>{{ $key->courses->c_course_code }}</td>
                        <td>{{ $key->courses->c_hours }}</td>
                        @if( $key->courses->c_course_type == 0) <td>{{__('translate.Theoretical')}} {{-- نظري --}}</td>@endif
                        @if( $key->courses->c_course_type == 1) <td>{{__('translate.Practical')}} {{-- عملي --}}</td>@endif
                        @if( $key->courses->c_course_type == 2) <td>{{__('translate.Theoretical - Practical')}} {{-- نظري - عملي --}}</td>@endif

                        <td>
                            @if($key->sc_semester == $semester && $key->sc_year == $year)
                                    <button class="btn btn-danger" onclick="showDeleteCourseModal({{ $key }})"><i class="fa fa-trash"></i></button>
                            @endif
                        </td>
                    </tr>
                @endforeach
            @endif

        </tbody>
        </table>
    </div>
</div>
