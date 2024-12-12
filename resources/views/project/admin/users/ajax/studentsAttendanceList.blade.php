    <table class="table table-bordered table-striped">
        <thead>
            <tr>
                <th>{{__('translate.Company Name')}} {{-- اسم الشركة --}}</th>
                <th>{{__('translate.Arrival Time')}} {{-- وقت الوصول --}}</th>
                <th>{{__('translate.Leaving Time')}} {{-- وقت المغادرة --}}</th>
                <th>{{__('translate.Details')}} {{-- التفاصيل --}}</th>
            </tr>
        </thead>
        <tbody>
            @if ($student_attendances->isEmpty())
                <tr>
                    <td colspan="4" class="text-center"><span>{{__('translate.No data to display')}}{{-- لا يوجد سجلات لعرضها --}}</span></td>
                </tr>
            @else
            @foreach($student_attendances as $student_attendance)
                <tr>
                    <td><a href="{{route("admin.companies.edit",['id'=>$student_attendance->training->company->c_id])}}">{{$student_attendance->training->company->c_name}}</a></td>
                    <td>{{$student_attendance->sa_in_time}}</td>
                    @if (!isset($student_attendance->sa_out_time))
                        <td>{{__("translate.Didn't Check-Out")}} {{-- لم يسجل مغادرة --}}</td>
                    @else
                        <td>{{$student_attendance->sa_out_time}}</td>
                    @endif
                    <td>
                        <button class="btn btn-primary fa fa-map-marker" onclick="map({{$student_attendance->sa_start_time_latitude}} , {{$student_attendance->sa_start_time_longitude}} , {{$student_attendance->sa_end_time_latitude}} , {{$student_attendance->sa_end_time_longitude}})" type="button" data-bs-toggle="tooltip" data-bs-placement="top" title="" data-bs-original-title="{{__('translate.Display Student Attendance Location')}}"></button>{{--"عرض موقع الطالب عند تسجيل الحضور و المغادرة"--}}
                        @if (!isset($student_attendance->report->sr_student_attendance_id))
                            {{__("translate.Student didn't submit report")}} {{-- لم يُسلم الطالب تقرير --}}
                        @else
                            <button class="btn btn-primary fa fa-file-text" onclick="report({{$student_attendance->sa_id}})" type="button" data-bs-toggle="tooltip" data-bs-placement="top" title="" data-bs-original-title="{{__('translate.Display Student Report')}}"></button>{{-- عرض تقرير الطالب --}}
                        @endif
                    </td>
                </tr>
            @endforeach
            @endif
        </tbody>
    </table>


