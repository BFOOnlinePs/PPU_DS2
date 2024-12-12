@if ($data->isEmpty())
    <h6 class="alert alert-danger">{{__('translate.No Majors Added to This Supervisor')}}{{--لا يوجد تخصصات مضافة لهذا المشرف لعرضها--}}</h6>
@else
    <table class="table table-bordered table-striped">
        <thead>
            <tr>
                <th>{{__('translate.Major')}} {{-- التخصص --}}</th>
                <th>{{__('translate.Display Major Students')}} {{-- عرض طلاب التخصص --}}</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($data as $key)
                <tr>
                    <td>{{$key->majors->m_name}}</td>
                    <td><a href="{{route('supervisor_assistants.students.index' , ['ms_major_id' => $key->ms_major_id])}}" class="btn btn-primary btn-xs"><span class="fa fa-users"></span></a></td>
                </tr>
            @endforeach
        </tbody>
    </table>
@endif

