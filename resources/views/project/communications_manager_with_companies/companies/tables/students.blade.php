<table class="table table-bordered table-striped">
    <thead>
        <tr>
            <th>{{__("translate.Student Name")}} {{-- اسم الطالب --}}</th>
            <th>{{__('translate.Training Status')}}{{--حالة التدريب--}}</th>
            <th>{{__('translate.Display Student Information')}} {{-- عرض معلومات عن الطالب --}}</th>
        </tr>
    </thead>
    <tbody>
        @if ($students_company->isEmpty())
            <tr>
                <td colspan="3" class="text-center"><span>{{__('translate.No data to display')}}{{--لا توجد بيانات--}}</span></td>
            </tr>
        @else
            @foreach ($students_company as $student)
                <tr>
                    {{-- <td>{{$student->users->name}}</td> --}}
                    <td><a href="{{route('admin.users.details',['id'=>$student->users->u_id])}}">{{$student->users->name}}</a></td>
                    <td>
                        @if ($student->sc_status == 1)
                        {{__('translate.In-training')}} {{-- لا يزال يتدرب--}}
                        @elseif($student->sc_status == 2)
                        {{__('translate.Completed')}}{{-- انهى التدريب --}}
                        @else
                    {{__("translate.Deleted")}}  {{--محذوف--}}
                        @endif
                    </td>
                    <td><a href="{{route('admin.users.details' , ['id'=>$student->sc_student_id])}}" class="btn btn-primary btn-xs"><span class="fa fa-search"></span></a></td>
                </tr>
            @endforeach
        @endif
    </tbody>
</table>

