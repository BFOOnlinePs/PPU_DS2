<table class="table table-bordered table-striped">
    <thead>
        <tr>
            <th>{{__('translate.Day')}}{{-- اليوم --}}</th>
            <th>{{__('translate.Date')}}{{-- التاريخ --}}</th>
            <th>{{__('translate.Arrival Time')}} {{-- وقت الوصول --}}</th>
            <th>{{__('translate.Leaving Time')}} {{-- وقت المغادرة --}}</th>
        </tr>
    </thead>
    <tbody>
        @if($student_attendances->isEmpty())
            <tr>
                <td colspan="5" class="text-center"><span>{{__('translate.No available data')}}{{-- لا توجد بيانات --}}</span></td>
            </tr>
        @else
            @foreach($student_attendances as $student_attendance)
                <tr>
                    <td>{{strftime('%A', strtotime($student_attendance->sa_in_time))}}</td>
                    <td>{{date("Y-m-d", strtotime($student_attendance->sa_in_time))}}</td>
                    <td>{{$student_attendance->sa_in_time}}</td>
                    @if (!isset($student_attendance->sa_out_time))
                        <td>{{__("translate.Didn't Check-Out")}} {{-- لم يسجل مغادرة --}}</td>
                    @else
                        <td>{{$student_attendance->sa_out_time}}</td>
                    @endif
                </tr>
            @endforeach
        @endif
    </tbody>
</table>
