<table class="table table-bordered table-striped">
    <thead>
        <tr>
            <th>{{__("translate.Student Name")}} {{-- اسم الطالب --}}</th>
            <th>{{__('translate.Branch')}} {{-- الفرع --}}</th>
            <th>{{__('translate.Section')}} {{-- القسم --}}</th>
        </tr>
    </thead>
    <tbody>
        @if ($students->isEmpty())
            <tr>
                <td colspan="3" class="text-center"><span>{{__('translate.No Trainee Students in this Company')}}{{-- لا يوجد طلاب متدربين في هذه الشركة --}}</span></td>
            </tr>
        @else
            @foreach($students as $student)
                <tr>
                    {{-- <td>{{$student->users->name}}</td> --}}
                    <td><a href="{{route('admin.users.details',['id'=>$student->users->u_id])}}">{{$student->users->name}}</a></td>

                    <td>
                        @if (isset($student->companyBranch->b_address))
                            {{$student->companyBranch->b_address}}
                        @else
                            {{__('translate.Branch is unspecified')}} {{-- الفرع غير محدد --}}
                        @endif
                    </td>
                    <td>
                        @if (isset($student->companyDepartment->d_name))
                            {{$student->companyDepartment->d_name}}
                        @else
                            {{__('translate.Section is unspecified')}} {{-- القسم غير محدد --}}
                        @endif
                    </td>
                </tr>
            @endforeach
        @endif
    </tbody>
</table>
