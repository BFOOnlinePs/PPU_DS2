<div id="registeredCoursesReportTable">
    <div class="table-responsive">
        <table class="table table-bordered table-striped">
            <thead>
                <tr>
                    <th scope="col" style="display:none;">id</th>
                    <th scope="col">{{__('translate.Course ID')}}{{--رقم المساق--}}</th>
                    <th scope="col">{{__('translate.Course Name')}}{{--اسم المساق--}}</th>
                    <th scope="col">{{__('translate.Course Type')}}{{--نوع المساق--}}</th>
                    <th scope="col">{{__('translate.total_enrolled_students')}}{{--إجمالي الطلاب المسجلين--}}</th>
                </tr>
            </thead>
            <tbody>
                @if ($data->isEmpty())
                <tr>
                    <td colspan="6" class="text-center"><span>{{__('translate.No available data')}} {{-- لا توجد بيانات  --}}</span></td>
                </tr>
                @else
                    @foreach ($data as $key)
                        <tr>
                            <td style="display:none;">{{ $key->r_id }}</td>
                            <td>{{ $key->courses->c_course_code }}</td>
                            <td>{{ $key->courses->c_name }}</td>
                            @if( $key->courses->c_course_type == 0) <td>{{__('translate.Theoretical')}} {{-- نظري --}}</td>@endif
                            @if( $key->courses->c_course_type == 1) <td>{{__('translate.Practical')}} {{-- عملي --}}</td>@endif
                            @if( $key->courses->c_course_type == 2) <td>{{__('translate.Theoretical - Practical')}} {{-- نظري - عملي --}}</td>@endif
                            <td>{{ $key->studentsNum }}</td>

                        </tr>
                    @endforeach
                @endif
            </tbody>
        </table>
    </div>
</div>
