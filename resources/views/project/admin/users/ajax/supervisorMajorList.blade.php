@if ($data->isEmpty())
    <h6 class="alert alert-danger">{{__('translate.No Majors Added to This Supervisor')}}{{--لا يوجد تخصصات مضافة لهذا المشرف لعرضها--}}</h6>
@else
    <table class="table table-bordered table-striped">
        <thead>
            <tr>
                <th>{{__('translate.Major')}} {{-- التخصص --}}</th>
                <th>{{__('translate.Display Major Students')}} {{-- عرض طلاب التخصص --}}</th>
                @if (auth()->user()->u_role_id == 1) {{-- Admin --}}
                    <th>
                        {{__('translate.Remove major for supervisor')}} {{-- حذف التخصص للمشرف --}}
                    </th>
                @endif
            </tr>
        </thead>
        <tbody>
            @foreach ($data as $key)
                <tr>
                    @if (auth()->user()->u_role_id == 1)
                        <td><a href="{{route('admin.majors.index')}}">{{$key->majors->m_name}}</a></td>
                    @else
                        <td>{{$key->majors->m_name}}</td>
                    @endif

                    <td><a href="{{route('supervisors.students.index' , ['id' => $key->ms_super_id , 'ms_major_id' => $key->ms_major_id])}}" class="btn btn-primary btn-xs"><span class="fa fa-users"></span></a></td>
                    @if (auth()->user()->u_role_id == 1) {{-- Admin --}}
                        <th>
                            <button class="btn btn-lg" onclick="delete_major_for_supervisor({{$key->ms_id}})" type="button"><span class="fa fa-trash "></span></button>
                        </th>
                    @endif
                </tr>
            @endforeach
        </tbody>
    </table>
@endif

