<table class="table table-bordered table-striped">
    <thead>
        <tr>
            <th>{{__("translate.Student Name")}} {{-- اسم الطالب --}}</th>
            <th>{{__('translate.Arrival Time')}} {{-- وقت الوصول --}}</th>
            <th>{{__('translate.Leaving Time')}} {{-- وقت المغادرة --}}</th>
            <th>{{__('translate.Display Report')}} {{-- عرض التقرير --}}</th>
        </tr>
    </thead>
    <tbody>
        @if ($records->isEmpty())
        <tr>
            <td colspan="4" class="text-center"><span>{{__('translate.No data to display')}}{{--لا توجد بيانات--}}</span></td>
        </tr>
        @else
            @foreach($records as $record)
                <tr>
                    <td>{{$record->training->users->name}}</td>
                    <td>{{$record->sa_in_time}}</td>
                    @if (!isset($record->sa_out_time))
                        <td>{{__("translate.Didn't Check-Out")}}{{-- لم يُسجل مغادرة --}}</td>
                    @else
                        <td>{{$record->sa_out_time}}</td>
                    @endif
                    @if (isset($record->report->sr_id))
                        <td>
                            <button class="btn btn-primary" onclick="openReportModal('{{$record->report->sr_id}}')"><i class="fa fa-file-text"></i></button>
                        </td>
                    @else
                        <td>{{__("translate.Student didn't submit report")}}{{-- لم يُسلم الطالب تقرير --}}</td>
                    @endif
                </tr>
            @endforeach
        @endif
    </tbody>
</table>

